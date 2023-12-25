<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class JsonResponseService
{
    public function generateJsonResponse(
        $metaData = null,
        $data = null,
        $message = null,
        $statusCode = 200
    ) {
        $response = [];

        if ($metaData !== null) {
            $response['meta'] = $metaData;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($message !== null) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }
}
