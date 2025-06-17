<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     * Adds a 'role' column to the 'users' table to define user roles.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'inventory_manager', 'inventory_user'])
                  ->default('inventory_user') // Set a default role for new users
                  ->after('email'); // Place the column after the 'email' column, or wherever you prefer
        });
    }

    /**
     * Reverse the migrations.
     * Defines how to undo the changes made in the 'up' method (drop the 'role' column).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role'); // Remove the 'role' column
        });
    }
};
