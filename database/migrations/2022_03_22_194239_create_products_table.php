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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name')->comment('Identificador en texto');
            $table->string('code')->comment('Código de inventario del producto');
            $table->text('description')->comment('Detalles opcionales del producto, para facilitar su búsqueda')->nullable();
            $table->foreignId('store_id')->comment('Tienda a la que pertenece el producto')->constrained()->on('moso_master.stores');
            $table->enum('type',['Producto','Servicio']);
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
        Schema::dropIfExists('products');
    }
};
