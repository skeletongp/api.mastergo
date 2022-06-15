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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name')->comment('Identificador de la categoría');
            $table->string('description')->comment('Para entender su natureleza y facilitar las búsqueda');
            $table->foreignId('store_id')->comment('Tienda a la que pertenece la categoría')->constrained()->on('moso_master.stores');
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
        Schema::dropIfExists('categories');
    }
};
