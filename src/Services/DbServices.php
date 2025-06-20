<?php

namespace App\Services;

use PDO;
use PDOException;

class DbServices
{
    public function connect(string $host, string $database, string $username, string $password = '', int $port = 3306): false|PDO
    {
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
            ]);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['db_connection'] = [
                'host' => $host,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'port' => $port,
            ];

            return $pdo;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            error_log("Błąd połączenia z bazą: " . $e->getMessage());
            return false;
        }
    }

    public function execute(PDO $pdo, string $sql, array $params = []): false|int|array
    {
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if (stripos(trim($sql), 'select') === 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $stmt->rowCount();

        } catch (PDOException $e) {
            error_log("Błąd wykonania zapytania SQL: " . $e->getMessage());
            return false;
        }
    }

    public function getPdoFromSession(): ?PDO
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['db_connection'])) {
            return null;
        }

        $conn = $_SESSION['db_connection'];

        if (!isset($conn['host'], $conn['database'], $conn['username'], $conn['password'], $conn['port'])) {
            return null;
        }

        try {
            $dsn = "mysql:host={$conn['host']};port={$conn['port']};dbname={$conn['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $conn['username'], $conn['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            error_log("Błąd połączenia PDO z sesji: " . $e->getMessage());
            return null;
        }
    }
}