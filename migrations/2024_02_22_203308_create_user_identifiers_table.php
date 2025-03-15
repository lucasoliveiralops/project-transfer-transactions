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
use App\Domain\Enum\IdentifiersType;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateUserIdentifiersTable extends Migration
{
    public function up(): void
    {
        Schema::create('user_identifiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('identifier')->unique();
            $table->enum('type', array_column(IdentifiersType::cases(), 'value'));

            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->index(['identifier', 'type'], 'index_identifier_and_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_identifiers');
    }
}
