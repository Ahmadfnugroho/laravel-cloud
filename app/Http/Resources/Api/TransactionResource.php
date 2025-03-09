<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\UserResource;




class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
return[
    'id' => $this->id,
    'booking_transaction_id' => $this->booking_transaction_id,
    'grand_total' => $this->grand_total,
    'status' => $this->status,
    'start_date' => $this->start_date,
    'end_date' => $this->end_date,
    'duration' => $this->duration,
    'user' => new UserResource($this->whenLoaded('user')),
    'user_name' => $this->user->name,
    'user_email' => $this->user->email,
    'userPhoneNumbers' => UserPhoneNumberResource::collection($this->whenLoaded('userPhoneNumbers')),
    'products' => ProductResource::collection($this->whenLoaded('products')),
    'detailTransactions' => DetailTransactionResource::collection($this->whenLoaded('detailTransactions')),
    'rentalIncludes' => RentalIncludeResource::collection($this->whenLoaded('rentalIncludes')),

];
    }
}
