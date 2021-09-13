<?php

namespace App\DTO;

use DateTime;
use stdClass;

class DealUnit
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var float
     */
    public $amountUsd;

    /**
     * @var float
     */
    public $amountBtc;

    /**
     * @var float
     */
    public $amountEth;

    /**
     * @var string
     */
    public $purpose;

    /**
     * @var DateTime
     */
    public $createdAt;

    /**
     * @var DateTime
     */
    public $updatedAt;

    public function __construct(stdClass $deal)
    {
        $this->id = $deal->id;
        $this->userId = $deal->userId;
        $this->amountUsd = $deal->amountUsd;
        $this->amountBtc = $deal->amountBtc;
        $this->amountEth = $deal->amountEth;
        $this->purpose = $deal->purpose;
        $this->createdAt = $deal->createdAt;
        $this->updatedAt = $deal->updatedAt;
    }
}