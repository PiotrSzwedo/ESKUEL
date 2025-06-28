<?php

namespace App\Controllers\Inquiries;

use App\Controllers\Controller;
use App\Services\EskuelService;
use App\Services\ViewService;
use Core\Request;
use Core\Response;

class EskuelController extends Controller
{
    private EskuelService $eskuelService;

    public function __construct(EskuelService $eskuelService, ViewService $viewService){
        $this->eskuelService = $eskuelService;
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
}