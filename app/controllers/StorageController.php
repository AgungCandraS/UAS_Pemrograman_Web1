<?php
/**
 * Storage Controller
 * Serves uploaded files securely
 */

class StorageController {
    
    public function serve($directory, $filename) {
        // Validate directory
        $allowedDirs = ['uploads', 'exports'];
        if (!in_array($directory, $allowedDirs)) {
            http_response_code(404);
            die('Not found');
        }
        
        // Security: prevent directory traversal
        $filename = basename($filename);
        $directory = basename($directory);
        
        // Build file path
        $filePath = STORAGE_PATH . '/' . $directory . '/' . $filename;
        
        // Check if file exists
        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File not found');
        }
        
        // Get file info
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        // Allowed mime types
        $allowedMimes = [
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            http_response_code(403);
            die('Forbidden file type');
        }
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        
        // Serve file
        readfile($filePath);
        exit;
    }
}
