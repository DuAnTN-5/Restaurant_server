<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'summary' => $this->summary,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'availability' => $this->availability,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url,
            // 'image_url' => json_decode($this->image_url, true),
            'stock_quantity' => $this->stock_quantity,
            'ingredients' => $this->ingredients,
            'position' => $this->position,
            'tags' => $this->tags,
            'status' => $this->status,
            'product_code' => $this->product_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
