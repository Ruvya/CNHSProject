<?php

namespace App\Helpers;

class UploadHelper
{
    /**
     * Configure PHP settings for large file uploads
     */
    public static function configureForLargeUploads()
    {
        // Store original values
        $originalSettings = [
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'max_input_time' => ini_get('max_input_time'),
        ];

        // Set new values for large file processing
        ini_set('max_execution_time', env('EXCEL_MAX_EXECUTION_TIME', 600));
        ini_set('memory_limit', env('EXCEL_MEMORY_LIMIT', '512M'));
        ini_set('max_input_time', env('MAX_INPUT_TIME', 300));

        return $originalSettings;
    }

    /**
     * Restore original PHP settings
     */
    public static function restoreOriginalSettings(array $originalSettings)
    {
        foreach ($originalSettings as $setting => $value) {
            ini_set($setting, $value);
        }
    }

    /**
     * Get upload configuration info
     */
    public static function getUploadInfo()
    {
        return [
            'max_file_size' => self::formatBytes(self::getMaxUploadSize()),
            'max_execution_time' => ini_get('max_execution_time') . ' seconds',
            'memory_limit' => ini_get('memory_limit'),
            'post_max_size' => ini_get('post_max_size'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
        ];
    }

    /**
     * Get the maximum upload size allowed by PHP configuration
     */
    public static function getMaxUploadSize()
    {
        $maxUpload = self::parseSize(ini_get('upload_max_filesize'));
        $maxPost = self::parseSize(ini_get('post_max_size'));
        $memoryLimit = self::parseSize(ini_get('memory_limit'));

        return min($maxUpload, $maxPost, $memoryLimit);
    }

    /**
     * Parse size string to bytes
     */
    private static function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);

        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }

        return round($size);
    }

    /**
     * Format bytes to human readable format
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Check if file size is within limits
     */
    public static function isFileSizeValid($fileSize)
    {
        $maxSize = self::getMaxUploadSize();
        return $fileSize <= $maxSize;
    }

    /**
     * Get estimated processing time based on file size
     */
    public static function getEstimatedProcessingTime($fileSize)
    {
        // Rough estimate: 1MB = 10 seconds processing time
        $estimatedSeconds = ($fileSize / (1024 * 1024)) * 10;

        // Minimum 30 seconds, maximum 10 minutes
        return max(30, min(600, $estimatedSeconds));
    }

    /**
     * Log upload attempt
     */
    public static function logUploadAttempt($fileName, $fileSize, $userId = null)
    {
        \Log::info('Excel upload attempt', [
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'file_size_formatted' => self::formatBytes($fileSize),
            'user_id' => $userId,
            'estimated_time' => self::getEstimatedProcessingTime($fileSize) . ' seconds',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Log upload completion
     */
    public static function logUploadCompletion($fileName, $summary, $processingTime = null)
    {
        \Log::info('Excel upload completed', [
            'file_name' => $fileName,
            'summary' => $summary,
            'processing_time' => $processingTime ? $processingTime . ' seconds' : 'unknown',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Log upload error
     */
    public static function logUploadError($fileName, $error, $userId = null)
    {
        \Log::error('Excel upload failed', [
            'file_name' => $fileName,
            'error' => $error,
            'user_id' => $userId,
            'timestamp' => now()->toISOString()
        ]);
    }
}
