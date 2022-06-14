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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->comment('Primer nombre del proveedor');
            $table->string('lastname',75)->comment('Apellidos');
            $table->string('fullname',75)->comment('Nombre y Apellidos');
            $table->string('email',100);
            $table->string('address',255)->comment('Dirección del cliente');
            $table->string('phone',25);
            $table->string('rnc')->nullable();
            $table->decimal('limit', 14,4)->default(0)->comment('Crédito límite del proveedor');
            $table->foreignId('store_id')->constrained();
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
        Schema::dropIfExists('providers');
    }
};
