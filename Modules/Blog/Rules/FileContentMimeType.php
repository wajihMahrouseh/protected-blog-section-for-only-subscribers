<?php

namespace Modules\Blog\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileContentMimeType implements Rule
{
    protected $validMimeTypes;

    public function __construct($validMimeTypes)
    {
        $this->validMimeTypes = $validMimeTypes;
    }

    public function passes($attribute, $value)
    {
        $validatedArray = [];

        foreach ($value as $index => $value) {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMimeType = finfo_file($fileInfo, $value->getPathname());
            finfo_close($fileInfo);

            $validatedArray[] = in_array($fileMimeType, $this->validMimeTypes);
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

    public function message()
    {
        return trans('validation.fileType');
    }
}
