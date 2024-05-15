<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'notificatable_id',
        'notificatable_type'
    ];

    /**
     * Get the parent model of the polymorphic relationship.
     *
     * This method defines the inverse of a polymorphic relationship, allowing the Notifications model to retrieve the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notificatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * The users that belong to the UserController
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('is_read')
            ->withTimestamps();
    }
}
