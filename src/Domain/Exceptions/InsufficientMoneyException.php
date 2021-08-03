<?php

namespace App\Domain\Exceptions;

class InsufficientMoneyException extends \DomainException
{
    public function __construct(string $selector, float $price, float $amount, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Insufficient money to get the item `%s` -> %d. provided `%d`',
                $selector,
                $price,
                $amount
            ),
            $code,
            $previous
        );
    }
}