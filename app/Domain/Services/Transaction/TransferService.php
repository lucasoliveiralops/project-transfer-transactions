<?php

declare(strict_types=1);

namespace App\Domain\Services\Transaction;

use App\Domain\Entity\User;
use App\Domain\Event\TransferCompleted;
use App\Domain\Repository\RepositoryInterface;
use App\Domain\Services\Authorizer\AuthorizerProviderInterface;
use App\Domain\Services\Transaction\Exception\ErrorToProcessTransaction;
use App\Domain\Services\Transaction\Exception\ForbiddenTransferForSeller;
use App\Domain\Services\Transaction\Exception\UnauthorizedTransaction;
use App\Domain\Services\Wallet\WalletService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class TransferService
{
    public function __construct(
        private readonly WalletService $walletService,
        private readonly AuthorizerProviderInterface $authorizationService,
        private readonly RepositoryInterface $repository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function transfer(User $payer, User $payee, float $amount): void
    {
        if ($payer->isSeller()) {
            throw new ForbiddenTransferForSeller();
        }

        if (! $this->authorizationService->authorize()) {
            throw new UnauthorizedTransaction();
        }

        $this->solveTransfer($payer, $payee, $amount);
    }

    private function solveTransfer(User $payer, User $payee, float $amount): void
    {
        try {
            $this->repository->beginTransaction();

            if (! $this->walletService->removeBalance($payer->getId(), $amount)) {
                throw new ErrorToProcessTransaction();
            }
            if (! $this->walletService->addBalance($payee->getId(), $amount)) {
                throw new ErrorToProcessTransaction();
            }

            $this->repository->commit();
            $this->eventDispatcher->dispatch(new TransferCompleted($payer, $payee, $amount));
        } catch (Throwable $e) {
            $this->repository->rollback();
            throw $e;
        }
    }
}
