<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Wallet;
use App\Repository\Interface\WalletRepositoryInterface;
use Hyperf\Database\Query\Builder;
use Hyperf\DbConnection\Db;

class WalletRepository extends Repository implements WalletRepositoryInterface
{
    public function __construct(private readonly Wallet $walletModel, Db $database)
    {
        parent::__construct($database);
    }

    public function getLockedForUpdateByUserId(string $userId): null|Wallet|Builder
    {
        return $this->walletModel::lockForUpdate()->where('user_id', $userId)->first();
    }

    public function updateBalance(Wallet $wallet, float $newBalance): bool
    {
        return $wallet->update(['current_balance' => $newBalance]);
    }
}
