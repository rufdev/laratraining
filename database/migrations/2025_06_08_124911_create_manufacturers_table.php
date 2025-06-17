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
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name')->unique(); // Manufacturer name (e.g., Dell, HP, Cisco)
            $table->string('url')->nullable(); // Optional: Manufacturer's website URL
            $table->string('support_url')->nullable(); // Optional: Support URL
            $table->string('support_phone')->nullable(); // Optional: Support phone number
            $table->string('support_email')->nullable(); // Optional: Support email address
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns for timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
