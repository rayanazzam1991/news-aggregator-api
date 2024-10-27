<?php

namespace Modules\Article\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetArticlesListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, ValidationRule|string>|string|ValidationRule>
     */
    public function rules(): array
    {
        return [
            'title'=>['sometimes','string'],
            'keywords' => ['sometimes', 'array'],
            'date' => ['sometimes', 'date'],
            'author_id' => ['sometimes', 'integer', 'exists:authors,id'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'source_id' => ['sometimes', 'integer', 'exists:sources,id'],
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
