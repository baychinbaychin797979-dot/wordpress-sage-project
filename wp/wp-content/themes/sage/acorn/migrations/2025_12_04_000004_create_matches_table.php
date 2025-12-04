<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('competition_id', 36)->nullable();
            $table->string('home_team_id', 36)->nullable();
            $table->string('away_team_id', 36)->nullable();
            $table->integer('status_id')->default(1);
            $table->bigInteger('match_time')->nullable();
            $table->json('home_scores')->nullable();
            $table->json('away_scores')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
};
