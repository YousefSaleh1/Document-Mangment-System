<?php

namespace App\Models;

use App\Http\Traits\Followable;
use App\Http\Traits\Loggable;
use App\Http\Traits\Notificatable;
use Devyousef\Visitor\Traits\Visitorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Category extends Model
{
    use HasFactory, Followable, Visitorable, Loggable, Notificatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'followers',
        'visitors'
    ];

    /**
     * The relationships to be touched when updating the model.
     *
     * This property specifies the relationships that should be touched (update the `updated_at` timestamp)
     * when the model is updated.
     *
     * @var array<int, string>
     */
    protected $touches = [
        'documents',
        'comments'
    ];

    /**
     * Get all of the documents for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id', 'id');
    }

    /**
     * Get all of the comments for the category.
     *
     * This function defines a polymorphic relationship between the category model and the comment model,
     * allowing the category to have many comments associated with it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
