<?php

namespace Core;

class Response
{
    public static function html(string $content, int $status = 200): string
    {
        http_response_code($status);
        header('Content-Type: text/html');
        return $content;
    }

    public static function json(array $data, int $status = 200): string
    {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }

    public static function file(string $path, int $status = 200, string $filename = null): void
    {
        if (!file_exists($path)) {
            self::html('<h1>404 Not Found</h1>', 404);
            return;
        }

        http_response_code($status);

        $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mimeType);

        if ($filename) {
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        }

        header('Content-Length: ' . filesize($path));
        readfile($path);
    }

    public static function css(string $path, int $status = 200, string $filename = null): void {

        if (!file_exists($path)) {
            self::html('<h1>404 Not Found</h1>', 404);
            return;
        }

        http_response_code($status);

        $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mimeType);

        if ($filename) {
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        }

        header('Content-Type: text/css');
        readfile($path);
    }

    public static function js(string $path, int $status = 200, string $filename = null): void {
        if (!file_exists($path)) {
            self::html('<h1>404 Not Found</h1>', 404);
            return;
        }

        http_response_code($status);

        $mimeType = mime_content_type($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mimeType);

        if ($filename) {
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        }

        header('Content-Type: application/javascript');
        readfile($path);
    }
}
