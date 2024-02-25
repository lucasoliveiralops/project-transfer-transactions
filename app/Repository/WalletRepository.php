<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Wallet;
use App\Repository\Interface\WalletRepositoryInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;

class WalletRepository extends  Repository implements WalletRepositoryInterface
{
    public function __construct(private readonly Wallet $walletModel, Db $database)
    {
        parent::__construct($database);
    }

    public function getByUserId(string $userId): Wallet|Builder|null
    {
        return $this->walletModel->where('user_id', $userId)->first();
    }

    public function updateBalanceByUserId(string $userId, float $newBalance): bool
    {
        $wallet = $this->getByUserId($userId);

        if(empty($wallet)) {
            return false;
        }

        return $wallet->update([
            'current_balance' => $newBalance
        ]);
    }
}
