<?php

namespace App\Service;

use App\Model\ArtilleryMan;
use App\Model\InfantryMan;
use App\Model\Sniper;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WarExecutor
{

    private $armies;

    public function executeBattle($armyOne, $armyTwo)
    {
        $battleLog = "";
        //check are parameters positive integers
        if (!is_int($armyOne) || !is_int($armyTwo) || $armyOne <= 0 || $armyTwo <= 0) {
            throw new BadRequestHttpException('Incorrect parameters!');
        }
        //generate armies
        $this->generateArmies($armyOne, $armyTwo);

        $battleLog .= sprintf(
            "Generated armies!<br>Army one:<br>Artillery men: %d, Infantry men: %d, Snipers: %d<br>"
            . "Army two:<br>Artillery men: %d, Infantry men: %d, Snipers: %d<br>",
            count(array_filter($this->armies[0], array($this, 'artilleryMen'))),
            count(array_filter($this->armies[0], array($this, 'infantryMen'))),
            count(array_filter($this->armies[0], array($this, 'snipers'))),
            count(array_filter($this->armies[1], array($this, 'artilleryMen'))),
            count(array_filter($this->armies[1], array($this, 'infantryMen'))),
            count(array_filter($this->armies[1], array($this, 'snipers')))
        );
        $battleLog .= $this->battle();

        return $battleLog;
    }

    private function generateArmies($armyOne, $armyTwo)
    {
        $this->armies = array();
        array_push($this->armies, $this->generateArmy($armyOne));
        array_push($this->armies, $this->generateArmy($armyTwo));
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

    private function battle()
    {
        $battleLog = "";
        $turn = mt_rand(0, 1);
        //battle as long as one side is not killed completely
        while (count($this->armies[0]) > 0 && count($this->armies[1]) > 0) {
            $battleLog .= $turn == 0 ? "Army one attacks!<br>" : "Army two attacks!<br>";
            $underAttackIndex = ($turn + 1) % 2;
            //Call kill command on every army soldier
            for ($i = 0; $i < count($this->armies[$turn]) && count($this->armies[$underAttackIndex]) > 0; $i++) {
                $battleLog .= sprintf("Soldier %d attacks.<br>", $i);
                $battleLog .= $this->armies[$turn][$i]->kill($this->armies[$underAttackIndex]);
            }
            $battleLog .= sprintf(
                "Attack finished! Army One: %d  Army Two: %d <br><br>",
                count($this->armies[0]),
                count($this->armies[1])
            );
            $turn = $underAttackIndex;
        }

        $battleLog .= count($this->armies[0]) > 0 ? "Army One WINS!!!" : "Army Two WINS!!!";

        return $battleLog;
    }

    private function artilleryMen($arr)
    {
        return $arr instanceof ArtilleryMan;
    }

    private function infantryMen($arr)
    {
        return $arr instanceof InfantryMan;
    }

    private function snipers($arr)
    {
        return $arr instanceof Sniper;
    }
}
