<?php


namespace App\DTO;


class RewardsStat
{
    private $deals;

    private $users;

    private $amountUsd;

    private $amountBtc;

    private $amountEth;

    private $awardUsd;

    private $awardBtc;

    private $awardEth;

    /**
     * RewardsStat constructor.
     * @param $deals
     * @param $users
     * @param $amountUsd
     * @param $amountBtc
     * @param $amountEth
     * @param $awardUsd
     * @param $awardBtc
     * @param $awardEth
     */
    public function __construct($deals, $users, $amountUsd, $amountBtc, $amountEth, $awardUsd, $awardBtc, $awardEth)
    {
        $this->deals = $deals;
        $this->users = $users;
        $this->amountUsd = $amountUsd;
        $this->amountBtc = $amountBtc;
        $this->amountEth = $amountEth;
        $this->awardUsd = $awardUsd;
        $this->awardBtc = $awardBtc;
        $this->awardEth = $awardEth;
    }

    /**
     * @return mixed
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
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

    /**
     * @return mixed
     */
    public function getAwardUsd()
    {
        return $this->awardUsd;
    }

    /**
     * @return mixed
     */
    public function getAwardBtc()
    {
        return $this->awardBtc;
    }

    /**
     * @return mixed
     */
    public function getAwardEth()
    {
        return $this->awardEth;
    }
}