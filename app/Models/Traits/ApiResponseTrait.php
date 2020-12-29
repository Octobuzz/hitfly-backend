<?php

namespace App\Models\Traits;

trait ApiResponseTrait
{
    /**
     * Send a failed response with a msg.
     *
     * @param string   $message
     * @param int|null $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFailedResponse($message, $status = null, $result = false)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'result' => $result,
        ]);
    }

    /**
     * Send a successful response.
     *
     * @param $data
     * @param int|null $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSuccessResponse($data, $status = null, $result = true)
    {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'result' => $result,
        ]);
    }
}
