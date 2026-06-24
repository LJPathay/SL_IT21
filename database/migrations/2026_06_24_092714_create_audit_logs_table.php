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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('action'); // e.g., 'created', 'updated', 'deleted', 'viewed'
            $table->string('model'); // e.g., 'User', 'Module', 'Quiz'
            $table->unsignedBigInteger('model_id')->nullable();
            $table->longText('changes')->nullable(); // JSON of before/after changes
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('success'); // success, failed
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('action');
            $table->index('model');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
