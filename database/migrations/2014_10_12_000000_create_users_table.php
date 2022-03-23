<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name',50)->comment('Primer nombre del usuario');
            $table->string('lastname',75)->comment('Apellidos');
            $table->string('email',100)->unique();
            $table->string('username',35)->unique()->comment('Utilizado para iniciar sesión');
            $table->string('password',255);
            $table->string('phone',25);
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('store_id')->constrained();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE users comment 'My comment'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
