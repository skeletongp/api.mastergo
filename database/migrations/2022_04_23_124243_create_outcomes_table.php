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
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 14,4)->comment('Cantidad gastada');
            $table->string('concepto')->comment('Detalle de este gasto');
            $table->string('ref')->comment('Referencia del gasto para asiento');
            $table->string('ncf')->nullable()->comment('Comprobante que sustenta el gasto');
            $table->morphs('outcomeable');
            $table->foreignId('user_id')->comment('Usuario que realiza el gasto')->constrained()->on('moso_master.users');
            $table->foreignId('store_id')->comment('Tienda a la que pertenece')->constrained()->on('moso_master.stores');
            $table->foreignId('place_id')->comment('Sucursal a la que pertenece')->constrained();
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
        Schema::dropIfExists('outcomes');
    }
};
