<?php

namespace Core;

class Response
{
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html');
        echo $content;
    }

    public static function json(array $data, int $status = 200): string|false
    {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
