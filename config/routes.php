<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Controller\TransactionController;

Router::addGroup('/transactions', function (){
    Router::post('/transfer', [TransactionController::class, 'transfer']);
});
