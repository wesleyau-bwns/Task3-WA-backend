<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Return a success JSON response.
     *
     * @param  string  $message
     * @param  mixed|null  $data
     * @param  int  $status
     * @param  array  $meta  
     */
    protected function success(
        string $message,
        $data = null,
        int $status = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        $response = ['message' => $message];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }
}
