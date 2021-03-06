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
        Schema::create('recurrents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 14, 4);
            $table->enum('recurrency',['Semanal','Quincenal','Mensual','Anual']);
            $table->enum('status',['Pendiente','Pagado','Vencido']);
            $table->foreignId('place_id')->constrained();
            $table->foreignId('count_id')->constrained();
            $table->date('expires_at');
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
        Schema::dropIfExists('recurrents');
    }
};
