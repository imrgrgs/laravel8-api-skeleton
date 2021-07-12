<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParamValueResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return parent::toArray($request);
    }
}
