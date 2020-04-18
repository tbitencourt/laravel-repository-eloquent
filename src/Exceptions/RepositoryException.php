<?php

/**
 * PHP version 7.4
 * @category PHP
 * @package  LaravelRepositoryEloquent
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
 */

declare(strict_types=1);

namespace Tbitencourt\LaravelRepositoryEloquent\Exceptions;

use Exception;
use Throwable;

/**
 * Class RepositoryException
 * @category PHP
 * @package  Tbitencourt\LaravelRepositoryEloquent\Exceptions
 * @author   Thales Bitencourt <thales.bitencourt@devthreads.com.br>
 * @author   DevThreads Team <contato@devthreads.com.br>
 * @license  https://www.devthreads.com.br  Copyright
 * @link     https://www.devthreads.com.br
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
