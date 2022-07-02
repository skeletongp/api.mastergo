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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
            $table->foreignId('user_id')->constrained()->on('moso_master.users');
            $table->foreignId('place_id')->constrained()->on('places');
            $table->foreignId('bank_id')->constrained()->on('banks');
            $table->morphs('chequeable');
            $table->decimal('amount',14,4)->comment('Monto del cheque');
            $table->string('concept',85)->comment('Concepto del cheque');
            $table->string('reference',25)->comment('Número del cheque');
            $table->date('date')->comment('Fecha de emisión del cheque');
            $table->enum('status',['Pendiente','Pago','Cancelado'])->default('Pendiente')->comment('Estado del cheque');
            $table->enum('type',['Emitido','Recibido'])->default('Emitido')->comment('Tipo de cheque');
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
        Schema::dropIfExists('cheques');
    }
};
