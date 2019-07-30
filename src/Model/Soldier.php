<?php


namespace App\Model;

abstract class Soldier
{
    public $damage;

    public $accuracy;

    public $shotsPerRound;

    public $criticalShotChance;

    public $health = 100;
}