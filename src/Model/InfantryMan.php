<?php


namespace App\Model;

class InfantryMan extends Soldier
{
    public function __construct()
    {
        $this->accuracy = 0.3;
        $this->damage = 20;
        $this->shotsPerRound = 5;
        $this->criticalShotChance = 0.1;
    }
}