<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TestConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        echo 'Test send message: ' . $msg->getBody() . PHP_EOL;
        echo 'Success' . PHP_EOL;
    }
}
