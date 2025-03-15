<?php

declare(strict_types=1);

namespace App\Infraestructure\Repository;

use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepositoryInterface;
use App\Infraestructure\Model\Wallet as WalletModel;
use Hyperf\DbConnection\Db;

class WalletRepository extends Repository implements WalletRepositoryInterface
{
    public function __construct(private readonly WalletModel $walletModel, Db $database)
    {
        parent::__construct($database);
    }

    public function getLockedForUpdateByUserId(string $userId): ?Wallet
    {
        $walletModel = $this->walletModel::lockForUpdate()->where('user_id', $userId)->first();

        if (! $walletModel) {
            return null;
        }

        return Wallet::fromArray($walletModel->toArray());
    }

    public function updateBalance(Wallet $wallet): bool
    {
        $walletModel = new WalletModel($wallet->toArray());
        $walletModel->exists = true;

        return $walletModel->update(['current_balance' => $wallet->getCurrentBalance()]);
    }
}
