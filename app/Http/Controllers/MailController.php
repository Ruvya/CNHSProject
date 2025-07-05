<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;
use App\Mail\WelcomeEmail;
use App\Mail\NotificationEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    /**
     * Display the email sending form.
     *
     * @return \Illuminate\View\View
     */
    public function showEmailForm()
    {
        return view('mail.send-email');
    }

    /**
     * Send a general email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendGeneralEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'sender_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $emailData = [
                'subject' => $request->subject,
                'message' => $request->message,
                'sender_name' => $request->sender_name ?? 'CNHS System',
            ];

            Mail::to($request->to_email)->send(new GeneralEmail($emailData));

            return redirect()->back()->with('success', 'Email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send email: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send a welcome email to a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendWelcomeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_email' => 'required|email',
            'user_name' => 'required|string|max:255',
            'user_type' => 'required|string|in:student,teacher,admin,registrar,principal',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $emailData = [
                'user_name' => $request->user_name,
                'user_type' => $request->user_type,
            ];

            Mail::to($request->to_email)->send(new WelcomeEmail($emailData));

            return redirect()->back()->with('success', 'Welcome email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send welcome email: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send a notification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendNotificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_email' => 'required|email',
            'notification_type' => 'required|string|max:255',
            'notification_message' => 'required|string',
            'action_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $emailData = [
                'notification_type' => $request->notification_type,
                'notification_message' => $request->notification_message,
                'action_url' => $request->action_url,
            ];

            Mail::to($request->to_email)->send(new NotificationEmail($emailData));

            return redirect()->back()->with('success', 'Notification email sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send notification email: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Test email configuration.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function testEmailConfiguration()
    {
        try {
            $testData = [
                'subject' => 'Test Email Configuration',
                'message' => 'This is a test email to verify that the SMTP configuration is working correctly.',
                'sender_name' => 'CNHS System Test',
            ];

            // Send test email to the configured from address
            Mail::to(config('mail.from.address'))->send(new GeneralEmail($testData));

            return redirect()->back()->with('success', 'Test email sent successfully! Check your inbox.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Email configuration test failed: ' . $e->getMessage());
        }
    }

    /**
     * Send bulk emails to multiple recipients.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendBulkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_list' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'sender_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Parse email list (comma or newline separated)
            $emails = preg_split('/[,\n\r]+/', $request->email_list);
            $emails = array_map('trim', $emails);
            $emails = array_filter($emails, function($email) {
                return filter_var($email, FILTER_VALIDATE_EMAIL);
            });

            if (empty($emails)) {
                return redirect()->back()
                    ->with('error', 'No valid email addresses found in the list.')
                    ->withInput();
            }

            $emailData = [
                'subject' => $request->subject,
                'message' => $request->message,
                'sender_name' => $request->sender_name ?? 'CNHS System',
            ];

            $successCount = 0;
            $failedEmails = [];

            foreach ($emails as $email) {
                try {
                    Mail::to($email)->send(new GeneralEmail($emailData));
                    $successCount++;
                } catch (\Exception $e) {
                    $failedEmails[] = $email;
                }
            }

            $message = "Bulk email completed. {$successCount} emails sent successfully.";
            if (!empty($failedEmails)) {
                $message .= " Failed to send to: " . implode(', ', $failedEmails);
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send bulk emails: ' . $e->getMessage())
                ->withInput();
        }
    }
}
