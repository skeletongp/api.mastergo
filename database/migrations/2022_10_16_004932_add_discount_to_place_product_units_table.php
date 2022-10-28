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
        Schema::table('product_place_units', function (Blueprint $table) {
            if (!Schema::hasColumn('place_product_units', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('price_special');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_place_units', function (Blueprint $table) {
            $table->dropifExists('discount');
        });
    }
};
