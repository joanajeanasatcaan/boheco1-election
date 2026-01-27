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
        Schema::create('voters_lists', function (Blueprint $table) {
            $table->id();
            $table->string('profile')->nullable();
            $table->string('name');
            $table->string('id_number')->unique();
            $table->timestamp('date_time_voted')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters_lists');
    }
};
