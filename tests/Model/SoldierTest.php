<?php

namespace App\Tests\Model;

use App\Model\InfantryMan;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SoldierTest extends TestCase
{
    public function testKill()
    {
        $soldier = new InfantryMan();
        $enemy = array(new InfantryMan());

        try {
            $result = $soldier->kill($enemy);
            $this->assertContains('Target found!', $result);
            $this->assertContains('shots left.', $result);
        } catch (\Exception $e) {
            $this->fail("Exception triggered with this message: " . $e->getMessage());
        }
    }

    public function testAim()
    {
        $soldier = new InfantryMan();
        $enemy = array(new InfantryMan(), new InfantryMan(), new InfantryMan(), new InfantryMan(), new InfantryMan());
        $aim = self::getMethod("aim");
        $index = $aim->invokeArgs($soldier, array(&$enemy));

        $this->assertGreaterThanOrEqual(0, $index);
        $this->assertLessThanOrEqual(4, $index);
    }

    public function testShoot()
    {
        $soldier = new InfantryMan();
        $enemy = new InfantryMan();
        $shoot = self::getMethod("shoot");
        $result = $shoot->invokeArgs($soldier, array(&$enemy));
        if ($result == "Target missed.") {
            $this->assertEquals(100, $enemy->health);
        } else {
            $this->assertLessThan(100, $enemy->health);
        }
    }

    public function testRemoveTarget()
    {
        $soldier = new InfantryMan();
        $enemy = array(new InfantryMan());
        $target = 0;

        $remove = self::getMethod("removeTargetIfDead");
        $remove->invokeArgs($soldier, array($target, &$enemy));

        $this->assertEquals(1, count($enemy));

        $enemy[0]->health = 0;
        $remove->invokeArgs($soldier, array($target, &$enemy));

        $this->assertTrue(empty($enemy));
    }

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('App\Model\InfantryMan');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}
