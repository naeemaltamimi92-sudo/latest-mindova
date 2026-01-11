<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeAttachment;
use App\Jobs\ProcessChallengeAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChallengeAttachmentController extends Controller
{
    /**
     * Upload an attachment for a challenge
     *
     * @param Request $request
     * @param Challenge $challenge
     * @return JsonResponse
     */
    public function upload(Request $request, Challenge $challenge): JsonResponse
    {
        // Check if user owns the challenge
        if ($challenge->company_id !== auth()->user()->company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to upload attachments for this challenge'
            ], 403);
        }

        // Validate the file
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:' . (ChallengeAttachment::MAX_FILE_SIZE / 1024), // Convert to KB
                'mimes:' . implode(',', array_keys(ChallengeAttachment::ALLOWED_TYPES)),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');

        // Additional MIME type validation
        if (!ChallengeAttachment::isAllowedType($file->getMimeType())) {
            return response()->json([
                'success' => false,
                'message' => 'File type not allowed. Only PDF files are accepted.'
            ], 422);
        }

        try {
            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('challenge_attachments/' . $challenge->id, $filename, 'public');

            // Create attachment record
            $attachment = ChallengeAttachment::create([
                'challenge_id' => $challenge->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => ChallengeAttachment::getExtensionFromMime($file->getMimeType()),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);

            // Dispatch job to process attachment asynchronously
            ProcessChallengeAttachment::dispatch($attachment);

            return response()->json([
                'success' => true,
                'message' => 'Attachment uploaded successfully',
                'attachment' => [
                    'id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'file_type' => $attachment->file_type,
                    'file_size' => $attachment->formatted_file_size,
                    'url' => $attachment->file_url,
                    'processed' => $attachment->processed,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all attachments for a challenge
     *
     * @param Challenge $challenge
     * @return JsonResponse
     */
    public function index(Challenge $challenge): JsonResponse
    {
        $attachments = $challenge->attachments()
            ->with('uploader:id,name')
            ->get()
            ->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'file_type' => $attachment->file_type,
                    'file_size' => $attachment->formatted_file_size,
                    'url' => $attachment->file_url,
                    'processed' => $attachment->processed,
                    'uploaded_by' => $attachment->uploader->name,
                    'uploaded_at' => $attachment->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'attachments' => $attachments
        ]);
    }

    /**
     * Delete an attachment
     *
     * @param Challenge $challenge
     * @param ChallengeAttachment $attachment
     * @return JsonResponse
     */
    public function destroy(Challenge $challenge, ChallengeAttachment $attachment): JsonResponse
    {
        // Check if user owns the challenge
        if ($challenge->company_id !== auth()->user()->company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete attachments for this challenge'
            ], 403);
        }

        // Check if attachment belongs to this challenge
        if ($attachment->challenge_id !== $challenge->id) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment does not belong to this challenge'
            ], 404);
        }

        try {
            // Delete file from storage
            Storage::disk('public')->delete($attachment->file_path);

            // Delete attachment record
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download an attachment
     *
     * @param Challenge $challenge
     * @param ChallengeAttachment $attachment
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Challenge $challenge, ChallengeAttachment $attachment)
    {
        // Check if attachment belongs to this challenge
        if ($attachment->challenge_id !== $challenge->id) {
            abort(404, 'Attachment not found');
        }

        // Check if user has access (company owner or volunteer assigned to a task in this challenge)
        $user = auth()->user();
        $hasAccess = false;

        if ($user->company && $challenge->company_id === $user->company->id) {
            $hasAccess = true;
        } elseif ($user->volunteer) {
            // Check if volunteer has any task assignments in this challenge
            $hasAccess = $challenge->tasks()
                ->whereHas('assignments', function ($query) use ($user) {
                    $query->where('volunteer_id', $user->volunteer->id);
                })
                ->exists();
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized to access this attachment');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
