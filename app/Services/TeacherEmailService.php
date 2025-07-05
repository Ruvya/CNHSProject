<?php

namespace App\Services;

use App\Mail\TeacherCredentialsMail;
use App\Models\Teacher;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class TeacherEmailService
{
    /**
     * Send credentials email to a newly created teacher
     *
     * @param Teacher $teacher
     * @param string $plainPassword
     * @param string|null $loginUrl
     * @return array
     */
    public function sendCredentialsEmail(Teacher $teacher, string $plainPassword, string $loginUrl = null, int $maxRetries = 3): array
    {
        $attempt = 1;
        $lastError = null;

        while ($attempt <= $maxRetries) {
            try {
                // Validate email configuration
                if (!$this->isEmailConfigured()) {
                    throw new Exception('Email configuration is not properly set up');
                }

                // Validate teacher data
                if (!$teacher->email || !filter_var($teacher->email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid teacher email address: ' . $teacher->email);
                }

                // Set default login URL if not provided - use regular login since password change redirect is handled in controller
                $loginUrl = $loginUrl ?: route('login');

                // Log the attempt
                Log::info('Attempting to send teacher credentials email', [
                    'teacher_id' => $teacher->id,
                    'teacher_email' => $teacher->email,
                    'teacher_name' => $teacher->name,
                    'login_url' => $loginUrl,
                    'attempt' => $attempt,
                    'max_retries' => $maxRetries,
                    'timestamp' => now()->toDateTimeString()
                ]);

                // Send the email
                Mail::to($teacher->email)->send(new TeacherCredentialsMail($teacher, $plainPassword, $loginUrl));

                // Log success
                Log::info('Teacher credentials email sent successfully', [
                    'teacher_id' => $teacher->id,
                    'teacher_email' => $teacher->email,
                    'teacher_name' => $teacher->name,
                    'attempt' => $attempt,
                    'timestamp' => now()->toDateTimeString()
                ]);

                return [
                    'success' => true,
                    'message' => 'Credentials email sent successfully to ' . $teacher->email . ($attempt > 1 ? " (after $attempt attempts)" : ''),
                    'attempts' => $attempt
                ];

            } catch (Exception $e) {
                $lastError = $e;

                // Log the error for this attempt
                Log::warning('Teacher credentials email attempt failed', [
                    'teacher_id' => $teacher->id ?? null,
                    'teacher_email' => $teacher->email ?? null,
                    'teacher_name' => $teacher->name ?? null,
                    'attempt' => $attempt,
                    'max_retries' => $maxRetries,
                    'error_message' => $e->getMessage(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'timestamp' => now()->toDateTimeString()
                ]);

                $attempt++;

                // If not the last attempt, wait before retrying
                if ($attempt <= $maxRetries) {
                    sleep(2); // Wait 2 seconds before retry
                }
            }
        }

        // All attempts failed, log final error
        Log::error('Failed to send teacher credentials email after all retries', [
            'teacher_id' => $teacher->id ?? null,
            'teacher_email' => $teacher->email ?? null,
            'teacher_name' => $teacher->name ?? null,
            'total_attempts' => $maxRetries,
            'final_error_message' => $lastError->getMessage(),
            'timestamp' => now()->toDateTimeString()
        ]);

        return [
            'success' => false,
            'message' => 'Failed to send credentials email after ' . $maxRetries . ' attempts: ' . $lastError->getMessage(),
            'error' => $lastError->getMessage(),
            'attempts' => $maxRetries
        ];
    }

    /**
     * Check if email configuration is properly set up
     *
     * @return bool
     */
    private function isEmailConfigured(): bool
    {
        $requiredConfigs = [
            'mail.mailers.smtp.host',
            'mail.mailers.smtp.port',
            'mail.mailers.smtp.username',
            'mail.mailers.smtp.password',
            'mail.from.address'
        ];

        $missingConfigs = [];
        foreach ($requiredConfigs as $config) {
            if (empty(config($config))) {
                $missingConfigs[] = $config;
            }
        }

        if (!empty($missingConfigs)) {
            Log::warning('Missing email configuration', [
                'missing_configs' => $missingConfigs,
                'total_missing' => count($missingConfigs),
                'timestamp' => now()->toDateTimeString()
            ]);
            return false;
        }

        // Additional validation for email format
        $fromAddress = config('mail.from.address');
        if (!filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid from email address in configuration', [
                'from_address' => $fromAddress,
                'timestamp' => now()->toDateTimeString()
            ]);
            return false;
        }

        return true;
    }

    /**
     * Test email configuration by sending a test email
     *
     * @param string $testEmail
     * @return array
     */
    public function testEmailConfiguration(string $testEmail = null): array
    {
        try {
            $testEmail = $testEmail ?: config('mail.from.address');
            
            if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid test email address');
            }

            // Create a dummy teacher for testing
            $dummyTeacher = new Teacher([
                'name' => 'CNHS Test Teacher',
                'email' => $testEmail
            ]);

            $testPassword = 'TestPassword123';
            
            Log::info('Testing email configuration', [
                'test_email' => $testEmail,
                'timestamp' => now()->toDateTimeString()
            ]);

            Mail::to($testEmail)->send(new TeacherCredentialsMail($dummyTeacher, $testPassword));

            Log::info('Email configuration test successful', [
                'test_email' => $testEmail,
                'timestamp' => now()->toDateTimeString()
            ]);

            return [
                'success' => true,
                'message' => 'Test email sent successfully to ' . $testEmail
            ];

        } catch (Exception $e) {
            Log::error('Email configuration test failed', [
                'test_email' => $testEmail ?? 'unknown',
                'error_message' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return [
                'success' => false,
                'message' => 'Email configuration test failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get email configuration status
     *
     * @return array
     */
    public function getEmailConfigurationStatus(): array
    {
        $configs = [
            'SMTP Host' => config('mail.mailers.smtp.host'),
            'SMTP Port' => config('mail.mailers.smtp.port'),
            'SMTP Username' => config('mail.mailers.smtp.username'),
            'SMTP Password' => config('mail.mailers.smtp.password') ? '***CONFIGURED***' : null,
            'From Address' => config('mail.from.address'),
            'From Name' => config('mail.from.name'),
            'Encryption' => config('mail.mailers.smtp.encryption')
        ];

        $isConfigured = $this->isEmailConfigured();

        return [
            'is_configured' => $isConfigured,
            'configurations' => $configs,
            'status' => $isConfigured ? 'Ready' : 'Incomplete Configuration'
        ];
    }

    /**
     * Get detailed error information for troubleshooting
     *
     * @param Exception $exception
     * @return array
     */
    public function getDetailedErrorInfo(Exception $exception): array
    {
        $errorInfo = [
            'error_type' => get_class($exception),
            'error_message' => $exception->getMessage(),
            'error_code' => $exception->getCode(),
            'error_file' => $exception->getFile(),
            'error_line' => $exception->getLine(),
            'timestamp' => now()->toDateTimeString()
        ];

        // Add specific troubleshooting tips based on error type
        $troubleshooting = [];

        if (strpos($exception->getMessage(), 'Connection refused') !== false) {
            $troubleshooting[] = 'Check if the SMTP server is accessible';
            $troubleshooting[] = 'Verify the SMTP host and port settings';
            $troubleshooting[] = 'Check firewall settings';
        }

        if (strpos($exception->getMessage(), 'Authentication failed') !== false) {
            $troubleshooting[] = 'Verify your email username and password';
            $troubleshooting[] = 'For Gmail, use App Password instead of regular password';
            $troubleshooting[] = 'Check if 2-factor authentication is enabled';
        }

        if (strpos($exception->getMessage(), 'SSL') !== false || strpos($exception->getMessage(), 'TLS') !== false) {
            $troubleshooting[] = 'Check SSL/TLS encryption settings';
            $troubleshooting[] = 'Try different encryption methods (tls, ssl, or none)';
            $troubleshooting[] = 'Verify the port matches the encryption type';
        }

        $errorInfo['troubleshooting_tips'] = $troubleshooting;

        return $errorInfo;
    }

    /**
     * Log comprehensive email operation details
     *
     * @param string $operation
     * @param array $context
     * @param string $level
     * @return void
     */
    public function logEmailOperation(string $operation, array $context = [], string $level = 'info'): void
    {
        $logData = array_merge([
            'operation' => $operation,
            'timestamp' => now()->toDateTimeString(),
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'from_address' => config('mail.from.address'),
        ], $context);

        Log::log($level, "Email operation: {$operation}", $logData);
    }
}
