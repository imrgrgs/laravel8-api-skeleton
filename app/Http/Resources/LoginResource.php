<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'user' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'active' => $this->active,
                'link' => $this->link,

                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'tenant' => $this->tenant,
            'access' => [
                'token' => $this->access_token,
                'token_type' => $this->token_type,
                'expires_in' => $this->expires_in,
            ]

        ];
        //       return parent::toArray($request);
    }
}
