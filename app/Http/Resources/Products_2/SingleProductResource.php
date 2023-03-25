<?php

namespace App\Http\Resources\Products_2;

use App\Http\Resources\Categories\CategoriesResource;
use App\Http\Resources\Images\ImagesResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'thumbnail'=> url($this->thumbnailUrl),
            'prices'=>$this->getPrices(),
            'categories' => new CategoriesResource($this->categories),
            'images' => new ImagesResource($this->images),
            'description'=>$this->description,
            'created_at'=>$this->created_at

        ];
    }

    protected function getPrices(): array
    {
        return [
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->endPrice
        ];
    }
}
