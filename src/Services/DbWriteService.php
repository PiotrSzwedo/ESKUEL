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

            $fileName = sprintf('%s_%s.json', time(), bin2hex(random_bytes(4)));

            $data = [
                'host' => $host,
                'database' => $database,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'port' => $port,
                'created_at' => date('c')
            ];

            $content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $filePath = rtrim($this->directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

            return file_put_contents($filePath, $content) !== false;

        }   catch (RandomException $e) {
            return false;
        }
    }


    public function readDatabase($fileName) :array
    {
        if (file_exists($fileName)){
            $filePath = $this->directory . DIRECTORY_SEPARATOR . $fileName;
            $content = file_get_contents($filePath);

            if ($content){
                return json_decode($content, true);
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
                $databases[] = json_decode($content, true);
            }
        }

        return $databases;
    }
}