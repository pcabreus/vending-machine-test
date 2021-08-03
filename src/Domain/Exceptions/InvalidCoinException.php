<?php

namespace App\Domain\Exceptions;

use Throwable;

class InvalidCoinException extends \DomainException
{
    public function __construct(float $floatAmount, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Invalid money. The vending machine accepts money in the form of 0.05, 0.10, 0.25 and 1, given `%d`',
                $floatAmount
            ),
            $code,
            $previous
        );
    }

}