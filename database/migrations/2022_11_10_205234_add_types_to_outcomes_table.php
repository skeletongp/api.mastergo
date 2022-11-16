<?php

use App\Models\Outcome;
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
        Schema::table('outcomes', function (Blueprint $table) {
            $table->enum('type', array_keys(Outcome::TYPES))->default('2')->after('ncf');
            $table->decimal('itbis', 10, 2)->default(0)->after('type');
            $table->decimal('selectivo', 10, 2)->default(0)->after('itbis');
            $table->decimal('propina', 10, 2)->default(0)->after('selectivo');
            $table->decimal('other', 10, 2)->default(0)->after('propina');
            $table->decimal('retenido', 10, 2)->default(0)->after('other');
            $table->decimal('products', 10, 2)->default(0)->after('retenido');
            $table->decimal('services', 10, 2)->default(0)->after('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outcomes', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
