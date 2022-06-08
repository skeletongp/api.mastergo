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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->decimal('setted')->comment('Cantidad de prducto que se invierte');
            $table->decimal('getted')->comment('Cantidad de producto que se obtuvo')->default(0);
            $table->enum('status',['Creado','Iniciado','Completado']);
            $table->foreignId('proceso_id')->comment('Proceso vinculado')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->decimal('eficiency')->comment('Eficiencia de la producción')->default(0);
            $table->dateTime('start_at')->comment('Momento en que inicia el proceso');
            $table->dateTime('end_at')->comment('Momento en que termina el proceso')->nullable();
            $table->foreignId('user_id')->comment('Usuario responsable del proceso')->constrained();
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
        Schema::dropIfExists('productions');
    }
};
