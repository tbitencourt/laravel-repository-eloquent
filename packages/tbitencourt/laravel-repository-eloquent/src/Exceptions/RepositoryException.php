<?php

namespace Tbitencourt\LaravelRepositoryEloquent\Exceptions;

use Exception;
use Throwable;

/**
 * Class RepositoryException
 * @package Tbitencourt\LaravelRepositoryEloquent\Exceptions
 * @author  Thales Bitencourt
 */
class RepositoryException extends Exception
{
    /**
     * RepositoryException constructor.
     * @param mixed $message
     * @param mixed $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = '0', Throwable $previous = null)
    {
        // Invoke parent
        parent::__construct($message, $code, $previous);
    }
}
