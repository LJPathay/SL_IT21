<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Module;
use App\Models\QuizQuestion;
use App\Services\LoggingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQuizController extends Controller
{
    /**
     * Display a listing of quizzes.
     */
    public function index(Request $request)
    {
        $query = Quiz::with('module');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->module_id) {
            $query->where('module_id', $request->module_id);
        }

        $quizzes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.quizzes', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $modules = Module::where('is_active', true)->get();

        return view('admin.quizzes-create', [
            'modules' => $modules,
        ]);
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1|max:180',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Quiz title is required.',
            'module_id.required' => 'A module must be selected.',
            'passing_score.required' => 'Passing score is required.',
            'passing_score.min' => 'Passing score must be at least 0.',
            'passing_score.max' => 'Passing score cannot exceed 100.',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'],
            'course_id' => Module::find($validated['module_id'])->course_id ?? null,
            'passing_score' => $validated['passing_score'],
            'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'quiz_created',
            'Quiz',
            $quiz->id,
            $validated,
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes')->with('success', 'Quiz created successfully!');
    }

    /**
     * Display the specified quiz.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load('module', 'questions', 'results.user');

        return view('admin.quizzes-show', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * Show the form for editing the specified quiz.
     */
    public function edit(Quiz $quiz)
    {
        $modules = Module::where('is_active', true)->get();

        return view('admin.quizzes-edit', [
            'quiz' => $quiz,
            'modules' => $modules,
        ]);
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1|max:180',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'Quiz title is required.',
            'module_id.required' => 'A module must be selected.',
            'passing_score.required' => 'Passing score is required.',
            'passing_score.min' => 'Passing score must be at least 0.',
            'passing_score.max' => 'Passing score cannot exceed 100.',
        ]);

        $changes = array_diff_assoc($validated, $quiz->only(['title', 'description', 'module_id', 'passing_score', 'time_limit_minutes']));
        $changes['is_active'] = $request->has('is_active') !== $quiz->is_active;

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'],
            'course_id' => Module::find($validated['module_id'])->course_id ?? null,
            'passing_score' => $validated['passing_score'],
            'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'quiz_updated',
            'Quiz',
            $quiz->id,
            $changes,
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes')->with('success', 'Quiz updated successfully!');
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Request $request, Quiz $quiz)
    {
        $quiz->delete();

        LoggingService::logAudit(
            Auth::user(),
            'quiz_deleted',
            'Quiz',
            $quiz->id,
            ['title' => $quiz->title],
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes')->with('success', 'Quiz deleted successfully!');
    }

    /**
     * Bulk delete quizzes.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'quiz_ids' => 'required|array',
            'quiz_ids.*' => 'exists:quizzes,id',
        ]);

        $quizzes = Quiz::whereIn('id', $validated['quiz_ids'])->get();
        $count = $quizzes->count();

        foreach ($quizzes as $quiz) {
            LoggingService::logAudit(
                Auth::user(),
                'quiz_deleted',
                'Quiz',
                $quiz->id,
                ['title' => $quiz->title],
                $request,
                'success'
            );
        }

        Quiz::whereIn('id', $validated['quiz_ids'])->delete();

        return redirect()->route('admin.quizzes')->with('success', "{$count} quiz(zes) deleted successfully!");
    }

    /**
     * Show quiz questions management.
     */
    public function questions(Quiz $quiz)
    {
        $quiz->load('questions');

        return view('admin.quizzes-questions', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * Store a new question for the quiz.
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'correct_answer' => 'required|string',
            'options' => 'nullable|array',
            'points' => 'required|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ], [
            'question_text.required' => 'Question text is required.',
            'question_type.required' => 'Question type is required.',
            'correct_answer.required' => 'Correct answer is required.',
            'points.required' => 'Points value is required.',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'correct_answer' => $validated['correct_answer'],
            'options' => $validated['options'] ?? null,
            'points' => $validated['points'],
            'order' => $validated['order'] ?? 0,
        ]);

        LoggingService::logAudit(
            Auth::user(),
            'quiz_question_created',
            'QuizQuestion',
            null,
            ['quiz_id' => $quiz->id, 'question_text' => $validated['question_text']],
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', 'Question added successfully!');
    }

    /**
     * Update a quiz question.
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,short_answer',
            'correct_answer' => 'required|string',
            'options' => 'nullable|array',
            'points' => 'required|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        $question->update($validated);

        LoggingService::logAudit(
            Auth::user(),
            'quiz_question_updated',
            'QuizQuestion',
            $question->id,
            $validated,
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', 'Question updated successfully!');
    }

    /**
     * Delete a quiz question.
     */
    public function destroyQuestion(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $question->delete();

        LoggingService::logAudit(
            Auth::user(),
            'quiz_question_deleted',
            'QuizQuestion',
            $question->id,
            ['question_text' => $question->question_text],
            $request,
            'success'
        );

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', 'Question deleted successfully!');
    }
}
