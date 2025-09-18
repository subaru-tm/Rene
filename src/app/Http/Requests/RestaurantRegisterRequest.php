<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestaurantRegisterRequest extends FormRequest
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
            'name' => 'required|max:20',
            'img_file' => 'required|image|mimes:jpeg,png',
            'area_id' => 'required',
            'genre_id' => 'required',
            'description' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店舗名を入力してください',
            'name.max' => '店舗名は20字以内で入力してください',
            'img_file.required' => '店舗画像を選択してください',
            'img_file.image' => '店舗画像は画像ファイルを選択してください',
            'img_file.mimes' => '「.jpeg」もしくは「.png」形式でアップロードしてください',
            'area_id.required' => 'エリアを選択してください',
            'genre_id.required' => 'ジャンルを選択してください',
            'description.required' => '店舗紹介を入力してください',
            'description.max' => '店舗紹介は255文字以内で入力してください',
        ];
    }
}
