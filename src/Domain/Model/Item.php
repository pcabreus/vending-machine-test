<?php

namespace App\Domain\Model;

class Item
{
    public const SELECTOR_SODA = 'SODA';
    public const SELECTOR_WATER = 'WATER';
    public const SELECTOR_JUICE = 'JOICE';
    public const SELECTORS = [
        self::SELECTOR_SODA,
        self::SELECTOR_WATER,
        self::SELECTOR_JUICE,
    ];

    private string $selector;
    private int $count;
    private Money $price;

    private function __construct(string $selector, int $count, Money $price)
    {
        $this->selector = $selector;
        $this->count = $count;
        $this->price = $price;
    }

    public static function createSoda(int $count, Money $price): self
    {
        return new static(self::SELECTOR_SODA, $count, $price);
    }

    public static function createWater(int $count, Money $price): self
    {
        return new static(self::SELECTOR_WATER, $count, $price);
    }

    public static function createJuice(int $count, Money $price): self
    {
        return new static(self::SELECTOR_JUICE, $count, $price);
    }

    public static function isSelectorValid(string $selector): bool
    {
        return in_array($selector, self::SELECTORS);
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