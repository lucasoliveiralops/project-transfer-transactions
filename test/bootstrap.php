<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
! defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);

Swoole\Runtime::enableCoroutine(true);

require BASE_PATH . '/vendor/autoload.php';

Hyperf\Di\ClassLoader::init();

$container = require BASE_PATH . '/config/container.php';

$config = $container->get(\Hyperf\Contract\ConfigInterface::class);

$config->set(Hyperf\Contract\StdoutLoggerInterface::class, [
    'log_level' => [
        Psr\Log\LogLevel::ALERT,
        Psr\Log\LogLevel::CRITICAL,
        Psr\Log\LogLevel::EMERGENCY,
        Psr\Log\LogLevel::ERROR,
        Psr\Log\LogLevel::INFO,
        Psr\Log\LogLevel::NOTICE,
        Psr\Log\LogLevel::WARNING,
    ],
]);


$container->get(Hyperf\Contract\ApplicationInterface::class);
