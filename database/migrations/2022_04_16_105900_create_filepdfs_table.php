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
        Schema::create('filepdfs', function (Blueprint $table) {
            $table->id();
            $table->string('note')->comment('Nota o descripción opcional')->nullable();
            $table->string('path')->comment('Ruta del archivo en local o nube');
            $table->enum('size',['thermal','letter'])->comment('Tamaño de papel del pdf')->default('thermal');
            $table->morphs('fileable');
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
        Schema::dropIfExists('filepdfs');
    }
};
