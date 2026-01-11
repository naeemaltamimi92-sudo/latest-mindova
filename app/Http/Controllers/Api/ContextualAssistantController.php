<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContextualAssistantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContextualAssistantController extends Controller
{
    /**
     * Dismiss the contextual assistant for this session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dismiss(Request $request): JsonResponse
    {
        ContextualAssistantService::dismiss();

        return response()->json([
            'success' => true,
            'message' => 'Assistant dismissed for this session'
        ]);
    }

    /**
     * Re-enable the contextual assistant.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function enable(Request $request): JsonResponse
    {
        ContextualAssistantService::enable();

        return response()->json([
            'success' => true,
            'message' => 'Assistant re-enabled'
        ]);
    }
}
