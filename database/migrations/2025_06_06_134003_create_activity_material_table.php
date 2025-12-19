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
        Schema::create('activity_material', function (Blueprint $table) {
            $table->id();
            $table->integer('act_id')->nullable();
            $table->text('category')->nullable();
            $table->text('unit')->nullable();
            $table->integer('qty')->nullable();
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
        Schema::dropIfExists('activity_material');
    }
};
