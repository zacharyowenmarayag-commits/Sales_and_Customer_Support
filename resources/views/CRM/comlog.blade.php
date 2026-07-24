@extends('layouts.app')

@section('title', 'AmbatuGrow - Communication Logs')

@section('content')
<div class="space-y-6" id="mainContent">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Communication Logs</h1>
            <p class="text-sm text-gray-500 mt-1">Track your recent customer conversations and notes.</p>
        </div>
        <button type="button" id="openModalBtn" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Log
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Search</label>
        <div class="relative">
            <input
                type="text"
                name="q"
                value="{{ old('q', $q) }}"
                id="communicationSearch"
                placeholder="Search customer name..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
            />
            <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Channel</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Subject</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Handled By</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-6 py-4 text-gray-600">{{ $log->created_at->format('M j, Y') }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $log->customer }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->channel }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->subject }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->handled_by }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No communication logs yet. Click "Add Log" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $logs])
    </div>
</div>


<div id="modalOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200" id="modalCard">
        <h2 class="text-base font-bold text-gray-900 mb-5">Add Communication Log</h2>

        <form action="{{ route('crm.comlog.store') }}" method="POST" id="logForm">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="customer" class="block text-sm font-semibold text-gray-700 mb-1.5">Customer</label>
                    <select name="customer" id="customer"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('customer') border-red-400 @enderror"
                        required>
                        <option value="" disabled {{ old('customer') ? '' : 'selected' }}>Select customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer }}" {{ old('customer') === $customer ? 'selected' : '' }}>{{ $customer }}</option>
                        @endforeach
                    </select>

                    @error('customer')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="channel" class="block text-sm font-semibold text-gray-700 mb-1.5">Communication Type</label>
                    <input type="text" name="channel" id="channel" value="{{ old('channel') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('channel') border-red-400 @enderror"
                        required>
                    @error('channel')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" name="email" value="{{ old('email', '') }}">
                <input type="hidden" name="phone" value="{{ old('phone', '') }}">


            </div>

            <div class="mb-4">
                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1.5">Subject</label>
                <select name="subject" id="subject"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('subject') border-red-400 @enderror"
                    required>
                    <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject }}" {{ old('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>
                    @endforeach
                </select>
                @error('subject')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="message" class="block text-sm font-semibold text-gray-700 mb-1.5">Message / Remarks</label>
                <textarea name="message" id="message" rows="4" placeholder="Enter message or remarks"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-y min-h-[80px] focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end mt-5">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                    Save Log
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mainContent = document.getElementById('mainContent');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalCard = document.getElementById('modalCard');
    const openModalBtn = document.getElementById('openModalBtn');
    const logForm = document.getElementById('logForm');

    function openModal() {
        modalOverlay.classList.remove('opacity-0', 'pointer-events-none');
        modalOverlay.classList.add('opacity-100', 'pointer-events-auto');
        modalCard.classList.remove('translate-y-1.5');
        modalCard.classList.add('translate-y-0');
        mainContent.classList.add('blur-sm', 'pointer-events-none', 'select-none');
    }

    function closeModal() {
        modalOverlay.classList.add('opacity-0', 'pointer-events-none');
        modalOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        modalCard.classList.add('translate-y-1.5');
        modalCard.classList.remove('translate-y-0');
        mainContent.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        logForm.reset();
    }

    openModalBtn.addEventListener('click', openModal);

    // Name search — fires as you type (300 ms debounce)
    const searchInput = document.getElementById('communicationSearch');
    if (searchInput) {
        const applySearch = () => {
            const value = searchInput.value.trim();
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        let debounceTimer;
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applySearch, 800);
        });

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { clearTimeout(debounceTimer); applySearch(); }
        });
    }


    modalOverlay.addEventListener('click', (event) => {
        if (event.target === modalOverlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    @if ($errors->any())
        openModal();
    @endif
</script>
@endpush
