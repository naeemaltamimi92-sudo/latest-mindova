<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NdaAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'content',
        'version',
        'is_active',
        'effective_date',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'effective_date' => 'datetime',
    ];

    /**
     * Get all signings for this NDA.
     */
    public function signings()
    {
        return $this->hasMany(ChallengeNdaSigning::class);
    }

    /**
     * Get the active general NDA from file storage.
     */
    public static function getActiveGeneralNda()
    {
        $filePath = storage_path('app/nda/general_nda.md');

        if (!file_exists($filePath)) {
            \Log::error('General NDA file not found', ['path' => $filePath]);
            return null;
        }

        $content = file_get_contents($filePath);

        // Return an object with the expected properties
        $nda = new \stdClass();
        $nda->id = 1;
        $nda->title = 'Mindova Platform General Non-Disclosure Agreement';
        $nda->type = 'general';
        $nda->version = '1.0';
        $nda->content = $content;
        $nda->is_active = true;
        $nda->effective_date = now();

        return $nda;
    }

    /**
     * Get the active challenge-specific NDA template from file storage.
     */
    public static function getActiveChallengeNda()
    {
        $filePath = storage_path('app/nda/challenge_nda.md');

        if (!file_exists($filePath)) {
            \Log::error('Challenge NDA file not found', ['path' => $filePath]);
            return null;
        }

        $content = file_get_contents($filePath);

        // Return an object with the expected properties
        $nda = new \stdClass();
        $nda->id = 2;
        $nda->title = 'Mindova Challenge-Specific Non-Disclosure Agreement';
        $nda->type = 'challenge_specific';
        $nda->version = '1.0';
        $nda->content = $content;
        $nda->is_active = true;
        $nda->effective_date = now();

        return $nda;
    }
}
