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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_user1_id');
            $table->unsignedBigInteger('contact_user2_id');

            $table->foreign('contact_user1_id')->references('id')->on('users');
            $table->foreign('contact_user2_id')->references('id')->on('users');

            $table->string('name', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
