<?php

namespace App\Service;


class CurrencyGenerator
{
    public const USD = 'USD';
    public const BTC = 'BTC';
    public const ETH = 'ETH';

    private const MIN = 'min';
    private const MAX = 'max';

    private static $scale = [
        self::USD => 2,
        self::BTC => 8,
        self::ETH => 18,
    ];

    private static $generatedAmounts = [
        self::USD => [self::MIN => 50, self::MAX => 500000],
        self::BTC => [self::MIN => 0.00005, self::MAX => 5000],
        self::ETH => [self::MIN => 1, self::MAX => 50000]
    ];

    /**
     * @var float[]|array
     */
    private $amount;

    /**
     * CurrencyGenerator constructor.
     */
    public function __construct()
    {
        $this->amount[self::USD] = rand() / 1000000;
        $this->amount[self::BTC] = rand() / 100000000;
        $this->amount[self::ETH] = rand() / 1000000000;
    }

    /**
     * @return float
     */
    public function amount(string $currency): float
    {
        return $this->amount[$currency] ?? 0;
    }
}