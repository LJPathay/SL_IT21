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
        Schema::create('security_detections', function (Blueprint $table) {
            $table->id();
            $table->string('detection_type'); // phishing, social_engineering, password, malware, online_activity
            $table->string('severity'); // low, medium, high, critical
            $table->string('title');
            $table->text('description');
            $table->text('details')->nullable();
            $table->string('source')->nullable(); // email, url, file, user, etc.
            $table->string('source_id')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('mitigation_steps')->nullable();
            $table->timestamps();
            $table->index(['detection_type', 'is_resolved']);
            $table->index('severity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_detections');
    }
};
