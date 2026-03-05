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
        Schema::create('ECRM_member_histories', function (Blueprint $table) {
            $table->id();
            $table->string('member_id'); 
            $table->foreign('member_id')->references('Id')->on('CRM_MemberConsumers')->onDelete('cascade');
            $table->enum('type', ['voted', 'verified', 'updated']);
            $table->string('description')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ECRM_member_histories');
    }
};
