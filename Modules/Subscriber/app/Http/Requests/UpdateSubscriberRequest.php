<?php

namespace Modules\Subscriber\app\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Subscriber\Enums\SubscriberStatusEnum;

class UpdateSubscriberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('subscriber')->user->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:64'],
            'username' => ['required', 'string', 'min:3', 'max:64', Rule::unique('users', 'username')->ignore($id),],
            'password' => ['required', 'string', 'min:3', 'max:64'],
            'confirmPassword' => ['required', 'string', 'min:3', 'max:64', 'same:password'],
            'status' => ['required', 'integer', Rule::in(SubscriberStatusEnum::getValues())],

        ];
    }


    public function validated($key = null, $default = null)
    {
        return [
            'userData' => [
                'name' => $this->name,
                'username' => $this->username,
                'password' => $this->password,
            ],

            'subscriberData' => [
                'status' => $this->status,
            ]
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
