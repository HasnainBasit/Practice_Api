<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
 public function toArray($request)
 {
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'phone_number' => $this->phone_number,
        'status' => $this->status,
    ];
}

public function successResponse($action, $message, $data, $statusCode = 200)
{
    return response()->json([
        'response' => [
            'action' => $action,
            'message' => $message,
            'data' => $data,
        ],
    ], $statusCode);
}

public function errorResponse($action, $message, $data, $statusCode = 400)
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
