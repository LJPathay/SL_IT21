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
        Schema::table('messages', function (Blueprint $table) {
            // Add encrypted versions of sensitive fields
            if (!Schema::hasColumn('messages', 'subject_encrypted')) {
                $table->text('subject_encrypted')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('messages', 'body_encrypted')) {
                $table->text('body_encrypted')->nullable()->after('body');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['subject_encrypted', 'body_encrypted']);
        });
    }
};
