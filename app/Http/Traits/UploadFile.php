<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait UploadFile
{
    /**
     * Upload a file.
     *
     * This method uploads a file to the specified folder and returns the file path.
     *
     * @param  Request  $request The HTTP request object.
     * @param  string  $folder The folder to upload the file to.
     * @param  string  $fileColumnName The name of the file input field in the request.
     * @return string The file path.
     *
     * @throws Exception If the file name contains multiple file extensions.
     */
    public function uploadFile(Request $request, string $folder, string $fileColumnName)
    {

        $file = $request->file($fileColumnName);
        $originalName = $file->getClientOriginalName();

        if (preg_match('/\.[^.]+\./', $originalName)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }

        $fileName = Str::random(32);
        $mime_type = $file->getClientOriginalExtension();;
        $type = explode('/', $mime_type);

        $path = $file->storeAs($folder, $fileName . '.' . end($type), 'public');
        return $path;
    }

    /**
     * Check if a file exists and upload it.
     *
     * This method checks if a file exists in the request and uploads it to the specified folder.
     * If the file doesn't exist, it returns null.
     *
     * @param  Request  $request The HTTP request object.
     * @param  string  $folder The folder to upload the file to.
     * @param  string  $fileColumnName The name of the file input field in the request.
     * @return string|null The file path if the file exists, otherwise null.
     */
    public function fileExists(Request $request, string $folder, string $fileColumnName)
    {
        if (empty($request->file($fileColumnName))) {
            return null;
        }
        return $this->uploadFile($request, $folder, $fileColumnName);
    }
}
