<?php

namespace App\Model;

class Sniper extends Soldier
{
    public function __construct()
    {
        $this->accuracy = 0.6;
        $this->damage = 50;
        $this->shotsPerRound = 1;
        $this->criticalShotChance = 0.4;
    }
}