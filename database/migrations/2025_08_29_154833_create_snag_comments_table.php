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
   Schema::create('snag_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('snag_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->foreign('snag_id')->references('id')->on('entry_snag')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('employee')->onDelete('cascade');
            
            $table->index(['snag_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('snag_comments');
    }
};
