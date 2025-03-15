<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Domain\Repository\RepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\WalletRepositoryInterface;
use App\Domain\Services\Authorizer\AuthorizerProviderInterface;
use App\Domain\Services\Notification\NotificationProviderInterface;
use App\Infraestructure\Authorizer\TransactionAuthorizerService;
use App\Infraestructure\Notification\NotificationService;
use App\Infraestructure\Repository\Repository;
use App\Infraestructure\Repository\TransactionRepository;
use App\Infraestructure\Repository\UserRepository;
use App\Infraestructure\Repository\WalletRepository;

/*
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
    TransactionRepositoryInterface::class => TransactionRepository::class,
    RepositoryInterface::class => Repository::class,
    AuthorizerProviderInterface::class => TransactionAuthorizerService::class,
    NotificationProviderInterface::class => NotificationService::class,
];
