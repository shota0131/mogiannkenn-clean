<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image'       => 'required|file|mimes:jpeg,jpg,png',
            'category_id' => 'required|array', // ✅ 配列を受け入れる
            'category_id.*' => 'integer|exists:categories,id', // ✅ 各要素が整数で有効なID
            'condition' => 'required|string',
            'price'       => 'required|numeric|min:0',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required'        => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'description.max'      => '商品説明は255文字以内で入力してください。',
            'image.required'       => '商品画像をアップロードしてください。',
            'image.mimes'          => '商品画像は.jpegまたは.png形式でアップロードしてください。',
            'category_id.required' => '商品のカテゴリーを選択してください。',
            'category_id.array'    => 'カテゴリーの形式が正しくありません。',
            'category_id.*.integer'=> 'カテゴリーIDが無効です。',
            'category_id.*.exists' => '選択したカテゴリーが存在しません。',
            'condition.required'=> '商品の状態を選択してください。',
            'price.required'       => '商品価格を入力してください。',
            'price.numeric'        => '商品価格は数値で入力してください。',
            'price.min'            => '商品価格は0円以上で入力してください。',
        ];
    }
}
