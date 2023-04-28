<?php

namespace App\Http\Requests;

class CallRequest extends ApiRequest
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
          'from_phone' => 'required|numeric',
          'to_phone' => 'required|numeric'
        ];
    }
}
