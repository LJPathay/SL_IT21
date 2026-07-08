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
        Schema::table('users', function (Blueprint $table) {
            // Add encrypted versions of sensitive fields if they don't exist
            if (!Schema::hasColumn('users', 'email_encrypted')) {
                $table->text('email_encrypted')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'name_encrypted')) {
                $table->text('name_encrypted')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'department_encrypted')) {
                $table->text('department_encrypted')->nullable()->after('department');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_encrypted', 'name_encrypted', 'department_encrypted']);
        });
    }
};
