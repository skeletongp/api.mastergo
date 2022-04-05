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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name',75);
            $table->string('address',100)->comment('Dirección del negocio');
            $table->string('email',75)->unique()->comment('Debe ser propio del negocio, no de un usuario');
            $table->string('phone',15);
            $table->string('RNC')->nullable();
            $table->timestamp('expires_at')->comment('Para el control de mensualidad del sistema');
            $table->softDeletes();
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
        Schema::dropIfExists('stores');
    }
};
