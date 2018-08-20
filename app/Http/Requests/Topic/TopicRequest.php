<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
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
        return [
            'category_id' => 'required',
            'title'       => 'required',
            'content'     => 'required',
            'active'      => 'required'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The category field is required.',
            'title.required'       => 'The title field is required.',
            'content.required'     => 'The content field is required.',
            'active.required'      => 'The active field is required.'
        ];
    }
}
