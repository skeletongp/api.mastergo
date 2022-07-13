<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::table('comprobantes', function (Blueprint $table) {
            $types=array_keys(Invoice::TYPES);
            foreach ($types as $ind=>$type) {
               $types[$ind]="'".$type."'";
            }
            $types=implode(",",$types);
          DB::statement("ALTER TABLE `comprobantes` CHANGE `type` `type` ENUM({$types}) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'COMPROBANTE DE CONSUMIDOR FINAL';");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comprobantes', function (Blueprint $table) {
            //
        });
    }
};
