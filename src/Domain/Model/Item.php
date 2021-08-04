<?php

namespace App\Domain\Model;

class Item
{
    private string $selector;
    private int $count;
    private Money $price;

    private function __construct(string $selector, int $count, Money $price)
    {
        $this->selector = $selector;
        $this->count = $count;
        $this->price = $price;
    }

    public static function create(string $selector, int $count, Money $price): self
    {
        return new static($selector, $count, $price);
    }

    public function decrease(): void
    {
        --$this->count;
    }

    public function getSelector(): string
    {
        return $this->selector;
    }

    public function setSelector(string $selector): self
    {
        $this->selector = $selector;

        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): self
    {
        $this->price = $price;

        return $this;
    }
}