<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InAppGuideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InAppGuideController extends Controller
{
    /**
     * Get guide for specific page.
     */
    public function show(Request $request, string $pageIdentifier): JsonResponse
    {
        $guide = InAppGuideService::getCurrentGuide($pageIdentifier);

        return response()->json([
            'success' => true,
            'guide' => $guide,
        ]);
    }

    /**
     * Dismiss guide.
     */
    public function dismiss(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string',
        ]);

        InAppGuideService::dismiss($request->page_identifier);

        return response()->json([
            'success' => true,
            'message' => 'Guide dismissed',
        ]);
    }

    /**
     * Reset guide.
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string',
        ]);

        InAppGuideService::reset($request->page_identifier);

        return response()->json([
            'success' => true,
            'message' => 'Guide reset',
        ]);
    }
}
