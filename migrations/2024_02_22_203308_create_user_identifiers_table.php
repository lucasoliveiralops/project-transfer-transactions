<?php

use App\Enum\IdentifiersType;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

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
