<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    private const NAME_MAX_LENGTH = 20;
    private const PASSWORD_MIN_LENGTH = 8;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:' . self::NAME_MAX_LENGTH,
            ],

            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email'),
            ],

            'password' => [
                'required',
                'string',
                'min:' . self::PASSWORD_MIN_LENGTH,
                'confirmed',
            ],

            'password_confirmation' => [
                'required',
                'string',
                'min:' . self::PASSWORD_MIN_LENGTH,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => 'お名前は'
                . self::NAME_MAX_LENGTH
                . '文字以内で入力してください',

            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'email.unique' => 'このメールアドレスは既に登録されています',

            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは'
                . self::PASSWORD_MIN_LENGTH
                . '文字以上で入力してください',
            'password.confirmed' => 'パスワードと一致しません',

            'password_confirmation.required' => '確認用パスワードを入力してください',
            'password_confirmation.min' => '確認用パスワードは'
                . self::PASSWORD_MIN_LENGTH
                . '文字以上で入力してください',
        ];
    }
}