<?php

namespace Modules\Blog\app\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Blog\Rules\FileTypeRule;
use Modules\Blog\Enums\BlogStatusEnum;
use Modules\Blog\Rules\MaxFileSizeRule;
use Modules\Blog\Rules\FileMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Blog\Rules\FileContentMimeType;
use Modules\Blog\Rules\MaxFileNameLengthRule;

class StoreBlogRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $fileMimeTypes = [
            'image/jpeg',
            'image/png',
        ];

        return [
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'body' => ['required', 'string', 'min:3', 'max:120'],
            'publishDate' => ['required', 'date', 'date_format:Y-m-d'],
            'status' => ['required', 'integer', Rule::in(BlogStatusEnum::getValues())],
            'photo' => [
                'required', 'image',
                new MaxFileNameLengthRule(191),
                new MaxFileSizeRule(10240), // size in 10240 KB = 10MB
                new FileTypeRule('file'),
                new FileMimeTypeRule($fileMimeTypes),
                new FileContentMimeType($fileMimeTypes)
            ],
        ];
    }


    public function validated($key = null, $default = null)
    {
        return [
            'title' => $this->title,
            'content' => $this->body,
            'publish_date' => $this->publishDate,
            'status' => $this->status,
            'user_id' => auth()->user()->id
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
