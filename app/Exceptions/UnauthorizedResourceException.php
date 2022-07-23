<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class UnauthorizedResourceException extends Exception
{
    public function render()
    {
        return new JsonResponse(['message' => 'Access denied'], 403);
    }
}
