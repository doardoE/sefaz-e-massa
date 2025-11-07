<?php

namespace App\Http\Resources\api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArrecadacoesResource extends JsonResource
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
            'tributo' => $this->tributo,
            'mes'=> $this->mes,
            'ano'=>$this->ano,
            'valor'=>$this->valor,
        ];
    }
}
