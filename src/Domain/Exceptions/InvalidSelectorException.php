<?php

namespace App\Domain\Exceptions;

use App\Domain\Model\Selector;

class InvalidSelectorException extends \DomainException
{
    public function __construct(string $selector, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Invalid selector. Valid selectors are `%s`, given `%d`',
                implode(',', Selector::SELECTORS),
                $selector
            ),
            $code,
            $previous
        );
    }
}