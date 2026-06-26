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
        Schema::create('phishing_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('phishing_campaigns')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('clicked_link')->default(false);
            $table->boolean('reported_phishing')->default(false);
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phishing_results');
    }
};
