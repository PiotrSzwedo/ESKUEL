<?php

namespace App\Controllers\Inquiries;

use App\Controllers\Controller;
use App\Services\DbServices;
use App\Services\EskuelService;
use App\Services\ViewService;
use Core\Request;
use Core\Response;

class EskuelController extends Controller
{
    private EskuelService $eskuelService;

    private DbServices $dbService;

    public function __construct(EskuelService $eskuelService, DbServices $dbServices, ViewService $viewService){
        $this->eskuelService = $eskuelService;
        $this->dbService = $dbServices;
        parent::__construct($viewService);
    }

    public function show(Request $request): string
    {
        $requestedPage = (int) $request->input("page", 1);
        $perPage = (int) $request->input("per_page", 50);

        if ($perPage < 1 || $perPage > 100) {
            $perPage = 50;
        }

        $keywords = $this->eskuelService->getEskuelKeyPhrases();

        if (empty($keywords)) {
            return Response::json(["message" => "No search results found."], 404);
        }

        $chunkedKeywords = array_chunk($keywords, $perPage);
        $totalPages = count($chunkedKeywords);

        $pageIndex = $requestedPage - 1;

        if ($pageIndex < 0 || $pageIndex >= $totalPages) {
            return Response::json([
                "message" => "Page not found.",
                "page" => $requestedPage,
                "total_pages" => $totalPages,
                "key_words" => []
            ], 404);
        }

        return Response::json([
            "page" => $requestedPage,
            "total_pages" => $totalPages,
            "key_words" => $chunkedKeywords[$pageIndex]
        ], 200);
    }

    public function execute(Request $request): string
    {
        $sql = $this->eskuelService->translateIntoSQL($request->input("query", ""));

        if ($sql == null || empty(trim($sql))) {
            return Response::json(["success" => false, "message" => "Missing required fields"], 422);
        }

        $pdo = $this->dbService->getPdoFromSession();
        if (!$pdo) return Response::json(["success" => false, "message" => "Connection error"], 500);

        $result = $this->dbService->execute($pdo, $sql);

        return Response::json([...$result], 200);
    }
}