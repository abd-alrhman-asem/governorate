<?php

namespace App\Http\Resources;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'competent_authorities' => $this['competent_authorities'],
            'destinations' => $this['destinations'],
            'complaint_types' => $this['complaint_types'],
        ];
    }
}
