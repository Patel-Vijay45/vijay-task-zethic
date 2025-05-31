<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'address_id' => $this->address_id,
            'status' => $this->status,
            'shipping_method' => $this->shipping_method,
            'shipping_description' => $this->shipping_description,
            'is_gift' => $this->is_gift,
            'total_item_count' => $this->total_item_count,
            'total_qty_ordered' => $this->total_qty_ordered,
            'grand_total' => $this->grand_total,
            'user_id' => $this->user_id,
        ];
    }
}
