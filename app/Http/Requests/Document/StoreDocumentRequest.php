<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
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
            'category_id'  => 'required|integer|exists:categories,id',
            'title'        => 'required|string',
            'description'  => 'required|string',
            'file'         => 'required|file|mimes:png,jpg,jpeg,gif,svg,pdf,doc,docx|max:10000000|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'tags'         => 'required|array'
        ];
    }
}
