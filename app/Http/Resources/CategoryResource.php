<?php

namespace App\Http\Resources;

use App\Http\Controllers\Api\V1\ProductController;
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
            'position' => $this->position,
            'image' => $this->image,
            'category_banner' => $this->category_banner,
            'status' => $this->status,
            'additional' => $this->additional,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
