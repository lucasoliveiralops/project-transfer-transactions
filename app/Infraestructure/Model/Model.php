<?php

declare(strict_types=1);

namespace App\Infraestructure\Model;

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
