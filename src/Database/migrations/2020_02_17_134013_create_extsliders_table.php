<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtslidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extsliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 25);
            $table->string('alias', 25);
            $table->text('html_content')->nullable();
            $table->text('scripts_content')->nullable();
            $table->text('css_content')->nullable();
            $table->integer('target_id')->unsigned()->nullable();
            $table->enum('status', [0,1])->default(0);
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
        Schema::dropIfExists('extsliders');
    }
}
