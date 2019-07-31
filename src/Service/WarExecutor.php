<?php

namespace App\Service;

use App\Model\ArtilleryMan;
use App\Model\InfantryMan;
use App\Model\Sniper;
use App\Model\Soldier;
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WarExecutor
{
    //array for holding soldiers for both sides
    private $armies;

    /**
     * Main function that calls all other functions to execute war between two armies
     * First it generates armies, then it executes battle and returns a log of the battle
     *
     * @param $armyOne number that says how many soldiers are there in the army One
     * @param $armyTwo number that says how many soldiers are there in the army Two
     * @return string contains description of the whole battle
     * @throws Exception
     */
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

    /**
     * Instantiates the array that holds the armies, calls generateArmy function and
     * saves the result inside the army array
     *
     * @param $armyOne number that says how many soldiers are there in the army One
     * @param $armyTwo number that says how many soldiers are there in the army Two
     */
    private function generateArmies($armyOne, $armyTwo)
    {
        $this->armies = array();
        array_push($this->armies, $this->generateArmy($armyOne));
        array_push($this->armies, $this->generateArmy($armyTwo));
    }

    /**
     * Generates the given number of soldiers, chooses the type based on probability
     * Accumulates all the soldiers in the array and returns it as a result
     *
     * @param $army number of solders to generate
     * @return array soldiers array
     */
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


    /**
     * Executes the battle, exchanges turn between the armies and the given army shoots at the opposite one
     * The battle goes on until one of the armies array is empty, whoever has soldiers alive wins
     *
     * @return string contains the log of the battle
     * @throws Exception
     */
    private function battle()
    {
        $battleLog = "";
        $turn = mt_rand(0, 1);

        //battle as long as one side is not killed completely
        while (count($this->armies[0]) > 0 && count($this->armies[1]) > 0) {
            $battleLog .= $turn == 0 ? "Army one attacks!<br>" : "Army two attacks!<br>";
            $underAttackIndex = ($turn + 1) % 2;

            $attacker = &$this->armies[$turn];
            $underAttack = &$this->armies[$underAttackIndex];

            //Call kill command on every army soldier
            for ($i = 0; $i < count($attacker) && count($underAttack) > 0; $i++) {
                $battleLog .= sprintf("Soldier %d attacks.<br>", $i);
                /** @var Soldier[] $attacker */
                $battleLog .= $attacker[$i]->kill($underAttack);
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

    /**
     * Created to be used with array_filter function
     *
     * @param $elem array element that we are checking
     * @return bool true if object is an instance of ArtilleryMan otherwise false
     */
    private function artilleryMen($elem)
    {
        return $elem instanceof ArtilleryMan;
    }

    /**
     * Created to be used with array_filter function
     *
     * @param $elem array element that we are checking
     * @return bool true if object is an instance of InfantryMan otherwise false
     */
    private function infantryMen($elem)
    {
        return $elem instanceof InfantryMan;
    }

    /**
     * Created to be used with array_filter function
     *
     * @param $elem array element that we are checking
     * @return bool true if object is an instance of Sniper otherwise false
     */
    private function snipers($elem)
    {
        return $elem instanceof Sniper;
    }
}
