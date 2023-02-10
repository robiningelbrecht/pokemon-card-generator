<?php

namespace App\Tests\Infrastructure\AMQP\Worker;

use App\Tests\PausedClock;
use PHPUnit\Framework\TestCase;

class WorkerTest extends TestCase
{
    private TestWorker $testWorker;

    public function testGetters(): void
    {
        $this->assertEquals(1000, $this->testWorker->getMaxIterations());
        $this->assertEquals(
            (new \DateTimeImmutable('2022-07-01'))->add(new \DateInterval('PT1H')),
            $this->testWorker->getMaxLifeTime()
        );
    }

    public function testMaxIterationsReached(): void
    {
        $this->assertFalse($this->testWorker->maxIterationsReached());
        for ($i = 0; $i < 998; ++$i) {
            $this->testWorker->maxIterationsReached();
        }
        $this->assertFalse($this->testWorker->maxIterationsReached());
        $this->assertTrue($this->testWorker->maxIterationsReached());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->testWorker = new TestWorker(PausedClock::on(new \DateTimeImmutable('2022-07-01')));
    }
}
