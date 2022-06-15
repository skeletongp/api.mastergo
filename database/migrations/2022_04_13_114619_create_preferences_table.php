<?php

use App\Models\Invoice;
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
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')->comment('Negocio al que pertenece la prefencia')->constrained();
            $table->enum('comprobante_type',Invoice::TYPES);
            $table->foreignId('unit_id')->constrained();
            $table->foreignId('tax_id')->constrained();
            $table->string('printer')->nullable();
            $table->integer('copy_print')->default(1);
            $table->string('printer_nif')->nullable();
            $table->string('printer_ver')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('preferences');
    }
};
