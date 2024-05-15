<?php

namespace App\Http\Traits;

use App\Models\Follow;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait Followable
{

    /**
     * Get the followers associated with the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany The followers relationship.
     */
    public function followers(): MorphMany
    {
        return $this->morphMany(Follow::class, 'followable');
    }

    /**
     * Toggle the follow status for the model.
     *
     * @return string The follow status message.
     */
    public function followToggle()
    {
        if ($this->hasFollowedByUser()) {
            $this->unFollow();
            return 'you are Unfollowing ';
        } else {
            $this->follow();
            return 'you are Following ';
        }
    }

    /**
     * Check if the model has been followed by the current user.
     *
     * @return bool Whether the model has been followed by the user.
     */
    protected function hasFollowedByUser()
    {
        return $this->followers()->where('user_id', Auth::user()->id)
            ->where('followable_id', $this->id)
            ->where('followable_type', get_class($this))
            ->exists();
    }

    /**
     * Follow the model.
     *
     * @return void
     */
    protected function follow()
    {
        $this->followers()->create([
            'followable_id'     => $this->id,
            'followable_type'   => get_class($this),
        ]);
    }

    /**
     * Unfollow the model.
     *
     * @return void
     */
    protected function unFollow()
    {
        $this->followers()->where([
            'user_id' => Auth::user()->id,
            'followable_id'     => $this->id,
            'followable_type'   => get_class($this),
        ])->delete();
    }

    /**
     * Unfollow the model.
     *
     * @return void
     */
    public function followersCount()
    {
        return $this->followers->count();
    }
}
