<?php

namespace App\Exceptions;

use Exception;

class ApiErrorException extends Exception
{
    public function __construct(string $message = "", int $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $this->getCode()
        ], $this->getCode());
    }
}
