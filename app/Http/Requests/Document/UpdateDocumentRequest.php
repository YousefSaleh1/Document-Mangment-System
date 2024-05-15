<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'  => 'nullable|integer|exists:categories,id',
            'title'        => 'nullable|string|max:25',
            'description'  => 'nullable|string',
            'file'         => 'nullable|file|mimes:png,jpg,jpeg,gif,sug,pdf,doc,docx|max:10000|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/sug,file/pdf,file/doc,file/docx',
        ];
    }
}
