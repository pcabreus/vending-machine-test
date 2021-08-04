<?php

namespace App\Domain\Exceptions;

class InsufficientMoneyException extends \DomainException
{
    public function __construct(string $selector, float $price, float $amount, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Insufficient money to get the item `%s` -> %s. provided `%s`',
                $selector,
                number_format($price, 2),
                number_format($amount, 2)
            ),
            $code,
            $previous
        );
    }
}