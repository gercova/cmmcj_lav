<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hospitalizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->foreign('history_id')->references('id')->on('historias');
            $table->unsignedInteger('bed_id');
            $table->foreign('bed_id')->references('id')->on('beds');
            $table->string('fc')->nullable();
            $table->string('t')->nullable();
            $table->string('so2')->nullable();
            $table->string('vital_functions')->nullable();
            $table->string('observations')->nullable();
            $table->string('others')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalizations');
    }
};
