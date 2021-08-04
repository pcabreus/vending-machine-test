<?php

namespace App\Application\UpdateItem;

class UpdateItem
{
    private string $selector;
    private int $count;

    public function __construct(string $selector, int $count)
    {
        $this->selector = $selector;
        $this->count = $count;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}