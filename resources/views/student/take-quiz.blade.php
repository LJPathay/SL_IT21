@extends('layouts.app')

@section('title', 'Take Quiz - ' . $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $quiz->title }}</h2>
            <p class="text-slate-500 text-sm mt-1">{{ $quiz->description ?? 'Please answer all questions to complete the assessment.' }}</p>
            <div class="flex gap-4 mt-3 text-xs font-semibold text-slate-500">
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Passing Score: {{ $quiz->passing_score }}%</span>
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> Total Questions: {{ $quiz->questions->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Questions Form -->
    <form method="POST" action="{{ route('student.quizzes.submit', $quiz->id) }}" class="space-y-6">
        @csrf
        
        @foreach($quiz->questions as $index => $question)
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-start gap-3">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-blue-50 text-blue-600 font-bold text-xs shrink-0 mt-0.5">{{ $index + 1 }}</span>
                    <h3 class="font-bold text-slate-900 text-base leading-relaxed">{{ $question->question_text }}</h3>
                </div>

                <div class="pl-9 space-y-2.5">
                    @if(!empty($question->options))
                        @foreach($question->options as $key => $option)
                            <label class="flex items-start gap-3 p-3.5 bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl cursor-pointer transition-all select-none">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                <span class="text-slate-700 text-sm font-medium leading-relaxed">{{ $option }}</span>
                            </label>
                        @endforeach
                    @else
                        <!-- Text Input fallback if options are empty -->
                        <input type="text" name="answers[{{ $question->id }}]" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter your answer">
                    @endif
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-between gap-4 pt-4">
            <a href="{{ route('student.quizzes') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold text-sm rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-200 transition-all">
                Submit Assessment
            </button>
        </div>
    </form>

</div>
@endsection
