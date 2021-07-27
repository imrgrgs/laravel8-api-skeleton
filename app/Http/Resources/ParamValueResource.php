<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class ParamValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attributes = parent::toArray($request);

        foreach ($this->getTranslatableAttributes() as $field) {

            $attributes[$field] = $this->getTranslation($field, App::getLocale());
        }
        return [
            'id' => $attributes['id'],
            'name' => $attributes['name'],
            'param_id' => $attributes['param_id'],
            'code' => $attributes['code'],
            'symbol' => $attributes['symbol'],
            'color' => $attributes['color'],
            'is_visible' => $attributes['is_visible'],
            'is_default' => $attributes['is_default'],
            'param_value_description' =>  new ParamValueDescriptionResource($this->description),
        ];
        //   return parent::toArray($request);
    }
}
