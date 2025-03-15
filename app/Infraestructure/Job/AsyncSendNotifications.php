<?php

declare(strict_types=1);

namespace App\Infraestructure\Job;

use App\Domain\Entity\User;
use App\Domain\Services\Notification\NotificationProviderInterface;
use Hyperf\AsyncQueue\Job;

class AsyncSendNotifications extends Job
{
    public function __construct(
        private readonly NotificationProviderInterface $notificationProvider,
        private readonly User $user,
    ) {
    }

    public function handle(): void
    {
        $this->notificationProvider->send();
    }
}
