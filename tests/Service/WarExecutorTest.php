<?php

namespace App\Tests\Service;

use App\Model\Soldier;
use App\Service\WarExecutor;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class WarExecutorTest extends TestCase
{
    public function testGenerateArmy()
    {
        $executor = new WarExecutor();
        $generate = self::getMethod("generateArmy");
        $array = $generate->invokeArgs($executor, array(10));
        $this->assertEquals(10, count($array));

        for ($i = 0; $i < count($array); $i++) {
            $this->assertTrue($array[$i] instanceof Soldier);
        }
    }

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('App\Service\WarExecutor');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}