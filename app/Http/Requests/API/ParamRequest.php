<?php

namespace App\Http\Requests\API;




class ParamRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-read');

        return [
            //
        ];
    }
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-create');

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

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-update');

        $rules = [
            'name' => 'sometimes|min:3|max:128|unique:params,name',
        ];
        $rules['name'] = $rules['name'] . "," . $this->route("param_id");
        return $rules;
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-delete');
        return [
            //
        ];
    }
}
