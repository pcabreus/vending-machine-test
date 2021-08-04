<?php

namespace App\Tests\Domain\Model;

use App\Domain\Exceptions\NoChangeException;
use App\Domain\Model\Coin;
use App\Domain\Model\CoinList;
use App\Domain\Model\Money;
use PHPUnit\Framework\TestCase;

class CoinListTest extends TestCase
{
    public function testCoinList(): void
    {
        $coinList = CoinList::create();
        $coinList->addCoin(Coin::create(0.05));
        self::assertEquals(5, $coinList->getTotal()->getValue());

        $coinList->addCoins(Coin::create(0.05), 1);
        self::assertEquals(10, $coinList->getTotal()->getValue());

        $money = $coinList->diff(Money::create(0.01));
        self::assertEquals(0.09, $money->toFloat());
    }

    public function testAddCoinList(): void
    {
        $coinList = CoinList::create();
        self::assertEquals(0, $coinList->getTotal()->getValue());

        $coinList->addCoinList(CoinList::create());
        self::assertEquals(0, $coinList->getTotal()->getValue());

        $coinListExtra = CoinList::create();
        $coinListExtra->addCoins(Coin::create(0.05), 2);
        $coinList->addCoinList($coinListExtra);
        self::assertEquals(10, $coinList->getTotal()->getValue());
    }

    /**
     * @dataProvider provider
     */
    public function testGetChange(CoinList $coinList, Money $money, CoinList $expected): void
    {
        $result = $coinList->getChange($money);
        self::assertEquals($expected, $result);
    }

    public function provider(): array
    {
        $coinList = $this->createCoinListHelper(
            [
                '0.05' => 5,
                '0.10' => 3,
                '0.25' => 2,
                '1.00' => 1,
            ]
        );
        $money = Money::create(0);

        return [
            [
                clone $coinList,
                $money->sum(100),
                $this->createCoinListHelper(
                    [
                        '1.00' => 1,
                    ]
                ),
            ],
            [
                clone $coinList,
                $money->sum(105),
                $this->createCoinListHelper(
                    [
                        '1.00' => 1,
                        '0.05' => 1,
                    ]
                ),
            ],
            [
                clone $coinList,
                $money->sum(200),
                $this->createCoinListHelper(
                    [
                        '1.00' => 1,
                        '0.25' => 2,
                        '0.10' => 3,
                        '0.05' => 4,
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider providerWithExceptions
     */
    public function testGetChangeWithNoChange(CoinList $coinList, Money $money): void
    {
        $this->expectException(NoChangeException::class);
        $coinList->getChange($money);
    }

    public function providerWithExceptions(): array
    {
        $coinList = $this->createCoinListHelper(
            [
                '0.05' => 5,
                '0.10' => 3,
                '0.25' => 2,
                '1.00' => 1,
            ]
        );
        $money = Money::create(0);

        return [
            [
                clone $coinList,
                $money->sum(3),
            ],
            [
                clone $coinList,
                $money->sum(10000),
            ],
        ];
    }

    private function createCoinListHelper(array $initValues): CoinList
    {
        $coinList = CoinList::create();
        foreach ($initValues as $value => $amount) {
            $coinList->addCoins(Coin::create($value), $amount);
        }

        return $coinList;
    }


}
