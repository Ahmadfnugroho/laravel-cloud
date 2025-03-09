<?php

namespace App\Http\Resources\Api;

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
            'quantity' => $this->quantity,
            'price' => $this->price,
            'thumbnail' => $this->thumbnail,
            'status' => $this->status,
            'slug' => $this->slug,
            'premiere' => $this->premiere,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'subCategory' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'rentalIncludes' => RentalIncludeResource::collection($this->whenLoaded('rentalIncludes')),
            'productSpecifications' => ProductSpecificationResource::collection($this->whenLoaded('productSpecifications')),
            'productPhotos' => ProductPhotoResource::collection($this->whenLoaded('productPhotos')),


        ];
    }
}
