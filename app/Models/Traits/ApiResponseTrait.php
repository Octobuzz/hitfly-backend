<?php

namespace App\Models\Traits;

use Illuminate\Http\Request;

trait ApiResponseTrait
{
    /**
     * Send a failed response with a msg
     *
     * @param  string $message
     * @param  $status
     * @return \Illuminate\Http\RedirectResponse
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
     * Send a successful response
     *
     * @param array $data
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
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
