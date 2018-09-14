<?php

namespace App\Http\Requests\Api;

class SocialAuthorizationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            //required_without code和access_token 必须有一个
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        if($this->social_type == 'weixin' && !$this->code){
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
