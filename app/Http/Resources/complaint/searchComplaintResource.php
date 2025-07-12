<?php

namespace App\Http\Resources\complaint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class searchComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'complaint_number'=> $this['id'],
            'email'=>$this['email'],
            'phone_number'=>$this['phone_number'],

        ];
    }
}
