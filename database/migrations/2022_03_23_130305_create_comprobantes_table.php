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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_keys(Invoice::TYPES));
            $table->string('prefix');
            $table->string('number');
            $table->string('ncf');
            $table->string('period');
            $table->enum('status',['disponible','usado','anulado','reportado']);
            $table->foreignId('user_id')->nullable()->constrained()->on('moso_master.users');
            $table->foreignId('client_id')->nullable()->constrained();
            $table->foreignId('place_id')->nullable()->constrained();
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
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
        Schema::dropIfExists('comprobantes');
    }
};
