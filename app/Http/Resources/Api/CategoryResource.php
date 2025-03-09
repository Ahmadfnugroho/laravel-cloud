<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'photo' => $this->photo,
            'slug' => $this->slug,
            'subcategories_count' => $this->subcategories_count,
            'subCategories' => SubCategoryResource::collection($this->whenLoaded('subCategories')),
            'products_count' => $this->products_count,
            'products' => ProductResource::collection($this->whenLoaded('products')),

        ];
    }
}
