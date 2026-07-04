@extends('layouts.app')

@section('title', 'Manage Quizzes')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Assessments & Quizzes</h2>
            <p class="text-slate-500 text-sm">Design assessments, create quiz questions, and manage testing criteria.</p>
        </div>
        <button onclick="toggleQuizModal(true)" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Quiz Question
        </button>
    </div>

    <!-- Stats row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">Q</div>
            <div>
                <div class="text-xs text-slate-400 font-bold uppercase">Total Quizzes</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $totalQuizzes }} Quizzes</div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center font-bold text-lg">?</div>
            <div>
                <div class="text-xs text-slate-400 font-bold uppercase">Total Questions</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">{{ $totalQuestions }} Questions</div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">%</div>
            <div>
                <div class="text-xs text-slate-400 font-bold uppercase">Avg Pass Score</div>
                <div class="text-xl font-bold text-slate-900 mt-0.5">80%</div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center font-bold text-lg">!</div>
            <div>
                <div class="text-xs text-slate-400 font-bold uppercase">Alerts</div>
                <div class="text-xl font-bold text-red-650 mt-0.5">None</div>
            </div>
        </div>
    </div>

    <!-- Main Section: Quizzes list -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 columns: Quiz Cards & Expandable questions -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="font-bold text-slate-800 text-lg">Quiz Directory</h3>

            @forelse($quizzes as $quiz)
            <div class="block bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:border-blue-300 transition-colors">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
                    <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="flex-1">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="px-2.5 py-0.5 bg-green-150/60 text-green-700 text-xs font-semibold rounded-md">{{ $quiz->module->category ?? 'General' }}</span>
                            <span class="text-xs text-slate-450">Pass requirement: {{ $quiz->passing_score ?? 80 }}%</span>
                        </div>
                        <h4 class="font-bold text-slate-900 text-lg hover:text-blue-600 transition-colors">{{ $quiz->title }}</h4>
                        <p class="text-slate-500 text-xs mt-0.5">Linked to "{{ $quiz->module->title ?? 'Unknown' }}" module.</p>
                    </a>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="text-sm font-semibold text-slate-655 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shrink-0 hover:bg-slate-50 transition-colors">
                            {{ $quiz->questions ? $quiz->questions->count() : 0 }} Questions
                        </a>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="p-2 border border-slate-200 bg-white rounded-lg hover:border-slate-350 hover:bg-slate-50 text-slate-650 hover:text-slate-900 transition-all" title="Edit Quiz Settings">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center text-slate-500">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="font-medium">No quizzes found</p>
                    <p class="text-sm">Create your first quiz to get started.</p>
                </div>
            </div>
            @endforelse

        </div>
        
        {{ $quizzes->links() }}

        </div>

        <!-- Right column: Quiz Settings & Rules -->
        <div class="space-y-6">
            <h3 class="font-bold text-slate-800 text-lg">Assessment Standards</h3>
            
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                <h4 class="font-bold text-slate-900 text-sm">Grading Configurations</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm py-1 border-b border-slate-100">
                        <span class="text-slate-500">Minimum passing grade</span>
                        <span class="font-bold text-slate-850">80%</span>
                    </div>
                    <div class="flex justify-between items-center text-sm py-1 border-b border-slate-100">
                        <span class="text-slate-500">Question distribution</span>
                        <span class="font-bold text-slate-850">Randomized</span>
                    </div>
                    <div class="flex justify-between items-center text-sm py-1 border-b border-slate-100">
                        <span class="text-slate-500">Allow multiple attempts</span>
                        <span class="font-bold text-green-600">Yes (Unlimited)</span>
                    </div>
                    <div class="flex justify-between items-center text-sm py-1">
                        <span class="text-slate-500">Show correct answers</span>
                        <span class="font-bold text-slate-850">After submission</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-xl p-5 shadow-md shadow-blue-100 space-y-3">
                <h4 class="font-bold text-base">Grading Insights</h4>
                <p class="text-xs text-blue-100 leading-relaxed">Students have completed 124 quiz attempts today. Average score stands at 78.4%. Standard deviation is 12%.</p>
                <a href="{{ url('/admin/reports') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-white/20 hover:bg-white/30 backdrop-blur-md px-3 py-1.5 rounded-lg transition-colors">
                    View grading reports
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

    </div>

    <!-- CREATE QUIZ QUESTION MODAL DIALOG -->
    <div id="quiz-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="toggleQuizModal(false)">
        <div id="quiz-modal" class="max-w-lg w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <h3 class="font-bold text-slate-900 text-lg">Add Assessment Question</h3>
                <button onclick="toggleQuizModal(false)" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.quizzes.store') }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Target Training Module</label>
                    <select name="module_id" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white" required>
                        @foreach($modules ?? [] as $module)
                        <option value="{{ $module->id }}">{{ $module->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Quiz Title</label>
                    <input name="title" type="text" placeholder="e.g. SQL Injection Assessment" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief description of this quiz..." class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Passing Score (%)</label>
                        <input name="passing_score" type="number" min="0" max="100" value="80" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Time Limit (Minutes, Optional)</label>
                        <input name="time_limit_minutes" type="number" min="1" max="180" placeholder="e.g. 30" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="quiz_active" class="rounded text-blue-600">
                    <label for="quiz_active" class="text-sm font-semibold text-slate-700">Publish immediately</label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                    <button type="button" onclick="toggleQuizModal(false)" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Create Quiz</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function toggleQuizModal(show) {
        const backdrop = document.getElementById('quiz-modal-backdrop');
        const modal = document.getElementById('quiz-modal');
        if (show) {
            backdrop.classList.remove('pointer-events-none', 'opacity-0');
            backdrop.classList.add('opacity-100');
            modal.classList.remove('scale-90');
            modal.classList.add('scale-100');
        } else {
            backdrop.classList.add('pointer-events-none', 'opacity-0');
            backdrop.classList.remove('opacity-100');
            modal.classList.remove('scale-100');
            modal.classList.add('scale-90');
        }
    }
</script>
@endsection
