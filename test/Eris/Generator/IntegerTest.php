<?php
namespace Eris\Generator;

class IntegerTest extends \PHPUnit_Framework_TestCase
{
    public function testPicksRandomlyAnInteger()
    {
        $generator = new Integer();
        for ($i = 0; $i < 100; $i++) {
            $this->assertTrue($generator->contains($generator()));
        }
    }

    public function testShrinksLinearlyTowardsZero()
    {
        $generator = new Integer(-10, 10);
        $value = $generator();
        for ($i = 0; $i < 20; $i++) {
            $newValue = $generator->shrink($value);
            $this->assertTrue(in_array(abs($value - $newValue), [0, 1]));
            $value = $newValue;
        }
        $this->assertEquals(0, $value);
    }

    public function testUniformity()
    {
        $generator = new Integer(-10, 10000);
        $values = [];
        for ($i = 0; $i < 50; $i++) {
            $values[] = $generator();
        }
        $this->assertGreaterThan(
            40,
            count(array_filter($values, function($n) { return $n > 0; })),
            "The positive numbers should be a vast majority given the interval [-10, 10000]"
        );
    }

    public function testCannotShrinkStopsToZero()
    {
        $generator = new Integer($lowerLimit = 0, $upperLimit = 0);
        $lastValue = $generator();
        $this->assertEquals(0, $generator->shrink($lastValue));
    }
}
