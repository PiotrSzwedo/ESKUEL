<?php

namespace App\Services;

use Random\RandomException;

class DbWriteService
{
    private string $directory;

    public function __construct($directory = "databases")
    {
        $this->directory = __DIR__. "/../../". $directory;
    }

    public function writeDatabase(string $host, string $database, string $username, string $password = '', int $port = 22): bool {
        try {
            $fileName = sprintf('%s%s.json', time(), bin2hex(random_bytes(4)));

            $data = [
                'host' => $host,
                'database' => $database,
                'username' => $username,
                'password' => $password == '' ? '' : password_hash($password, PASSWORD_DEFAULT),
                'port' => $port,
                'created_at' => date('c')
            ];

            $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $filePath = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

            while(file_exists($filePath)){
                $fileName = sprintf('%s%s.json', time(), bin2hex(random_bytes(random_int(4, 9999999999))));
                $filePath = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
            }

            return file_put_contents($filePath, $content) !== false;

        }   catch (RandomException $e) {
            return false;
        }
    }

    public function updateDatabase(string $fileName, string $host, string $database, string $username, string $password = '', int $port = 22): bool
    {
        $data = [
            'host' => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password == '' ? '' : password_hash($password, PASSWORD_DEFAULT),
            'port' => $port,
            'created_at' => date('c')
        ];

        $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $filePath = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            return file_put_contents($filePath, $content) !== false;
        }

        return false;
    }

    public function readDatabase($fileName) :array
    {
        $filePath = $this->directory . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);

            if ($content){
                $data = json_decode($content, true);
                $data["id"] = str_replace(".json", "", $fileName);
                return $data;
            }
        }

        return [];
    }

    public function getDatabases(int $offset = 0, int $limit = 30): array
    {
        $allFiles = scandir($this->directory);
        $jsonFiles = array_filter($allFiles, function ($file) {
            return pathinfo($file, PATHINFO_EXTENSION) === 'json';
        });

        $jsonFiles = array_values($jsonFiles);
        $pagedFiles = array_slice($jsonFiles, $offset, $limit);

        $databases = [];

        foreach ($pagedFiles as $file) {
            $filePath = $this->directory . DIRECTORY_SEPARATOR . $file;
            $content = file_get_contents($filePath);
            if ($content) {
                $data = json_decode($content, true);
                $data["id"] = str_replace(".json", "", $file);
                $databases[] = $data;
            }
        }

        return $databases;
    }
}