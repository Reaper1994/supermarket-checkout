<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InvalidQuantityException extends Exception
{
    // Custom exception message and any other properties or methods as needed
    public function __construct($message = "Invalid quantity provided", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
