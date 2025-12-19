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
        Schema::create('activity', function (Blueprint $table) {
            $table->id();
            $table->integer('pro_id')->nullable();
            $table->string('stage', 200)->nullable();
            $table->text('sub')->nullable();
            $table->string('cat')->nullable();
            $table->json('qc')->nullable();
            $table->integer('qc_per')->nullable();
            $table->string('progress', 20)->nullable();
            $table->string('next_day')->nullable();
            $table->text('remarks')->nullable();
            $table->json('file')->nullable();
            $table->string('status')->nullable();
            $table->integer('c_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity');
    }
};
