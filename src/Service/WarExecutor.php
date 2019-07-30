<?php

namespace App\Service;

use App\Model\ArtilleryMan;
use App\Model\InfantryMan;
use App\Model\Sniper;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WarExecutor
{

    private $armyOneSoldiers;
    private $armyTwoSoldiers;

    public function executeBattle($armyOne, $armyTwo)
    {
        //check are parameters positive integers
        if (!is_int($armyOne) || !is_int($armyTwo) || $armyOne <= 0 || $armyTwo <= 0) {
            throw new BadRequestHttpException('Incorrect parameters!');
        }

        $this->generateArmies($armyOne, $armyTwo);

        dump($this->armyOneSoldiers, $this->armyTwoSoldiers);
    }

    private function generateArmies($armyOne, $armyTwo)
    {
        $this->armyOneSoldiers = $this->generateArmy($armyOne);
        $this->armyTwoSoldiers = $this->generateArmy($armyTwo);
    }

    private function generateArmy($army)
    {
        if (!is_int($army) || $army <= 0) {
            throw new BadRequestHttpException('Incorrect parameters!');
        }
        $result = array();

        for ($i = 0; $i < $army; $i++) {
            $random = mt_rand(0, 100);
            switch (true) {
                case $random <= 10:
                    array_push($result, new Sniper());
                    break;
                case $random <= 30:
                    array_push($result, new ArtilleryMan());
                    break;
                default:
                    array_push($result, new InfantryMan());
                    break;
            }
        }

        return $result;
    }
}
