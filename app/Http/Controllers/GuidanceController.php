<?php

namespace App\Http\Controllers;

use App\Services\GuidanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GuidanceController extends Controller
{
    protected $guidanceService;

    public function __construct(GuidanceService $guidanceService)
    {
        $this->guidanceService = $guidanceService;
    }

    /**
     * Mark guidance step as completed
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function completeStep(Request $request): JsonResponse
    {
        $request->validate([
            'step_id' => 'required|string',
        ]);

        $this->guidanceService->completeStep(
            $request->user(),
            $request->input('step_id')
        );

        return response()->json([
            'success' => true,
            'message' => 'Step marked as completed'
        ]);
    }

    /**
     * Get user's guidance progress percentage
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProgress(Request $request): JsonResponse
    {
        $percentage = $this->guidanceService->getProgressPercentage($request->user());

        return response()->json([
            'progress' => $percentage
        ]);
    }

    /**
     * Reset guidance progress (for testing/restart)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetProgress(Request $request): JsonResponse
    {
        $this->guidanceService->resetProgress($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Guidance progress reset successfully'
        ]);
    }
}
