<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    private const NAME_MAX_LENGTH = 20;
    private const POSTAL_CODE_PATTERN = '/^\d{3}-\d{4}$/';
    private const POSTAL_CODE_FORMAT = 'XXX-XXXX';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
            ],
            'name' => [
                'required',
                'string',
                'max:' . self::NAME_MAX_LENGTH,
            ],
            'postal_code' => [
                'required',
                'regex:' . self::POSTAL_CODE_PATTERN,
            ],
            'address' => [
                'required',
                'string',
            ],
            'building' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.image' => 'プロフィール画像は画像ファイルを選択してください',
            'profile_image.mimes' => 'プロフィール画像はJPEGまたはPNG形式で選択してください',

            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は'
                . self::NAME_MAX_LENGTH
                . '文字以内で入力してください',

            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は'
                . self::POSTAL_CODE_FORMAT
                . 'の形式で入力してください',

            'address.required' => '住所を入力してください',
        ];
    }
}