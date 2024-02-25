<?php

declare(strict_types=1);

namespace App\Service\Transaction;

use App\Enum\TransactionOperation;
use App\Enum\UserType;
use App\Event\TransferCompleted;
use App\Exception\Transaction\ErrorToProcessTransaction;
use App\Exception\Transaction\ForbiddenTransferForSeller;
use App\Exception\Transaction\InsufficientBalanceForTransaction;
use App\Exception\Transaction\UnauthorizedTransaction;
use App\Model\Transaction;
use App\Model\User;
use App\Repository\Interface\RepositoryInterface;
use App\Repository\Interface\TransactionRepositoryInterface;
use App\Service\Transaction\Authorizer\AuthorizerProviderInterface;
use App\Service\User\UserService;
use App\Service\Wallet\WalletService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepositoryInterface $repository,
    )
    {}

    public function store(string $walletId, float $amount, TransactionOperation $operation): Transaction
    {
        return $this->repository->store($walletId, $amount, $operation);
    }
}
