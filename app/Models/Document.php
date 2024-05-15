<?php

namespace App\Models;

use App\Http\Traits\DownloadableFile;
use App\Http\Traits\DownloadFileTrait;
use App\Http\Traits\Loggable;
use App\Http\Traits\Notificatable;
use Devyousef\Visitor\Traits\Visitorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    use HasFactory, DownloadableFile, Visitorable, Loggable, Notificatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'file',
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
        'comments',
        'visitors',
        'downloads'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($document) {
            $document->user_id = Auth::user()->id;
        });
    }

    /**
     * Get the user that owns the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category that owns the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get all of the comments for the document.
     *
     * This function defines a polymorphic relationship between the document model and the comment model,
     * allowing the document to have many comments associated with it.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * The tags that belong to the Tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'document_tag');
    }
}
