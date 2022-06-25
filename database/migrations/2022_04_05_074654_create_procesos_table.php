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
            $table->string('code')->commet('Nombre identificador del proceso');       
            $table->foreignId('place_id')->comment('Usuario responsable del proceso')->constrained();
            $table->foreignId('unit_id')->comment('Unidad que se trabaja en el proceso')->constrained();
            $table->foreignId('user_id')->comment('Unidad que se trabaja en el proceso')->constrained()->on('moso_master.users');
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
