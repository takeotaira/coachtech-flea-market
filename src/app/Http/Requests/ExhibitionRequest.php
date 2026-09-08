<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    private const NAME_MAX_LENGTH = 255;
    private const BRAND_NAME_MAX_LENGTH = 255;
    private const DESCRIPTION_MAX_LENGTH = 255;
    private const CATEGORY_MIN_COUNT = 1;
    private const PRICE_MIN_VALUE = 0;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png',
            ],
            'categories' => [
                'required',
                'array',
                'min:' . self::CATEGORY_MIN_COUNT,
            ],
            'categories.*' => [
                'exists:categories,id',
            ],
            'condition_id' => [
                'required',
                'exists:conditions,id',
            ],
            'name' => [
                'required',
                'string',
                'max:' . self::NAME_MAX_LENGTH,
            ],
            'brand_name' => [
                'nullable',
                'string',
                'max:' . self::BRAND_NAME_MAX_LENGTH,
            ],
            'description' => [
                'required',
                'string',
                'max:' . self::DESCRIPTION_MAX_LENGTH,
            ],
            'price' => [
                'required',
                'numeric',
                'min:' . self::PRICE_MIN_VALUE,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => '商品画像を選択してください',
            'image.image' => '商品画像は画像ファイルを選択してください',
            'image.mimes' => '商品画像はJPEGまたはPNG形式で選択してください',

            'categories.required' => 'カテゴリーを選択してください',
            'categories.min' => 'カテゴリーを'
                . self::CATEGORY_MIN_COUNT
                . 'つ以上選択してください',

            'condition_id.required' => '商品の状態を選択してください',
            'condition_id.exists' => '商品の状態を正しく選択してください',

            'name.required' => '商品名を入力してください',
            'name.max' => '商品名は'
                . self::NAME_MAX_LENGTH
                . '文字以内で入力してください',

            'brand_name.max' => 'ブランド名は'
                . self::BRAND_NAME_MAX_LENGTH
                . '文字以内で入力してください',

            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は'
                . self::DESCRIPTION_MAX_LENGTH
                . '文字以内で入力してください',

            'price.required' => '販売価格を入力してください',
            'price.numeric' => '販売価格は数字で入力してください',
            'price.min' => '販売価格は'
                . self::PRICE_MIN_VALUE
                . '円以上で入力してください',
        ];
    }
}