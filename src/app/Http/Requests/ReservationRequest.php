<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'date' => 'required',
            'time' => 'required',
            'number' => 'required|custom_check_number',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '予約日付を指定してください',
            'time.required' => '予約時刻を指定してください',
            'number.custom_check_number' => '人数を数字で入力してください',
        ];
    }
}
