<?php

namespace App\Services;

class EskuelService
{
    private mixed $eskuelPath;

    public function __construct($eskuelPath =  __DIR__ . "/../../config/ESKUEL.php")
    {
        $this->eskuelPath = $eskuelPath;
    }

    public function translateIntoSQL(array $stringArray): string
    {
        $keyPhrases = $this->getEskuelKeyPhrases();

        if (empty($keyPhrases)) {
            return "";
        }

        $translated = [];

        foreach ($stringArray as $word) {
            $key = mb_strtolower($word);
            if (isset($keyPhrases[$key])) {
                $translated[] = $keyPhrases[$key];
            } else {
                $translated[] = '"' . $word . '"';
            }
        }

        return implode(' ', $translated);
    }

    public function getEskuelKeyPhrases(): array
    {
        if (file_exists($this->eskuelPath)) {
            return include $this->eskuelPath;
        }

        return [];
    }
}