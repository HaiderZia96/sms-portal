<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstantMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instant_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('group_id');
            $table->string('u_id')->nullable();
            $table->text('message');
            $table->text('number');
            $table->string('msg_id')->nullable();
            $table->string('mask');
            $table->string('status')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('user_groups')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instant_messages');
    }
}
