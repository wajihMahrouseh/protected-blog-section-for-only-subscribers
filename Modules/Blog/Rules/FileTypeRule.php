<?php

namespace Modules\Blog\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileTypeRule implements Rule
{
    protected $fileType;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($fileType)
    {
        $this->fileType = $fileType;
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
        $validatedArray = [];
        $fileType = $this->fileType;

        foreach ($value as $index => $value) {
            $validatedArray[] = $value->gettype() == $fileType;
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

        // return trans('validation.fileType', ['file_type' => $this->fileType]);
        // return Lang::get('validation.maxFileSize', ['max_size' => $this->maxSizeInMB]);
        // return __('validation.MaxFileSize', ['max_size' => $this->maxSizeInMB]);

    }
}
