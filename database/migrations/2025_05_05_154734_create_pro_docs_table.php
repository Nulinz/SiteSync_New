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
        Schema::create('pro_docs', function (Blueprint $table) {
            $table->id();
            $table->integer('pro_id');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->text('desp')->nullable();
            $table->text('link')->nullable();
            $table->string('status')->nullable();
            $table->string('c_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pro_docs');
    }
};
