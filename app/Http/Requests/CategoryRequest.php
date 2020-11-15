<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            return [
                'name' => 'sometimes|max:255',
                'parent_id' => 'sometimes|nullable|exists:categories,id'
            ];
        }
        return [
            'name' => 'required|max:255',
            'parent_id' => 'sometimes|nullable|exists:categories,id',
        ];
    }
}
