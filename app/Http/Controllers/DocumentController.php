<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Document;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\TagResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\Notificatable;
use App\Http\Traits\TagTrait;
use App\Http\Traits\UploadFile;

class DocumentController extends Controller
{
    use ApiResponseTrait, TagTrait, UploadFile, Notificatable;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::all();
        $data = DocumentResource::collection($documents);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        $file_path = $this->uploadFile($request, 'Document', 'file');

        $document = Document::create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'file'        => $file_path,
        ]);

        if ($request->has('tags')) {
            $this->tagsAttach($document, $request->tags);
        }

        $data = new DocumentResource($document);
        return $this->customeResponse($data, 'Created Successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->visit();

        $data['document'] = new DocumentResource($document);
        $data['tags']     = TagResource::collection($document->tags);
        $data['comments'] = CommentResource::collection($document->comments);
        return $this->customeResponse($data, 'Done!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        $document->category_id = $request->input('category_id') ?? $document->category_id;
        $document->title       = $request->input('title') ?? $document->title;
        $document->description = $request->input('description') ?? $document->description;
        $document->file        = $this->fileExists($request, 'Document', 'file') ?? $document->file;
        $document->save();

        if ($request->has('tags')) {
            $this->tagsSync($document, $request->tags);
        }

        $data = new DocumentResource($document);
        return $this->customeResponse($data, 'Successfully Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->tags()->detach();
        $document->delete();
        return response()->json(['message' => 'Document Deleted'], 200);
    }

    /**
     * Download the file associated with a document.
     *
     * This method downloads the file associated with the specified document.
     *
     * @param  Document  $document The document to download its file.
     * @return Illuminate\Http\Response The HTTP response with the downloaded file.
     */
    public function download(Document $document)
    {
        $document->downloadFile();
        $path = storage_path('app\public\\' . $document->file);
        return response()->download($path);
    }
}
