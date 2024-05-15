<?php

namespace App\Http\Traits;

use App\Models\Document;
use App\Models\Tag;

trait TagTrait
{

    /**
     * Attach tags to the document.
     *
     * @param  Document  $document The document to attach the tags to.
     * @param  array  $tags The array of tags to attach.
     * @return void
     */
    public function tagsAttach(Document $document, array $tags)
    {
        $tagsAttach = [];
        foreach ($tags as $tag) {
            if ($this->tagExists($tag)) {
                $tag_model = $this->findTag($tag);
                $tagsAttach[] = $tag_model->id;
            } else {
                $tag_model = $this->tagStore($tag);
                $tagsAttach[] = $tag_model->id;
            }
        }
        $document->tags()->attach($tagsAttach);
    }

    /**
     * Sync tags with the document.
     *
     * @param  Document  $document The document to sync the tags with.
     * @param  array  $tags The array of tags to sync.
     * @return void
     */
    public function tagsSync(Document $document, array $tags)
    {
        $tagsSync = [];
        foreach ($tags as $tag) {
            if ($this->tagExists($tag)) {
                $tag_model = $this->findTag($tag);
                $tagsSync[] = $tag_model->id;
            } else {
                $tag_model = $this->tagStore($tag);
                $tagsSync[] = $tag_model->id;
            }
        }
        $document->tags()->sync($tagsSync);
    }

    /**
     * Check if a tag exists.
     *
     * @param  string  $tag The tag to check.
     * @return bool Whether the tag exists or not.
     */
    protected function tagExists(string $tag)
    {
        return Tag::where('tag_name', $tag)->exists();
    }

    /**
     * Find a tag by its name.
     *
     * @param  string  $tag The tag to find.
     * @return mixed The found tag or null if not found.
     */
    protected function findTag(string $tag)
    {
        return Tag::where('tag_name', $tag)->first();
    }

    /**
     * Store a new tag.
     *
     * @param  string  $tag The tag to store.
     * @return mixed The newly created tag.
     */
    protected function tagStore(string $tag)
    {
        $tag_model = Tag::create(['tag_name' => $tag]);
        return $tag_model;
    }
}
