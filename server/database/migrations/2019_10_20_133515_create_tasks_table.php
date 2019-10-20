<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 128);
            $table->text('description')->nullable();
            $table->string('color', 6)->nullable();
            $table->unsignedInteger('order');
            $table->unsignedBigInteger('card_id');
            $table->timestamps();
            $table->unique(['card_id', 'order']);
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
