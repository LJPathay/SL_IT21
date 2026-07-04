@extends('layouts.app')

@section('title', 'Manage Quiz Questions')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Quiz Questions</h2>
            <p class="text-slate-500 text-sm">Manage assessment questions for: <strong class="text-slate-800">{{ $quiz->title }}</strong></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quizzes') }}" class="px-4 py-2 border border-slate-350 text-slate-650 hover:bg-slate-50 rounded-xl font-semibold text-sm transition-all bg-white flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Quizzes
            </a>
            <button onclick="openQuestionModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Question
            </button>
        </div>
    </div>

    <!-- Questions list -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-base">Questions List ({{ $quiz->questions->count() }})</h3>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($quiz->questions->sortBy('order') as $index => $question)
            <div class="p-6 hover:bg-slate-50/30 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2 py-0.5 rounded-md">Q{{ $index + 1 }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 uppercase">
                                {{ str_replace('_', ' ', $question->question_type) }}
                            </span>
                            <span class="text-xs text-slate-450 font-medium">{{ $question->points }} Points</span>
                        </div>
                        <h4 class="font-semibold text-slate-900 text-base leading-relaxed">{{ $question->question_text }}</h4>
                        
                        @if($question->question_type === 'multiple_choice' && is_array($question->options))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3 pl-2">
                            @foreach($question->options as $key => $option)
                            <div class="flex items-center gap-2 text-sm text-slate-655 {{ $option === $question->correct_answer ? 'font-bold text-emerald-600 bg-emerald-50/50 border border-emerald-200/50 px-3 py-1.5 rounded-lg' : 'bg-slate-50/50 border border-slate-100 px-3 py-1.5 rounded-lg' }}">
                                <span class="uppercase text-xs font-semibold text-slate-400 border border-slate-200 w-5 h-5 rounded-full flex items-center justify-center bg-white">{{ $key }}</span>
                                <span>{{ $option }}</span>
                            </div>
                            @endforeach
                        </div>
                        @elseif($question->question_type === 'true_false')
                        <div class="flex items-center gap-4 text-sm mt-3 pl-2">
                            <span class="px-3 py-1.5 rounded-lg border {{ $question->correct_answer === 'true' ? 'font-bold text-emerald-600 bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100 text-slate-400' }}">True</span>
                            <span class="px-3 py-1.5 rounded-lg border {{ $question->correct_answer === 'false' ? 'font-bold text-emerald-600 bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-100 text-slate-400' }}">False</span>
                        </div>
                        @else
                        <div class="mt-3 pl-2">
                            <span class="text-xs font-semibold text-slate-450">Correct Answer:</span>
                            <span class="text-sm font-bold text-emerald-600 bg-emerald-50/50 border border-emerald-200/50 px-3 py-1.5 rounded-lg ml-2">{{ $question->correct_answer }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2 shrink-0">
                        <button onclick='editQuestion({{ json_encode($question) }})' class="p-2 border border-slate-200 rounded-lg hover:border-slate-300 text-slate-650 hover:text-slate-800 transition-colors bg-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <form method="POST" action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" onsubmit="return confirm('Are you sure you want to delete this question?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 border border-red-200 rounded-lg hover:border-red-300 text-red-500 hover:text-red-700 transition-colors bg-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-slate-500">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium text-slate-700">No questions added yet</p>
                <p class="text-sm mt-1 text-slate-450">Click the "Add Question" button to start adding quiz questions.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- QUESTION MODAL DIALOG -->
<div id="question-modal-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none" onclick="closeQuestionModal()">
    <div id="question-modal" class="max-w-xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden scale-90 transition-transform duration-300 ease-out" onclick="event.stopPropagation()">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <h3 id="modal-title" class="font-bold text-slate-900 text-lg">Add Quiz Question</h3>
            <button onclick="closeQuestionModal()" class="p-1 text-slate-400 hover:text-slate-650 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="question-form" method="POST" action="{{ route('admin.quizzes.questions.store', $quiz) }}" class="p-6 space-y-4">
            @csrf
            <div id="form-method-container"></div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Question Text</label>
                <textarea id="question_text" name="question_text" rows="3" required placeholder="Type the quiz question here..." class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Question Type</label>
                    <select id="question_type" name="question_type" onchange="handleTypeChange()" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white" required>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True / False</option>
                        <option value="short_answer">Short Answer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Points</label>
                    <input id="points" name="points" type="number" min="1" max="100" value="10" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                </div>
            </div>

            <!-- Dynamic Answer Options Section -->
            <div id="options-container" class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Answer Options</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-400">A</span>
                        <input id="opt-a" name="options[a]" type="text" placeholder="Option A" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm option-input">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-400">B</span>
                        <input id="opt-b" name="options[b]" type="text" placeholder="Option B" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm option-input">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-400">C</span>
                        <input id="opt-c" name="options[c]" type="text" placeholder="Option C" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm option-input">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-400">D</span>
                        <input id="opt-d" name="options[d]" type="text" placeholder="Option D" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm option-input">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Correct Answer</label>
                <div id="correct-answer-container">
                    <!-- Loaded dynamically via JavaScript depending on selected type -->
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Order Index (Optional)</label>
                <input id="order" name="order" type="number" min="0" value="0" class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 mt-4">
                <button type="button" onclick="closeQuestionModal()" class="px-4 py-2.5 border border-slate-250 rounded-xl text-slate-650 hover:bg-slate-50 text-sm font-semibold">Cancel</button>
                <button type="submit" id="submit-btn" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200">Save Question</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openQuestionModal() {
        document.getElementById('modal-title').textContent = 'Add Quiz Question';
        document.getElementById('question-form').action = "{{ route('admin.quizzes.questions.store', $quiz) }}";
        document.getElementById('form-method-container').innerHTML = '';
        document.getElementById('question_text').value = '';
        document.getElementById('question_type').value = 'multiple_choice';
        document.getElementById('points').value = '10';
        document.getElementById('order').value = '0';
        document.getElementById('opt-a').value = '';
        document.getElementById('opt-b').value = '';
        document.getElementById('opt-c').value = '';
        document.getElementById('opt-d').value = '';
        
        handleTypeChange();
        toggleModal(true);
    }

    function editQuestion(question) {
        document.getElementById('modal-title').textContent = 'Edit Quiz Question';
        document.getElementById('question-form').action = `/admin/quizzes/{{ $quiz->id }}/questions/${question.id}`;
        document.getElementById('form-method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('question_text').value = question.question_text;
        document.getElementById('question_type').value = question.question_type;
        document.getElementById('points').value = question.points;
        document.getElementById('order').value = question.order || 0;
        
        if (question.question_type === 'multiple_choice' && question.options) {
            document.getElementById('opt-a').value = question.options.a || '';
            document.getElementById('opt-b').value = question.options.b || '';
            document.getElementById('opt-c').value = question.options.c || '';
            document.getElementById('opt-d').value = question.options.d || '';
        }

        handleTypeChange(question.correct_answer);
        toggleModal(true);
    }

    function toggleModal(show) {
        const backdrop = document.getElementById('question-modal-backdrop');
        const modal = document.getElementById('question-modal');
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

    function closeQuestionModal() {
        toggleModal(false);
    }

    function handleTypeChange(selectedValue = '') {
        const type = document.getElementById('question_type').value;
        const optionsContainer = document.getElementById('options-container');
        const correctAnswerContainer = document.getElementById('correct-answer-container');

        if (type === 'multiple_choice') {
            optionsContainer.style.display = 'block';
            correctAnswerContainer.innerHTML = `
                <select name="correct_answer" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                    <option value="">Select the correct option value</option>
                    <option value="" id="correct-opt-a">Value of A</option>
                    <option value="" id="correct-opt-b">Value of B</option>
                    <option value="" id="correct-opt-c">Value of C</option>
                    <option value="" id="correct-opt-d">Value of D</option>
                </select>
            `;
            
            // Set up inputs to dynamically match option inputs
            const syncOptions = () => {
                const optA = document.getElementById('opt-a').value || 'Option A value';
                const optB = document.getElementById('opt-b').value || 'Option B value';
                const optC = document.getElementById('opt-c').value || 'Option C value';
                const optD = document.getElementById('opt-d').value || 'Option D value';
                
                const selectA = document.getElementById('correct-opt-a');
                const selectB = document.getElementById('correct-opt-b');
                const selectC = document.getElementById('correct-opt-c');
                const selectD = document.getElementById('correct-opt-d');

                if (selectA) { selectA.value = optA; selectA.textContent = 'A: ' + optA; }
                if (selectB) { selectB.value = optB; selectB.textContent = 'B: ' + optB; }
                if (selectC) { selectC.value = optC; selectC.textContent = 'C: ' + optC; }
                if (selectD) { selectD.value = optD; selectD.textContent = 'D: ' + optD; }
            };

            ['opt-a', 'opt-b', 'opt-c', 'opt-d'].forEach(id => {
                document.getElementById(id).addEventListener('input', syncOptions);
            });

            syncOptions();
            
            // If editing, set the correct dropdown selection
            if (selectedValue) {
                setTimeout(() => {
                    correctAnswerContainer.querySelector('select').value = selectedValue;
                }, 50);
            }
        } else if (type === 'true_false') {
            optionsContainer.style.display = 'none';
            correctAnswerContainer.innerHTML = `
                <select name="correct_answer" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                    <option value="true" ${selectedValue === 'true' ? 'selected' : ''}>True</option>
                    <option value="false" ${selectedValue === 'false' ? 'selected' : ''}>False</option>
                </select>
            `;
        } else {
            optionsContainer.style.display = 'none';
            correctAnswerContainer.innerHTML = `
                <input name="correct_answer" type="text" value="${selectedValue}" placeholder="Exact text answer required" required class="w-full px-4 py-2.5 border border-slate-250 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
            `;
        }
    }

    // Initialize layout on load
    document.addEventListener('DOMContentLoaded', () => {
        handleTypeChange();
    });
</script>
@endsection
