<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id'          => $this->id,
            'Owner'       => $this->user_id,
            'Name'        => $this->name,
            'CategoryId'  => $this->category_id,
            'Description' => $this->description,
            'price'       => $this->price,
            'quantity'    => $this->quantity,
            'weight'      => $this->weight,
            'width'       => $this->width,
            'height'      => $this->height,
            'length'      => $this->length,
            'formattedPrice' => $this->formatted_price,
        ];
    }
}
