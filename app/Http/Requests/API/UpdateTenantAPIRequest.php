<?php

namespace App\Http\Requests\API;


class UpdateTenantAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('tenants-update');
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
            'name' => 'sometimes|min:3|max:128,unique:tenants,name',
        ];
        $rules['name'] = $rules['name'] . "," . $this->route("tenant_id");
        return $rules;
    }
}
