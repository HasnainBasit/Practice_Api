<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'description' => $this->description,
            'image_path' => $this->image_path,
            'status' => $this->status,
        ];
    }

    public static function validationErrorResponse($validator)
    {
        return response()->json([
            'error' => [
                'action' => 'validation_error',
                'message' => 'Validation failed',
                'data' => $validator->errors(),
            ],
        ], 400);
    }


    public static function notFoundErrorResponse()
    {
        return response()->json([
            'error' => [
                'action' => 'not_found',
                'message' => 'Post not found',
            ],
        ], 404);
    }
    public static function successResponse($action, $message, $data, $statusCode = 200)
    {
        return response()->json([
            'response' => [
                'action' => $action,
                'message' => $message,
                'data' => $data,
            ],
        ], $statusCode);
    }

}
