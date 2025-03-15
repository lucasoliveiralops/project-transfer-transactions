<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Exception\UserNotFound;
use App\Domain\Services\Transaction\Exception\ForbiddenTransferForSeller;
use App\Domain\Services\Transaction\Exception\UnauthorizedTransaction;
use App\Domain\Services\Transaction\TransferService;
use App\Infraestructure\Repository\UserRepository;

class TransferUseCase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TransferService $transferService,
    ) {
    }

    /**
     * @throws ForbiddenTransferForSeller
     * @throws UserNotFound
     * @throws UnauthorizedTransaction
     */
    public function execute(string $payerId, string $payeeId, float $amount): void
    {
        $payer = $this->userRepository->getById($payerId);

        if (! $payer) {
            throw new UserNotFound($payerId);
        }

        $payee = $this->userRepository->getById($payeeId);

        if (! $payee) {
            throw new UserNotFound($payeeId);
        }

        $this->transferService->transfer($payer, $payee, $amount);
    }
}
