<?php

namespace App\Listener;

use App\Event\TransferCompleted;
use App\Job\AsyncSendNotifications;
use App\Service\Notification\NotificationProviderInterface;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;

class TransferCompletedListener implements ListenerInterface
{
    public function __construct(
        private readonly NotificationProviderInterface $notificationProvider,
        private readonly ContainerInterface $container
    )
    {
    }

    public function listen(): array
    {
        return [
            TransferCompleted::class,
        ];
    }

    public function process(object $event): void
    {
        $driver = (new DriverFactory($this->container))->get('default');

        $driver->push(new AsyncSendNotifications($this->notificationProvider, $event->payer));
        $driver->push(new AsyncSendNotifications($this->notificationProvider, $event->payee));
    }
}