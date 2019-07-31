<?php


namespace App\Model;

abstract class Soldier
{
    public $damage;

    public $accuracy;

    public $shotsPerRound;

    public $criticalShotChance;

    public $health = 100;

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

    private function aim(&$enemyArmy)
    {
        $randomIndex = mt_rand(0, count($enemyArmy)-1);
        return $randomIndex;
    }

    private function shoot(Soldier &$target)
    {
        //check is shot accurate enough
        $chance = mt_rand(0, 100) / 100;
        if ($chance > $this->accuracy) {
            return "Target missed.";
        }
        //check will it be crit
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
            $target->health < 0? 0 : $target->health
        );
    }

    private function removeTargetIfDead($targetIndex, &$enemyArmy)
    {
        if ($enemyArmy[$targetIndex]->health <= 0) {
            unset($enemyArmy[$targetIndex]);
            $enemyArmy = array_values($enemyArmy);
            return "Target removed. ";
        }
        return " ";
    }
}
