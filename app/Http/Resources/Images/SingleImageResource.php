<?php

namespace App\Http\Resources\Images;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleImageResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd(url($this->url));
        return ['url' => url($this->url)];
    }
}
