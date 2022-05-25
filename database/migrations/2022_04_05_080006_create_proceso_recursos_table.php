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
        Schema::create('proceso_recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->comment('Proceso vinculado')->constrained();
            $table->foreignId('recurso_id')->comment('Recurso utilizado en el proceso')->constrained();
            $table->decimal('cant')->comment('Cantidad del recurso que se utiliza');
            $table->decimal('stock')->comment('Cantidad del recurso que resta');
            $table->enum('status',['open','close']);
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
        Schema::dropIfExists('proceso_recursos');
    }
};
