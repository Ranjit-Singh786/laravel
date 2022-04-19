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
        Schema::create('replyys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coment_id');
            $table->string('name');
            $table->integer('user_id')->nullable();
            $table->integer('post_id')->nullable();
            $table->text('reply');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replyys');
    }
};