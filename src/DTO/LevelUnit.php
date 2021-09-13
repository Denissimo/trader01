<?php

namespace App\DTO;

use App\Entity\User;

class LevelUnit
{
    /**
     * @var int
     */
    private $level;

    /**
     * @var int[]|array
     */
    private $childrenId = [];

    /**
     * @var Deal[]|array
     */
    private $deals;

    /**
     * @var LevelUnit[]|array
     */
    private $children;

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     * @return LevelUnit
     */
    public function setLevel(int $level): LevelUnit
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return Deal[]|array
     */
    public function getDeals(): array
    {
        return $this->deals;
    }

    /**
     * @param Deal[]|array $deals
     * @return LevelUnit
     */
    public function setDeals(array $deals): LevelUnit
    {
        $this->deals = $deals;
        return $this;
    }

    public function pushDeal(DealUnit $deal)
    {
        $this->deals[] = $deal;
    }

    /**
     * @return LevelUnit[]|array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param LevelUnit[]|array $children
     * @return LevelUnit
     */
    public function setChildren(array $children): LevelUnit
    {
        $this->children = $children;
        return $this;
    }

    public function pushChild(LevelUnit $levelUnit)
    {
        $this->children[] = $levelUnit;
    }

    /**
     * @return array|int[]
     */
    public function getChildrenId(): array
    {
        return $this->childrenId;
    }

    /**
     * @param array|int[] $childrenId
     * @return LevelUnit
     */
    public function setChildrenId(array $childrenId): LevelUnit
    {
        $this->childrenId = $childrenId;
        return $this;
    }

    public function pushChildId(LevelUnit $levelUnit)
    {
        $this->children[] = $levelUnit;
    }

}