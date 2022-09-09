<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    /**
     * 認証
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * バリデーションルールの設定
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Shop
            'image' => 'image|mimes:jpg,jpeg,png|max:2028',
            // Products
            'files.image.*' => 'image|mimes:jpg,jpeg,png|max:2028',
        ];
    }

    /**
     * messagesメソッド
     * 
     * validationメッセージの上書き
     * messagesメソッドをオーバーライド
     */
    public function messages()
    {
        return [
            'image.image' => '指定されたファイルが画像ではありません。',
            'image.mimes' => '指定された拡張子（jpg/jpeg/png)ではありません。',
            'image.max' => 'ファイルサイズは2MB以内としてください。',
        ];
    }
}
