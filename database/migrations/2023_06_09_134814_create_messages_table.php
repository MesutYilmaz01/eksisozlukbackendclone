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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignId('receiver_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignId('chat_id')->references('id')->on('chats')->constrained()->cascadeOnDelete();
            $table->boolean('delete_for_sender')->default(false);
            $table->boolean('delete_for_receiver')->default(false);
            $table->string('message');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
