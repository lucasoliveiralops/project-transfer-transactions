<?php

namespace App\Job;

use App\Model\User;
use App\Service\Notification\NotificationProviderInterface;
use Hyperf\AsyncQueue\Job;

class AsyncSendNotifications extends Job
{
    public function __construct(
        private readonly NotificationProviderInterface $notificationProvider,
        private readonly User $user,
    )
    {}

    public function handle(): void
    {
        $this->notificationProvider->send();
    }
}
