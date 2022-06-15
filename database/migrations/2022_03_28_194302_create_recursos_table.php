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
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name')->comment('Identificador en texto');
            $table->foreignId('store_id')->comment('Tienda a la que pertenece el recurso')->constrained()->on('moso_master.stores');
            $table->foreignId(('place_id'))->comment('Sucursal a la que pertenedce el material')->constrained();
            $table->foreignId('unit_id')->comment('Unidad con que se gestiona el recurso')->constrained();
            $table->foreignId('provider_id')->comment('Proveedor que suple dicho recurso')->constrained();
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
        Schema::dropIfExists('materials');
    }
};
