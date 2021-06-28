<?php

namespace App\Http\Requests\API;




class RegisterParamAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-create');
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
            'name' => 'required|min:3|max:128,unique:params,name',
        ];
    }
}
