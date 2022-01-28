<?php

namespace App\Support;

use App\Constants\ResponseConstants;
use Illuminate\Http\JsonResponse;

class ApiResponder {
    /**
     * Return success response.
     *
     * @param mixed $data
     * @param int|null $status
     * @param string|null $type
     * @return JsonResponse
     */
    public static function success($data = null, ?int $status = 200, ?string $type = ResponseConstants::SUCCESS)
    {
        return response()->json(compact('data', 'type'), $status);
    }

    /**
     * Return error response.
     *
     * @param string|null $error
     * @param mixed $details
     * @param int|null $status
     * @param string|null $type
     * @return JsonResponse
     */
    public static function error(?string $error = null, $details = null, ?int $status = 500, ?string $type = ResponseConstants::UNKNOWN_ERROR)
    {
        return response()->json(compact('error', 'details', 'type'), $status);
    }

    /**
     * Throw error response.
     *
     * @param string|null $error
     * @param array|null $details
     * @param int|null $status
     * @param string|null $type
     * @return void
     */
    public static function throwError(?string $error = null, ?array $details = null, ?int $status = 500, ?string $type = ResponseConstants::UNKNOWN_ERROR)
    {
        self::error($error, $details, $status, $type)->throwResponse();
    }
}
