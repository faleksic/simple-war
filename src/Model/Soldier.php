<?php


namespace App\Model;

use Exception;

abstract class Soldier
{
    public $damage;

    public $accuracy;

    public $shotsPerRound;

    public $criticalShotChance;

    public $health = 100;

    /**
     * Used to call on solider so that he finds an enemy and shoots him
     *
     * @param $enemyArmy array of enemy soldiers
     * @return string description of the steps that happened while trying to kill
     * @throws Exception
     */
    public function kill(&$enemyArmy)
    {
        $killLog = "";
        $shotsLeft = $this->shotsPerRound;
        while ($shotsLeft > 0 && count($enemyArmy) > 0) {
            $targetIndex = $this->aim($enemyArmy);
            $killLog .= "Target found! ";
            $killLog .= $this->shoot($enemyArmy[$targetIndex]);
            $killLog .= $this->removeTargetIfDead($targetIndex, $enemyArmy);
            $killLog .= sprintf("%d shots left. <br>", --$shotsLeft);
        }

        return $killLog;
    }

    /**
     * Returns an index of random enemy from an array of enemy soldiers
     *
     * @param $enemyArmy array of enemy soldiers
     * @return int index of the enemy target
     */
    private function aim(&$enemyArmy)
    {
        return mt_rand(0, count($enemyArmy) - 1);
    }

    /**
     * @param Soldier $target enemy soldier on which this soldier is shooting
     * @return string description of the steps that happened while trying to shoot
     */
    private function shoot(Soldier &$target)
    {
        //check is shot accurate enough
        $chance = mt_rand(0, 100) / 100;
        if ($chance > $this->accuracy) {
            return "Target missed.";
        }
        //check will shot be critical
        $chance = mt_rand(0, 100) / 100;
        $hit = $chance <= $this->criticalShotChance ? $this->damage * 2 : $this->damage;

        $log = "";

        if ($chance <= $this->criticalShotChance) {
            $log .= "Critical hit! ";
        }

        $target->health -= $hit;
        return $log . sprintf(
            "Target hit with damage %d and current health of target is %d. ",
            $hit,
            $target->health < 0 ? 0 : $target->health
        );
    }

    /**
     * Used to delete soldier from array if his health is equal or lower than zero
     *
     * @param int targetIndex element position that needs to be removed from the array
     * @param $enemyArmy array of enemy soldiers
     * @return string description of the steps that happened while trying to remove target
     * @throws Exception used to cover an unexpected case when index is bigger than the array size
     */
    private function removeTargetIfDead($targetIndex, &$enemyArmy)
    {
        if ($targetIndex >= count($enemyArmy)) {
            throw new Exception("Index of the element that needs to be removed is bigger than the array");
        }

        if ($enemyArmy[$targetIndex]->health <= 0) {
            unset($enemyArmy[$targetIndex]);
            $enemyArmy = array_values($enemyArmy);
            return "Target removed. ";
        }
        return " ";
    }
}
