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
        Schema::create('phishing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('template_type'); // microsoft_login, dhl_shipping, netflix_renewal, payroll_update
            $table->string('target_audience'); // all, cs_dept, staff
            $table->string('status')->default('draft'); // draft, active, completed, cancelled
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('total_sent')->default(0);
            $table->integer('total_clicked')->default(0);
            $table->integer('total_reported')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phishing_campaigns');
    }
};
