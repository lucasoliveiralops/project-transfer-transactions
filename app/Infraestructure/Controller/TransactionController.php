<?php

declare(strict_types=1);

namespace App\Infraestructure\Controller;

use App\Application\TransferUseCase;
use App\Domain\Exception\UserNotFound;
use App\Domain\Services\Transaction\Exception\ForbiddenTransferForSeller;
use App\Domain\Services\Transaction\Exception\UnauthorizedTransaction;
use App\Domain\Services\Wallet\Exception\InsufficientBalanceForTransaction;
use App\Infraestructure\Request\TransferRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Exception\ForbiddenHttpException;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Swoole\Http\Status;

class TransactionController
{
    #[Inject]
    protected TransferUseCase $transferUseCase;

    #[Inject]
    protected ResponseInterface $response;

    public function transfer(TransferRequest $request): Response
    {
        try {
            $this->transferUseCase->execute(
                $request->validated()['payer'],
                $request->validated()['payee'],
                $request->validated()['value'],
            );

            return $this->response->withStatus(Status::CREATED);
        } catch (UserNotFound $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (ForbiddenTransferForSeller|InsufficientBalanceForTransaction|UnauthorizedTransaction $e) {
            throw new ForbiddenHttpException($e->getMessage());
        }
    }
}
