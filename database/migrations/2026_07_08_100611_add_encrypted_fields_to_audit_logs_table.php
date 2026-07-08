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
        Schema::table('audit_logs', function (Blueprint $table) {
            // Add encrypted versions of sensitive fields
            if (!Schema::hasColumn('audit_logs', 'ip_address_encrypted')) {
                $table->text('ip_address_encrypted')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('audit_logs', 'user_agent_encrypted')) {
                $table->text('user_agent_encrypted')->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('audit_logs', 'changes_encrypted')) {
                $table->text('changes_encrypted')->nullable()->after('changes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_address_encrypted', 'user_agent_encrypted', 'changes_encrypted']);
        });
    }
};
