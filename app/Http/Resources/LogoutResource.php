<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'response' => [
                'action' => $this['action'],
                'message' => $this['message'],
            ],
        ];
    }

    public function successResponse($action)
    {
        return response()->json($this->toArray(request()), 200);
    }

    public function errorResponse($action, $message, $data = null, $statusCode = 400)
    {
        return response()->json([
            'error' => [
                'action' => $action,
                'message' => $message,
                'data' => $data,
            ],
        ], $statusCode);
    }
}
