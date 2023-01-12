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
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('oauth_token_id')->constrained();
            $table->string('remote_service');
            $table->string('remote_id');
            $table->string('name');
            $table->json('raw');
            $table->timestamps();

            $table->unique(['remote_service', 'remote_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendars');
        Schema::dropIfExists('calendar_events');
    }
};
