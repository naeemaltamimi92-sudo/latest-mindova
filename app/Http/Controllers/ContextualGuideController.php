<?php

namespace App\Http\Controllers;

use App\Models\UserGuidePreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContextualGuideController extends Controller
{
    /**
     * Dismiss guide for a specific page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dismiss(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string|max:255',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            UserGuidePreference::dismiss(
                auth()->id(),
                $request->input('page_identifier')
            );

            return response()->json([
                'success' => true,
                'message' => 'Guide dismissed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to dismiss guide',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset guide for a specific page (show it again).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string|max:255',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        try {
            UserGuidePreference::reset(
                auth()->id(),
                $request->input('page_identifier')
            );

            return response()->json([
                'success' => true,
                'message' => 'Guide reset successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset guide',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if guide is dismissed for a specific page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkStatus(Request $request): JsonResponse
    {
        $request->validate([
            'page_identifier' => 'required|string|max:255',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'dismissed' => false,
                'message' => 'User not authenticated'
            ], 401);
        }

        $isDismissed = UserGuidePreference::isDismissed(
            auth()->id(),
            $request->input('page_identifier')
        );

        return response()->json([
            'success' => true,
            'dismissed' => $isDismissed
        ]);
    }
}
