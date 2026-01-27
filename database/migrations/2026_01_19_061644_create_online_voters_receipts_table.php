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
        Schema::create('online_voters_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('profile')->nullable();
            $table->string('name');
            $table->string('id_number')->unique();
            $table->timestamp('date_time_voted')->nullable();
            $table->string('remarks')->default('Voted Online');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_voters_receipts');
    }
};
