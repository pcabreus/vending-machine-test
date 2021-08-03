<?php

namespace App\Domain\Exceptions;

class NotFoundItemException extends \DomainException
{
    public function __construct(string $selector, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'The requested item `%s` is not available',
                $selector
            ),
            $code,
            $previous
        );
    }
}