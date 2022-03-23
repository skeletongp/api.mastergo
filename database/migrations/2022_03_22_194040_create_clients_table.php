<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->comment('Primer nombre del cliente');
            $table->string('lastname',75)->comment('Apellidos');
            $table->string('email',100)->unique();
            $table->string('address',255)->comment('Dirección del cliente');
            $table->string('phone',25);
            $table->decimal('limit',11,2)->default(0)->comment('Crédito límite del cliente');
            $table->timestamp('email_verified_at')->nullable();
            $table->foreignId('store_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
};
