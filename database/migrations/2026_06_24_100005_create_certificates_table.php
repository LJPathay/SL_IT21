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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->foreignId('module_id')->nullable()->constrained('modules')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->text('title');
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('file_path')->nullable();
            $table->json('metadata')->nullable(); // Grade, completion percentage, etc
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('course_id');
            $table->index('module_id');
            $table->index('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
