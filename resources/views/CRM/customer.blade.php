@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Customers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your customer records and contact details.</p>
        </div>

        <button type="button" id="openModalBtn" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Customer
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
                id="customerSearch"
                placeholder="Search customer name..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
            />
            <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Phone</th>

                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="customersTableBody">
                @forelse ($customers as $customer)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $customer->customer_id }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $customer->email ?: '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $customer->phone ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No customers yet. Click "Add Customer" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $customers])
    </div>
</div>

<div id="modalOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200" id="modalCard">
        <h2 class="text-base font-bold text-gray-900 mb-5">Add Customer</h2>

        <form action="{{ route('crm.customers.store') }}" method="POST" id="customerForm">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-1.5">First Name</label>
                    <input
                        type="text"
                        name="first_name"
                        id="first_name"
                        value="{{ old('first_name') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                        required
                    >
                    @error('first_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Last Name</label>
                    <input
                        type="text"
                        name="last_name"
                        id="last_name"
                        value="{{ old('last_name') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                        required
                    >
                    @error('last_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email (optional)</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    >
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Phone (optional)</label>
                    <input
                        type="text"
                        name="phone"
                        id="phone"
                        value="{{ old('phone') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    >
                </div>
            </div>

            <div class="flex justify-end mt-5">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                    Save Customer
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
    const customerForm = document.getElementById('customerForm');

    function openModal() {
        if (!modalOverlay || !modalCard) return;
        modalOverlay.classList.remove('opacity-0', 'pointer-events-none');
        modalOverlay.classList.add('opacity-100', 'pointer-events-auto');
        modalCard.classList.remove('translate-y-1.5');
        modalCard.classList.add('translate-y-0');
        if (mainContent) {
            mainContent.classList.add('blur-sm', 'pointer-events-none', 'select-none');
        }
    }

    function closeModal() {
        if (!modalOverlay || !modalCard) return;
        modalOverlay.classList.add('opacity-0', 'pointer-events-none');
        modalOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        modalCard.classList.add('translate-y-1.5');
        modalCard.classList.remove('translate-y-0');
        if (mainContent) {
            mainContent.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        }
        customerForm?.reset();
    }

    openModalBtn?.addEventListener('click', openModal);

    modalOverlay?.addEventListener('click', (event) => {
        if (event.target === modalOverlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // Name searcher — fires as you type (300 ms debounce)
    const searchInput = document.getElementById('customerSearch');
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

    @if ($errors->any())
        openModal();
    @endif
</script>
@endpush

