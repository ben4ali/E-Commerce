<?php

namespace Exceptions;

use Exception;
use Throwable;

class CSPNotInitialized extends Exception implements Throwable
{
    public function __construct(string $message = "Vous devez initialize() la classe avant de l'utiliser.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}