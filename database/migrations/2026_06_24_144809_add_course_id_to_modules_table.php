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
        Schema::table('modules', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->after('required_roles');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
            $table->integer('lesson_count')->default(0)->after('order');
            $table->integer('enrollment_count')->default(0)->after('lesson_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['course_id', 'lesson_count', 'enrollment_count']);
        });
    }
};
