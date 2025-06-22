<?php

namespace App\Services;

use PDO;
use PDOException;

class DbServices
{
    public function connect(string $host, string $database, string $username, string $password = '', int $port = 3306): false|PDO
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

    public function execute(PDO $pdo, string $sql): false|array
    {
        try {
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute();

            if ($stmt->columnCount() > 0) {
                return [
                    'type' => 'result',
                    'rows' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                    'columns' => array_map(
                        fn($i) => $stmt->getColumnMeta($i)['name'] ?? 'col' . $i,
                        range(0, $stmt->columnCount() - 1)
                    ),
                ];

            }

            return [
                'type' => 'message',
                'affected' => $stmt->rowCount(),
                'message' => "Zapytanie wykonane poprawnie. Zmieniono wierszy: " . $stmt->rowCount(),
            ];

        } catch (PDOException $e) {
            return [
                'type' => 'error',
                'error' => $e->getMessage()
            ];
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

    public function isDatabaseConnected(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['db_connection'])) {
            return false;
        }


        $conn = $_SESSION['db_connection'];

        try {
            $dsn = "mysql:host={$conn['host']};port={$conn['port']};dbname={$conn['database']};charset=utf8mb4";
            $pdo = new PDO($dsn, $conn['username'], $conn['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            if ($pdo){
                return true;
            }
        } catch (PDOException $e) {
            return false;
        }

        return false;
    }

}