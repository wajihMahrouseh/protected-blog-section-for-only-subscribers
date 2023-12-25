<?php

namespace Modules\Blog\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileMimeTypeRule implements Rule
{
    protected $fileMimeType;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fileMimeType)
    {
        $this->fileMimeType = $fileMimeType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // dd($value[0]);

        $validatedArray = [];
        $mimeTypes = $this->fileMimeType;

        foreach ($value as $index => $value) {
            $targetMimeType = $value->getClientMimeType();

            $validatedArray[] = in_array($targetMimeType,  $mimeTypes);
        }

        $containsFalse = false;

        foreach ($validatedArray as $value) {
            if ($value === false) {
                $containsFalse = true;
                break; // Exit the loop as soon as a false value is found
            }
        }

        if ($containsFalse) {
            return !$containsFalse;
        } else {
            return true;
        }
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.fileType');

        // return trans('validation.fileMimeType', ['file_type' => $this->fileMimeType]);
        // return Lang::get('validation.maxFileSize', ['max_size' => $this->maxSizeInMB]);
        // return __('validation.MaxFileSize', ['max_size' => $this->maxSizeInMB]);

    }
}
