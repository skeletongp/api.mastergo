<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Scope;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_scopes', function (Blueprint $table) {
            $table->foreignIdFor(Scope::class,'scope_name')->references('name')->on('scopes')->constrained()->onDelete('cascade');
            $table->morphs('scopeable');
           
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
        Schema::dropIfExists('model_has_scopes');
    }
};
