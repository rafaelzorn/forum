<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = collect([
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$this->user.'',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($this->method() == 'PUT') {
            $rules->forget('password');

            if (!is_null($this->password)) {
                $rules = $rules->merge([
                    'password' => 'min:6|confirmed',
                ]);
            }
        }

        return $rules->all();
    }
}
