<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sports_teams', function (Blueprint $table) {
            $table->id();
            $table->string('team_name');
            $table->string('sport_type');
            $table->string('banner_image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sports_teams');
    }
};
