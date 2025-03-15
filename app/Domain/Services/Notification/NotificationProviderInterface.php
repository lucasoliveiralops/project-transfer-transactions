<?php

declare(strict_types=1);

namespace App\Domain\Services\Notification;

interface NotificationProviderInterface
{
    public function send(): bool;
}
