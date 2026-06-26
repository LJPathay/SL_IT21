<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\UserEnrollment;
use App\Models\QuizResult;
use App\Models\Certificate;
use App\Models\PhishingCampaign;
use App\Models\PhishingResult;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get overall platform statistics.
     */
    public function getPlatformStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_courses' => Course::count(),
            'active_courses' => Course::where('is_active', true)->count(),
            'total_modules' => Module::count(),
            'active_modules' => Module::where('is_active', true)->count(),
            'total_enrollments' => UserEnrollment::count(),
            'completed_enrollments' => UserEnrollment::where('status', 'completed')->count(),
            'total_certificates' => Certificate::count(),
            'total_quiz_attempts' => QuizResult::count(),
        ];
    }

    /**
     * Get user enrollment statistics by role.
     */
    public function getUserStatsByRole(): array
    {
        return [
            'admin_count' => User::where('role', 'admin')->count(),
            'instructor_count' => User::where('role', 'instructor')->count(),
            'student_count' => User::where('role', 'student')->count(),
        ];
    }

    /**
     * Get course completion rates.
     */
    public function getCourseCompletionStats(): array
    {
        $courses = Course::withCount(['enrollments as total_enrollments'])
            ->withCount(['enrollments as completed_enrollments' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->get();

        return $courses->map(function ($course) {
            $completionRate = $course->total_enrollments > 0
                ? round(($course->completed_enrollments / $course->total_enrollments) * 100, 1)
                : 0;

            return [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'total_enrollments' => $course->total_enrollments,
                'completed_enrollments' => $course->completed_enrollments,
                'completion_rate' => $completionRate,
            ];
        })->toArray();
    }

    /**
     * Get module performance statistics.
     */
    public function getModulePerformanceStats(): array
    {
        $modules = Module::withCount(['enrollments as total_enrollments'])
            ->withCount(['enrollments as completed_enrollments' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->with(['quizzes' => function ($query) {
                $query->withCount('results');
            }])
            ->get();

        return $modules->map(function ($module) {
            $completionRate = $module->total_enrollments > 0
                ? round(($module->completed_enrollments / $module->total_enrollments) * 100, 1)
                : 0;

            $avgQuizScore = 0;
            if ($module->quizzes->isNotEmpty()) {
                $totalQuizResults = $module->quizzes->sum('results_count');
                if ($totalQuizResults > 0) {
                    $avgQuizScore = QuizResult::whereHas('quiz', function ($query) use ($module) {
                        $query->where('module_id', $module->id);
                    })->avg('score');
                }
            }

            return [
                'module_id' => $module->id,
                'module_title' => $module->title,
                'category' => $module->category,
                'difficulty' => $module->difficulty,
                'total_enrollments' => $module->total_enrollments,
                'completed_enrollments' => $module->completed_enrollments,
                'completion_rate' => $completionRate,
                'avg_quiz_score' => round($avgQuizScore, 1),
            ];
        })->toArray();
    }

    /**
     * Get quiz performance statistics.
     */
    public function getQuizPerformanceStats(): array
    {
        return QuizResult::select(
            'quiz_id',
            DB::raw('COUNT(*) as total_attempts'),
            DB::raw('AVG(score) as avg_score'),
            DB::raw('AVG(time_taken) as avg_time_taken'),
            DB::raw('MAX(score) as highest_score'),
            DB::raw('MIN(score) as lowest_score')
        )
            ->with('quiz')
            ->groupBy('quiz_id')
            ->get()
            ->map(function ($result) {
                return [
                    'quiz_id' => $result->quiz_id,
                    'quiz_title' => $result->quiz->title ?? 'Unknown',
                    'total_attempts' => $result->total_attempts,
                    'avg_score' => round($result->avg_score, 1),
                    'avg_time_taken' => round($result->avg_time_taken, 1),
                    'highest_score' => $result->highest_score,
                    'lowest_score' => $result->lowest_score,
                ];
            })
            ->toArray();
    }

    /**
     * Get user activity over time (last 30 days).
     */
    public function getUserActivityStats(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return [
            'new_users' => User::where('created_at', '>=', $startDate)->count(),
            'active_users' => User::where('last_login_at', '>=', $startDate)->count(),
            'new_enrollments' => UserEnrollment::where('enrolled_at', '>=', $startDate)->count(),
            'completed_modules' => UserEnrollment::where('completed_at', '>=', $startDate)->count(),
            'certificates_issued' => Certificate::where('issued_at', '>=', $startDate)->count(),
        ];
    }

    /**
     * Get phishing simulation statistics.
     */
    public function getPhishingStats(): array
    {
        $campaigns = PhishingCampaign::all();

        return [
            'total_campaigns' => $campaigns->count(),
            'active_campaigns' => $campaigns->where('status', 'active')->count(),
            'completed_campaigns' => $campaigns->where('status', 'completed')->count(),
            'total_emails_sent' => $campaigns->sum('total_sent'),
            'total_clicks' => $campaigns->sum('total_clicked'),
            'total_reports' => $campaigns->sum('total_reported'),
            'avg_click_rate' => $campaigns->sum('total_sent') > 0
                ? round(($campaigns->sum('total_clicked') / $campaigns->sum('total_sent')) * 100, 1)
                : 0,
            'avg_report_rate' => $campaigns->sum('total_sent') > 0
                ? round(($campaigns->sum('total_reported') / $campaigns->sum('total_sent')) * 100, 1)
                : 0,
        ];
    }

    /**
     * Get department-wise statistics.
     */
    public function getDepartmentStats(): array
    {
        return User::select('department', DB::raw('COUNT(*) as count'))
            ->whereNotNull('department')
            ->where('is_active', true)
            ->groupBy('department')
            ->orderByDesc('count')
            ->get()
            ->map(function ($stat) {
                return [
                    'department' => $stat->department,
                    'user_count' => $stat->count,
                ];
            })
            ->toArray();
    }

    /**
     * Get top performing users.
     */
    public function getTopPerformingUsers(int $limit = 10): array
    {
        return User::withCount(['certificates', 'enrollments as completed_enrollments' => function ($query) {
            $query->where('status', 'completed');
        }])
            ->where('role', 'student')
            ->where('is_active', true)
            ->orderByDesc('certificates_count')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'department' => $user->department,
                    'certificates_earned' => $user->certificates_count,
                    'modules_completed' => $user->completed_enrollments,
                ];
            })
            ->toArray();
    }

    /**
     * Get learning progress distribution.
     */
    public function getProgressDistribution(): array
    {
        return [
            'not_started' => UserEnrollment::where('progress_percentage', 0)->count(),
            'in_progress_25' => UserEnrollment::whereBetween('progress_percentage', [1, 25])->count(),
            'in_progress_50' => UserEnrollment::whereBetween('progress_percentage', [26, 50])->count(),
            'in_progress_75' => UserEnrollment::whereBetween('progress_percentage', [51, 75])->count(),
            'in_progress_99' => UserEnrollment::whereBetween('progress_percentage', [76, 99])->count(),
            'completed' => UserEnrollment::where('progress_percentage', 100)->count(),
        ];
    }
}
