<?php

namespace App\Traits;

use App\Constants\ResponseConstants;
use App\Support\ApiResponder;
use Illuminate\Http\JsonResponse;

trait ApiTools
{
    /**
     * Return success response.
     *
     * @param mixed $data
     * @param int|null $status
     * @param string|null $type
     * @return JsonResponse
     */
    public function success($data = null, ?int $status = 200, ?string $type = ResponseConstants::SUCCESS)
    {
        return ApiResponder::success($data, $status, $type);
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
    public function error(?string $error = null, $details = null, ?int $status = 500, ?string $type = ResponseConstants::UNKNOWN_ERROR)
    {
        return ApiResponder::error($error, $details, $status, $type);
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
    public function throwError(?string $error = null, ?array $details = null, ?int $status = 500, ?string $type = ResponseConstants::UNKNOWN_ERROR)
    {
        ApiResponder::throwError($error, $details, $status, $type);
    }
}
