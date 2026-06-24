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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('event_type'); // e.g., 'login_attempt', 'failed_login', 'unauthorized_access', 'suspicious_activity'
            $table->string('severity')->default('info'); // info, warning, critical
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('endpoint')->nullable(); // The URL/endpoint accessed
            $table->string('method')->default('GET'); // HTTP method
            $table->integer('response_code')->nullable();
            $table->longText('details')->nullable(); // JSON of additional details
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance and security analysis
            $table->index('user_id');
            $table->index('event_type');
            $table->index('severity');
            $table->index('ip_address');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
