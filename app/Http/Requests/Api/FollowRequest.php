<?php

namespace App\Http\Requests\Api;

class FollowRequest extends FormRequest
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
            'user_id' => 'required|integer',
        ];
    }

    public function messages(){
        return [
            'user_id.required' => '需关注的用户的id不能为空',
        ];
    }
}
