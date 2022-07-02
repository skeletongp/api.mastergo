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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name',50)->comment('Primer nombre del cliente');
            $table->string('lastname',75)->comment('Apellidos');
            $table->string('fullname',135)->comment('Nombre completo');
            $table->string('phone',25)->comment('Teléfono')->nullable();
            $table->string('cellphone',25)->comment('Celular');
            $table->foreignId('client_id')->constrained();
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
        Schema::dropIfExists('contacts');
    }
};
