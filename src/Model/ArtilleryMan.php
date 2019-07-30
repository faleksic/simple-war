<?php

namespace App\Model;

class ArtilleryMan extends Soldier
{
    public function __construct()
    {
        $this->accuracy = 0.08;
        $this->damage = 30;
        $this->shotsPerRound = 10;
        $this->criticalShotChance = 0.05;
    }
}