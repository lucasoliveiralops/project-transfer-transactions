<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\Transaction\ForbiddenTransferForSeller;
use App\Exception\Transaction\InsufficientBalanceFromTransaction;
use App\Exception\Transaction\UnauthorizedTransaction;
use App\Exception\User\UserNotFound;
use App\Request\TransferRequest;
use App\Service\Transaction\TransferService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\ForbiddenHttpException;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpServer\Contract\ResponseInterface;

class TransactionController
{
    #[Inject]
    protected TransferService $transferService;

    #[Inject]
    protected ResponseInterface $response;

    public function transfer(TransferRequest $request): ?ResponseInterface
    {
        try {
            $this->transferService->transfer(
                $request->validated()['payer'],
                $request->validated()['payee'],
                $request->validated()['value'],
            );

            return null;
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (
                ForbiddenTransferForSeller|
                InsufficientBalanceFromTransaction|
                UnauthorizedTransaction $e
                ) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
