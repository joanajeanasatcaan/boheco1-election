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
        Schema::create('ECRM_VoteLogs', function (Blueprint $table) {
        $table->id();
        $table->string('member_id');  
        $table->unsignedBigInteger('household_id');
        $table->string('ip_address')->nullable();
        $table->foreignId('nominee_id')->constrained('ECRM_Nominees')->cascadeOnDelete();
        $table->foreign('member_id')->references('Id')->on('CRM_MemberConsumers')->cascadeOnDelete();

        $table->timestamps();

        $table->unique(['nominee_id', 'household_id']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ECRM_VoteLogs');
    }
};
