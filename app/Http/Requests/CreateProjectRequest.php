<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
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
            'title'         => 'string|required|min:4|max:255',
            'description'   => 'sometimes|nullable|string',
            'minZoom'       => 'required|integer|min:10|max:15|lt:maxZoom',
            'maxZoom'       => 'required|integer|min:18|max:24|gt:minZoom',
            'file'          => 'required|file'
        ];
    }
}
