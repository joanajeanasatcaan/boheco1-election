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
        Schema::create('ECRM_Voters', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_voted')->default(false);
            $table->foreignId('nominee_id')->constrained('ECRM_Nominees')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ECRM_Voters');
    }
};
