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
        Schema::create('production_recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->comment('Producción vinculada')->constrained();
            $table->foreignId('recurso_id')->comment('Recurso utilizado en el proceso')->constrained();
            $table->foreignId('brand_id')->comment('Marca utilizada en el proceso')->constrained();
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
        Schema::dropIfExists('production_recursos');
    }
};
