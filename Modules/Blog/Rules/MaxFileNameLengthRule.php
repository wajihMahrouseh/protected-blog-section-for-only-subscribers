<?php

namespace Modules\Blog\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFileNameLengthRule implements Rule
{
    protected $maxFileNameLength;

    /**
     * Create a new rule instance.
     *
     * @param int $maxFileNameLength
     * @return void
     */
    public function __construct($maxFileNameLength)
    {
        $this->maxFileNameLength = $maxFileNameLength;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $validatedArray = [];
        foreach ($value as $index => $value) {
            $fileName = $value->getClientOriginalName();

            $validatedArray[] = strlen($fileName) <= $this->maxFileNameLength;
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
        return trans('validation.maxFileNameLength', ['max_length' => $this->maxFileNameLength]);
    }
}
