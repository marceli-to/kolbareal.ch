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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->json('apartment_sizes');   // e.g. ["2.5", "3.5"]
            $table->string('first_name');
            $table->string('last_name');
            $table->string('street');
            $table->string('zip_city');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->timestamp('exported_at')->nullable(); // set when included in weekly export
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
