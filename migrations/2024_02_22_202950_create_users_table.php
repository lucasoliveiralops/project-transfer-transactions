<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use App\Enum\UserType;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('password');
            $table->string('email')->unique();
            $table->enum('type', array_column(UserType::cases(), 'value'));
            
            $table->datetimes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
