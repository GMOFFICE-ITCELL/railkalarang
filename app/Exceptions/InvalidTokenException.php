<?php

// app\Exceptions\InvalidTokenException.php

namespace App\Exceptions;

use Exception;

class InvalidTokenException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Invalid token'
        ], 403);
    }
}
