<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class TitleNotFoundException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => 'Title not found',
            'message' => $this->getMessage() ?: 'The requested title could not be found.',
        ], 404);
    }
}