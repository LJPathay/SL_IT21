@extends('layouts.app')

@section('title', 'Sent Messages')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Sent Messages</h2>
            <p class="text-slate-500 text-sm mt-0.5">Messages you have sent to others.</p>
        </div>
        <a href="{{ route('messages.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-md shadow-blue-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Compose
        </a>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit">
        <a href="{{ route('messages.inbox') }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-slate-500 hover:text-slate-800 transition-colors">Inbox</a>
        <a href="{{ route('messages.sent') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-white text-blue-600 shadow-sm">Sent</a>
    </div>

    {{-- Messages list --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @forelse($messages as $message)
        <div class="border-b border-slate-100 last:border-0">
            <a href="{{ route('messages.show', $message) }}" class="flex items-start gap-4 p-5 hover:bg-slate-50 transition-colors">
                {{-- Recipient Avatar --}}
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shrink-0 mt-0.5">
                    {{ strtoupper(substr($message->recipient->name ?? 'U', 0, 1)) }}
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <span class="font-semibold text-slate-900 text-sm truncate">To: {{ $message->recipient->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-slate-400 shrink-0">{{ $message->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm font-medium text-slate-700 truncate">{{ $message->subject }}</p>
                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ Str::limit($message->body, 90) }}</p>
                </div>

                {{-- Read status badge --}}
                <span class="shrink-0 mt-1 px-2 py-0.5 text-xs rounded-full font-medium {{ $message->is_read ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $message->is_read ? 'Read' : 'Unread' }}
                </span>
            </a>
        </div>
        @empty
        <div class="p-16 text-center">
            <svg class="w-14 h-14 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            <p class="font-semibold text-slate-600">No sent messages</p>
            <p class="text-slate-400 text-sm mt-1">Messages you send will appear here.</p>
            <a href="{{ route('messages.create') }}" class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                Send a message
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{ $messages->links() }}

</div>
@endsection
