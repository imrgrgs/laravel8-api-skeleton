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
            'name' => 'required|min:3|max:128|unique:params,name',
            'display_names' => 'required|array|min:1',
            'dysplay_names.*' => 'required|string|min:3|max:32',
            'descriptions' => 'sometimes|required|array|min:1',
            'descriptions.*' => 'sometimes|required|string|min:3|max:128',
            'values' => 'sometimes|required|array|min:1',
            'values.names' => 'sometimes|required|array|min:1',

            'values.*.names.*' => 'sometimes|required|string|min:3|max:32',
            'values.*.code' => 'sometimes|required|string|distinct|min:1|max:8',
            'values.*.symbol' => 'sometimes|nullable|string|min:3|max:32',
            'values.*.color' => 'sometimes|nullable|string|min:3|max:32',
            'values.*.is_visible' => 'sometimes|nullable|boolean',
            'values.*.is_default' => 'sometimes|nullable|boolean',


        ];
    }
}
