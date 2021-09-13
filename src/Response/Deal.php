<?php

namespace App\Response;

use \DateTime;
use \App\Entity\Deal as DealEntity;

class Deal
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

    public function __construct(DealEntity $deal)
    {
        $this->id = $deal->getId();
        $this->userId = $deal->getUser()->getId();
        $this->amountUsd = $deal->getAmountUsd();
        $this->amountBtc = $deal->getAmountBtc();
        $this->amountEth = $deal->getAmountEth();
        $this->purpose = $deal->getPurpose();
        $this->createdAt = $deal->getCreatedAt();
        $this->updatedAt = $deal->getUpdatedAt();
    }
}