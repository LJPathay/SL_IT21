@extends('layouts.app')

@section('title', $message->subject)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $message->subject }}</h2>
            <p class="text-slate-500 text-sm mt-0.5">
                @if($message->sender_id === auth()->id())
                    Sent to <strong class="text-slate-700">{{ $message->recipient->name ?? 'Unknown' }}</strong>
                @else
                    From <strong class="text-slate-700">{{ $message->sender->name ?? 'Unknown' }}</strong>
                @endif
                · {{ $message->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
        <a href="{{ route('messages.inbox') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Inbox
        </a>
    </div>

    {{-- Message card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Message meta bar --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                {{ strtoupper(substr($message->sender->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-slate-900 text-sm">{{ $message->sender->name ?? 'Unknown' }}</div>
                <div class="text-xs text-slate-400">{{ $message->sender->email ?? '' }} · {{ ucfirst($message->sender->role ?? '') }}</div>
            </div>
            <div class="text-xs text-slate-400 shrink-0">{{ $message->created_at->diffForHumans() }}</div>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <div class="prose prose-slate prose-sm max-w-none">
                {!! nl2br(e($message->body)) !!}
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 pb-6 flex items-center gap-3 flex-wrap">
            <a href="{{ route('messages.create') }}?recipient_id={{ $message->sender_id }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Reply
            </a>
            <form method="POST" action="{{ route('messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 bg-white text-red-600 hover:bg-red-50 text-sm font-semibold rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
