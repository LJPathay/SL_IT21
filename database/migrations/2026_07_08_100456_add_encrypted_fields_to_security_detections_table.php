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
        Schema::table('security_detections', function (Blueprint $table) {
            // Add encrypted versions of sensitive fields
            if (!Schema::hasColumn('security_detections', 'source_encrypted')) {
                $table->text('source_encrypted')->nullable()->after('source');
            }
            if (!Schema::hasColumn('security_detections', 'source_id_encrypted')) {
                $table->text('source_id_encrypted')->nullable()->after('source_id');
            }
            if (!Schema::hasColumn('security_detections', 'details_encrypted')) {
                $table->text('details_encrypted')->nullable()->after('details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_detections', function (Blueprint $table) {
            $table->dropColumn(['source_encrypted', 'source_id_encrypted', 'details_encrypted']);
        });
    }
};
