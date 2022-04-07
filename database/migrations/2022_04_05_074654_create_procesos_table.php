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
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->commet('Nombre identificador del proceso');
            $table->timestamp('start_at')->comment('Momento en que inicia el proceso');
            $table->timestamp('end_at')->comment('Momento en que termina el proceso')->nullable();
            $table->enum('status',['Sin procesar','Procesado','Descartado','En Proceso'])->comment('Cómo se encuentra el recurso');
            $table->foreignId('user_id')->comment('Usuario responsable del proceso')->constrained();
            $table->foreignId('place_id')->comment('Usuario responsable del proceso')->constrained();
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
        Schema::dropIfExists('procesos');
    }
};
