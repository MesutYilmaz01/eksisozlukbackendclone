<?php

use App\Models\User;
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
        Schema::create('user_follow_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_user_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignId('followed_user_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('user_follow_user');
    }
};
