<?php

declare(strict_types=1);

use App\Repository\Repository;
use App\Repository\WalletRepository;
use App\Repository\UserRepository;
use App\Repository\Interface\RepositoryInterface;
use App\Repository\Interface\UserRepositoryInterface;
use App\Repository\Interface\WalletRepositoryInterface;
use App\Service\Transaction\Authorizer\TransactionAuthorizerService;
use App\Service\Transaction\Authorizer\AuthorizerProviderInterface;
use App\Service\Notification\NotificationService;
use App\Service\Notification\NotificationProviderInterface;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    UserRepositoryInterface::class => UserRepository::class,
    WalletRepositoryInterface::class => WalletRepository::class,
    AuthorizerProviderInterface::class => TransactionAuthorizerService::class,
    RepositoryInterface::class => Repository::class,
    NotificationProviderInterface::class => NotificationService::class
];
