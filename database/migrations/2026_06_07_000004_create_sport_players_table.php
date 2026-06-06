<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sport_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sports_team_id')->constrained()->cascadeOnDelete();
            $table->string('player_name');
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sport_players');
    }
};
