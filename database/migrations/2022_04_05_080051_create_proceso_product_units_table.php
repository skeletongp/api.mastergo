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
        Schema::create('proceso_product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->comment('Proceso vinculado')->constrained();
            $table->foreignId('product_id')->comment('Producto utilizado en el proceso')->constrained();
            $table->foreignId('unit_id')->comment('Unidad del producto a generar')->constrained();
            $table->decimal('due')->comment('Cantidad de prducto que se espera');
            $table->decimal('obtained')->comment('Cantidad de producto que se obtuvo')->default(0);
            $table->decimal('eficiency')->comment('Eficiencia de la producción')->default(0);
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
        Schema::dropIfExists('proceso_product_units');
    }
};
