@extends('layouts.app')

@section('title', 'Compose Message')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Compose Message</h2>
            <p class="text-slate-500 text-sm mt-0.5">Send a direct message to another user.</p>
        </div>
        <a href="{{ route('messages.inbox') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Inbox
        </a>
    </div>

    {{-- Compose Form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
            <h3 class="font-semibold text-slate-800">New Message</h3>
        </div>
        <form method="POST" action="{{ route('messages.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Recipient --}}
            <div>
                <label for="recipient_id" class="block text-sm font-semibold text-slate-700 mb-1.5">To</label>
                <select name="recipient_id" id="recipient_id" required
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('recipient_id') border-red-500 @enderror">
                    <option value="">Select recipient…</option>
                    @foreach($recipients as $recipient)
                    <option value="{{ $recipient->id }}" {{ old('recipient_id') == $recipient->id ? 'selected' : '' }}>
                        {{ $recipient->name }} ({{ ucfirst($recipient->role) }})
                    </option>
                    @endforeach
                </select>
                @error('recipient_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subject --}}
            <div>
                <label for="subject" class="block text-sm font-semibold text-slate-700 mb-1.5">Subject</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                    placeholder="What is this message about?"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('subject') border-red-500 @enderror">
                @error('subject')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Body --}}
            <div>
                <label for="body" class="block text-sm font-semibold text-slate-700 mb-1.5">Message</label>
                <textarea name="body" id="body" rows="8" required
                    placeholder="Write your message here…"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none @error('body') border-red-500 @enderror">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <a href="{{ route('messages.inbox') }}" class="px-5 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition-colors text-sm">
                    Discard
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send Message
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
