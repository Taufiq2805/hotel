<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('report_sewas', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->default(0)->after('id_reservasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_sewas', function (Blueprint $table) {
            //
        });
    }
};
