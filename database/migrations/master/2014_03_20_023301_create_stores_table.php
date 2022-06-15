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
    protected $connection = 'moso_master';

    public function up()
    {
      Schema::connection($this->connection)->create('stores', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name',75);
            $table->string('address',100)->comment('Dirección del negocio');
            $table->string('lema',100)->comment('Frase corta debajo del nombre');
            $table->string('email',75)->unique()->comment('Debe ser propio del negocio, no de un usuario');
            $table->string('phone',25);
            $table->string('phone2',25)->nullable();
            $table->decimal('price', 14,4);
            $table->string('rnc')->nullable();
            $table->timestamp('expires_at')->comment('Para el control de mensualidad del sistema');
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
        Schema::connection($this->connection)->dropIfExists('moso_master.stores');
    }
};
