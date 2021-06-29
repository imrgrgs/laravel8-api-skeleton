<?php

namespace App\Http\Requests\API;




class UpdateParamAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-update');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|min:3|max:128|unique:params,name',
        ];
        $rules['name'] = $rules['name'] . "," . $this->route("param_id");
        return $rules;
    }
}
