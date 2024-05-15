<?php

namespace App\Http\Traits;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\DocumentResource;
use App\Models\Category;
use App\Models\Document;

trait GetCommentItemTrait
{

    /**
     * Get the comment item resource based on the commentable type and ID.
     *
     * @param  string  $commentableType The commentable type.
     * @param  int  $commentableId The commentable ID.
     * @return mixed The item information or "Not Found!" if not found.
     */
    public function getCommentItemResource(string $commentableType, int $commentableId)
    {
        switch ($commentableType) {
            case 'App\Models\Category':
                $category = Category::findOrFail($commentableId);
                return ['item_type' => 'Category', new CategoryResource($category)];
                break;
            case 'App\Models\Document':
                $document = Document::findOrFail($commentableId);
                return ['item_type' => 'Document', new DocumentResource($document)];
                break;
            default:
                return 'Not Found!';
                break;
        }
    }

    /**
     * Get the comment item based on the commentable type and ID.
     *
     * @param string $commentableType
     * @param int $commentableId
     * @return \App\Models\Category|\App\Models\Document|string
     */
    public function getCommentItem(string $commentableType, int $commentableId)
    {
        switch ($commentableType) {
            case 'App\Models\Category':
                $category = Category::findOrFail($commentableId);
                return $category;
                break;
            case 'App\Models\Document':
                $document = Document::findOrFail($commentableId);
                return $document;
                break;
            default:
                return 'Not Found!';
                break;
        }
    }
}
