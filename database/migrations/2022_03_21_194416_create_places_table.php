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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->comment('Identificador por código largo');
            $table->string('name', 100)->comment('Identificador por nombre');
            $table->string('phone',25)->comment('Teléfono de la sucursal');
            $table->foreignId('user_id')->comment('Usuario responsable de la sucursal')->nullable()->constrained();
            $table->foreignId('store_id')->comment('Relación con la tienda base')->nullable()->constrained();
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
        Schema::dropIfExists('places');
    }
};
