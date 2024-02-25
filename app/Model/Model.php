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

namespace App\Model;

use Hyperf\Database\Model\Events\Creating;
use Hyperf\DbConnection\Model\Model as BaseModel;
use Hyperf\Stringable\Str;

abstract class Model extends BaseModel
{
    public bool $incrementing = false;

    public function creating(): void
    {
        $this->id = Str::uuid()->toString();
    }
}