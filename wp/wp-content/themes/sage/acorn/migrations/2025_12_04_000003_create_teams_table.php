<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('competition_id', 36)->nullable();
            $table->string('country_id', 36)->nullable();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->timestamps();

            // Optional: add foreign keys if your MySQL setup uses InnoDB
            // $table->foreign('competition_id')->references('id')->on('competitions');
            // $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
