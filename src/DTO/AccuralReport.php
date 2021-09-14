<?php


namespace App\DTO;


class AccuralReport
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var int
     */
    private $children;

    /**
     * @var int
     */
    private $awards;

    private $amountUsd;

    private $amountBtc;

    private $amountEth;

    /**
     * AccuralReport constructor.
     * @param int $level
     * @param int $children
     * @param int $awards
     * @param $amountUsd
     * @param $amountBtc
     * @param $amountEth
     */
    public function __construct(int $level, int $children, int $awards,$amountUsd, $amountBtc, $amountEth)
    {
        $this->level = $level;
        $this->children = $children;
        $this->awards = $awards;
        $this->amountUsd = $amountUsd;
        $this->amountBtc = $amountBtc;
        $this->amountEth = $amountEth;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getChildren(): int
    {
        return $this->children;
    }

    /**
     * @return int
     */
    public function getAwards(): int
    {
        return $this->awards;
    }

    /**
     * @return mixed
     */
    public function getAmountUsd()
    {
        return $this->amountUsd;
    }

    /**
     * @return mixed
     */
    public function getAmountBtc()
    {
        return $this->amountBtc;
    }

    /**
     * @return mixed
     */
    public function getAmountEth()
    {
        return $this->amountEth;
    }

}