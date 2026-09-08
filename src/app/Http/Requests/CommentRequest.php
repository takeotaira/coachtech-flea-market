<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    private const CONTENT_MAX_LENGTH = 255;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:' . self::CONTENT_MAX_LENGTH,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'コメントを入力してください',
            'content.max' => 'コメントは'
                . self::CONTENT_MAX_LENGTH
                . '文字以内で入力してください',
        ];
    }
}