<?php

namespace App\Consumer;

use App\Service\NotifyService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationConsumer implements ConsumerInterface
{
    /** @var NotifyService */
    protected $notifyService;

    /** @var ContainerInterface */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->notifyService = $this->container->get('notify_service');
    }

    public function execute(AMQPMessage $msg)
    {
        $template = unserialize($msg->getBody());

        $this->notifyService->notify($template);
    }
}
