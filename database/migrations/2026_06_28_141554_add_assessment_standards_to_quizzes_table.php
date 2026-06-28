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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('question_distribution')->default('sequential')->after('attempts_allowed');
            $table->string('show_correct_answers')->default('after_submission')->after('question_distribution');
            $table->boolean('shuffle_options')->default(false)->after('show_correct_answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['question_distribution', 'show_correct_answers', 'shuffle_options']);
        });
    }
};
