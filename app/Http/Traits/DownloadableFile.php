<?php

namespace App\Http\Traits;

use App\Models\Download;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait DownloadableFile
{

    /**
     * Get the downloads associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany The downloads relationship.
     */
    public function downloads(): MorphMany
    {
        return $this->morphMany(Download::class, 'downloadable');
    }

    /**
     * Download the file associated with the model.
     *
     * @return void
     */
    public function downloadFile()
    {
        if (!$this->hasDownloadByUser()) {
            $this->downloads()->create([
                'downloadable_id'     => $this->id,
                'downloadable_type'   => get_class($this),
            ]);
        }
    }

    /**
     * Check if the model has been downloaded by the current user.
     *
     * @return bool Whether the model has been downloaded by the user.
     */
    public function hasDownloadByUser()
    {
        return $this->downloads()->where('user_id', Auth::user()->id)
            ->where('downloadable_id', $this->id)
            ->where('downloadable_type', get_class($this))
            ->exists();
    }

    /**
     * Get the number of downloads for the model.
     *
     * @return int The number of downloads.
     */
    public function downloadsCount()
    {
        return $this->downloads->count();
    }
}
