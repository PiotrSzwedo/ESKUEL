<?php

namespace Core;

class Request
{
    protected string $method;
    protected array $query;
    protected array $body;
    protected array $headers;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->query = $_GET;
        $this->headers = getallheaders();

        if (in_array($this->method, ['POST'])) {
            $this->body = $_POST;
        } elseif (in_array($this->method, ['PUT', 'PATCH', 'DELETE'])) {
            $rawInput = file_get_contents('php://input');
            $this->body = $this->parseRawInput($rawInput);
        } else {
            $this->body = [];
        }
    }

    protected function parseRawInput(string $input): array
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            return json_decode($input, true) ?? [];
        } elseif (str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str($input, $output);
            return $output;
        }

        return [];
    }

    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $name, $default = null)
    {
        return $this->headers[$name] ?? $default;
    }
}
