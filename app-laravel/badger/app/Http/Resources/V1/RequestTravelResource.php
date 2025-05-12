<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestTravelResource extends JsonResource
{
    public static $wrap = "RequestTravels";
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome_solicitante' => $this->nome_solicitante,
            'destino' => $this->destino,
            'data_ida' => $this->data_ida,
            'data_volta' => $this->data_volta,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'usuario' => [
                'id' => $this->user->id,
                'nome' => $this->user->name,
                'email' => $this->user->email,
            ],
        ];
    }

    public function with(Request $request){
        return [
            'status' => 'success'
        ];
    }

    public function withResponse(Request $request, \Illuminate\Http\JsonResponse $response){
        $response->header('Accept', 'application/json');
    }
}
