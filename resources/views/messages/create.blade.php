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
                <label for="recipient_search" class="block text-sm font-semibold text-slate-700 mb-1.5">To</label>
                <div class="relative">
                    <input type="text" id="recipient_search" 
                        placeholder="Search by name or email..."
                        autocomplete="off"
                        class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white @error('recipient_id') border-red-500 @enderror">
                    <input type="hidden" name="recipient_id" id="recipient_id" value="{{ old('recipient_id') }}">
                    
                    {{-- Search Results Dropdown --}}
                    <div id="recipient_results" class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-64 overflow-y-auto hidden">
                        <div class="p-2 text-sm text-slate-500">Start typing to search...</div>
                    </div>
                </div>
                @error('recipient_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                
                {{-- Selected Recipient Display --}}
                <div id="selected_recipient" class="mt-2 hidden">
                    <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <span id="selected_name" class="text-sm font-medium text-blue-900"></span>
                        <button type="button" id="clear_recipient" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
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

@push('scripts')
<script>
    const recipients = @json($recipients);
    const searchInput = document.getElementById('recipient_search');
    const resultsDiv = document.getElementById('recipient_results');
    const recipientIdInput = document.getElementById('recipient_id');
    const selectedRecipientDiv = document.getElementById('selected_recipient');
    const selectedNameSpan = document.getElementById('selected_name');
    const clearRecipientBtn = document.getElementById('clear_recipient');

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 1) {
            resultsDiv.classList.add('hidden');
            return;
        }

        const filtered = recipients.filter(r => 
            r.name.toLowerCase().includes(query) || 
            r.email.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
            resultsDiv.innerHTML = '<div class="p-2 text-sm text-slate-500">No recipients found</div>';
        } else {
            resultsDiv.innerHTML = filtered.map(r => `
                <div class="recipient-option px-3 py-2 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0" 
                     data-id="${r.id}" 
                     data-name="${r.name}"
                     data-role="${r.role}"
                     data-department="${r.department || ''}">
                    <div class="font-medium text-slate-900">${r.name}</div>
                    <div class="text-xs text-slate-500">${r.email}</div>
                    <div class="text-xs text-slate-400 mt-0.5">
                        <span class="inline-block px-1.5 py-0.5 bg-slate-100 rounded text-slate-600">${r.role.charAt(0).toUpperCase() + r.role.slice(1)}</span>
                        ${r.department ? `<span class="ml-1">${r.department}</span>` : ''}
                    </div>
                </div>
            `).join('');

            resultsDiv.querySelectorAll('.recipient-option').forEach(option => {
                option.addEventListener('click', function() {
                    selectRecipient(this.dataset.id, this.dataset.name);
                });
            });
        }

        resultsDiv.classList.remove('hidden');
    });

    function selectRecipient(id, name) {
        recipientIdInput.value = id;
        selectedNameSpan.textContent = name;
        selectedRecipientDiv.classList.remove('hidden');
        searchInput.value = '';
        resultsDiv.classList.add('hidden');
    }

    clearRecipientBtn.addEventListener('click', function() {
        recipientIdInput.value = '';
        selectedRecipientDiv.classList.add('hidden');
        searchInput.focus();
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.classList.add('hidden');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            resultsDiv.classList.add('hidden');
        }
    });
</script>
@endpush
