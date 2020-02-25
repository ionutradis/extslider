<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModeAndIpsToExtsliders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extsliders', function (Blueprint $table) {
            $table->enum('mode', ['testing', 'production'])->default('testing');
            $table->text('ips')->default('');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extsliders', function (Blueprint $table) {
            $table->dropColumn('mode');
            $table->dropColumn('ips');
            //
        });
    }
}
