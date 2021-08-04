<?php

namespace App\Domain\Exceptions;

class NoChangeException extends \DomainException
{
    public function __construct(float $change, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'There is no enough coins for your change `%s`',
                $change,
            ),
            $code,
            $previous
        );
    }
}