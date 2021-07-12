<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\JsonResource;

class ParamResource extends JsonResource
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
            'dispay_name' => $attributes['display_name'],
            'values' => ParamValueResource::collection($this->values),
        ];
        //    return parent::toArray($request);
    }
}
