<?php

namespace Test;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RabbitMQTest extends KernelTestCase
{
    /** @var  Producer */
    private $rabbitMQ;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->rabbitMQ = $kernel->getContainer()
            ->get('old_sound_rabbit_mq.test_producer');
    }

    public function testPushQueue()
    {
        $this->rabbitMQ->publish('test');
    }
}
