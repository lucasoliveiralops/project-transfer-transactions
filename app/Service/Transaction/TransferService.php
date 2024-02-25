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
use App\Model\User;
use App\Repository\Interface\RepositoryInterface;
use App\Service\Transaction\Authorizer\AuthorizerProviderInterface;
use App\Service\User\UserService;
use App\Service\Wallet\WalletService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class TransferService
{
    public function __construct(
        private readonly UserService                 $userService,
        private readonly WalletService               $walletService,
        private readonly AuthorizerProviderInterface $authorizationService,
        private readonly RepositoryInterface         $repository,
        private readonly EventDispatcherInterface    $eventDispatcher,
    )
    {}

    public function transfer(string $payerId, string $payeeId, float $amount): void
    {
        $payer = $this->userService->findOrFail($payerId);
        $payee = $this->userService->findOrFail($payeeId);

        if($payer->type != UserType::DefaultUser->value){
            throw new ForbiddenTransferForSeller();
        }
        if(! $this->walletService->hasAmount($payer, $amount)){
            throw new InsufficientBalanceForTransaction();
        }
        if(! $this->authorizationService->authorize()){
            throw new UnauthorizedTransaction();
        }

        $this->solveTransfer($payer, $payee, $amount);
    }

    private function solveTransfer(User $payer, User $payee, float $amount): void
    {
        try {
            $this->repository->beginTransaction();

            if(! $this->walletService->changeBalance($payer, $amount, TransactionOperation::Loss)){
                throw new ErrorToProcessTransaction();
            }
            if( ! $this->walletService->changeBalance($payee, $amount, TransactionOperation::Earned)){
                throw new ErrorToProcessTransaction();
            }

            $this->repository->commit();
            $this->eventDispatcher->dispatch(new TransferCompleted($payer, $payee, $amount));
        } catch (Throwable $e) {
            $this->repository->rollback();
            throw new ErrorToProcessTransaction($e);
        }
    }
}
