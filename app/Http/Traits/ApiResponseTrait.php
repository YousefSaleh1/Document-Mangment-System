<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{
    /**
     * Generate API response.
     *
     * @param  mixed  $data The response data.
     * @param  string  $token The access token.
     * @param  string  $message The response message.
     * @param  int  $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse The API response.
     */
    public function apiResponse(mixed $data, string $token, string $message, int $status)
    {

        $array = [
            'data' => $data,
            'message' => $message,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];

        return response()->json($array, $status);
    }

    /**
     * Generate custom API response.
     *
     * @param  mixed  $data The response data.
     * @param  string  $message The response message.
     * @param  int  $status The HTTP status code.
     * @return \Illuminate\Http\JsonResponse The API response.
     */
    public function customeResponse(mixed $data, string $message, int $status)
    {
        $array = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($array, $status);
    }
}
