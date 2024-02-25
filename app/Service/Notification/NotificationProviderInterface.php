<?php

namespace App\Service\Notification;

interface NotificationProviderInterface
{
    public function send(): bool;
}
