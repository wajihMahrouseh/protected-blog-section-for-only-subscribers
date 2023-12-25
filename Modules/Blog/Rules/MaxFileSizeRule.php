<?php

namespace Modules\Blog\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFileSizeRule implements Rule
{
    protected $maxSizeInMB;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($maxSizeInKB)
    {
        $this->maxSizeInMB = $maxSizeInKB / 1024;
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
        # 1st solution .. convert from MB to Byte
        $maxFileSizeInByte = $this->maxSizeInMB * 1024 * 1024; // Convert from MB to Byte


        $validatedArray = [];
        foreach ($value as $index => $value) {
            $validatedArray[] = $value->getSize() <= $maxFileSizeInByte;;
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

        # 2nd solution .. convert from Byte to MB
        // $fileSizeInMB = $value->getSize() / 1048576;
        // return $fileSizeInMB <= $this->maxSizeInMB;
    }


    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.maxFileSize', ['max_size' => $this->maxSizeInMB]);
        // return Lang::get('validation.maxFileSize', ['max_size' => $this->maxSizeInMB]);
        // return __('validation.MaxFileSize', ['max_size' => $this->maxSizeInMB]);

    }
}
