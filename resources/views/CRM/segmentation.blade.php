@extends('layouts.app')

@section('title', 'AmbatuGrow - Customer Segmentation')

@push('styles')
<style>
    * { font-family: 'Inter', sans-serif; }

    .segmentation-content {
        padding: 0;
    }

    .segmentation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .segmentation-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.015em;
    }

    .segmentation-header p {
        color: #6b7280;
        margin-top: 2px;
        font-size: 0.75rem;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .search {
        position: relative;
    }

    .search input {
        width: 270px;
        padding: 12px 42px 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
    }

    .search i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .header-right button {
        background: #3f8d2f;
        color: #fff;
        border: none;
        padding: 12px 18px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
    }

    .header-right button:hover {
        background: #337326;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
    }

    .circle {
        width: 50px;
        height: 50px;
        margin: auto;
        margin-bottom: 15px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 18px;
    }

    .green { background: #57b957; }
    .blue { background: #5b8def; }
    .purple { background: #9b6df2; }
    .red { background: #ef6b6b; }

    .card h5 {
        color: #666;
        font-weight: 500;
    }

    .card h2 {
        margin: 12px 0;
        font-size: 32px;
    }

    .card span {
        color: #999;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .table-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-card thead {
        background: #f7f7f7;
    }

    .table-card thead th {
        padding: 12px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-card tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }

    .table-card tbody tr:hover {
        background: #fafafa;
    }

    .new { color: #3f8d2f; font-weight: 600; }
    .regular { color: #2f80ed; font-weight: 600; }
    .vip { color: #9b51e0; font-weight: 600; }
    .inactive { color: #eb5757; font-weight: 600; }

    .table-card td i {
        cursor: pointer;
        margin-right: 12px;
        color: #777;
        transition: .3s;
    }

    .table-card td i:hover {
        color: #3f8d2f;
    }

    .marketing {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 35px;
    }

    .marketing h3 {
        margin-bottom: 8px;
    }

    .marketing p {
        color: #777;
        margin-bottom: 20px;
    }

    .marketing-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .primary-btn,
    .secondary-btn {
        border: none;
        padding: 12px 22px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
    }

    .primary-btn {
        background: #3f8d2f;
        color: #fff;
    }

    .secondary-btn {
        background: #eef8ef;
        color: #3f8d2f;
    }

    @media (max-width: 992px) {
        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cards {
            grid-template-columns: 1fr;
        }

        .header-right {
            width: 100%;
        }

        .search {
            width: 100%;
        }

        .search input {
            width: 100%;
        }

        .table-card table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    @media (max-width: 480px) {
        .segmentation-header h1 {
            font-size: 22px;
        }

        .card h2 {
            font-size: 24px;
        }

        .primary-btn,
        .secondary-btn {
            width: 100%;
        }
    }

    .crm-modal-overlay {
        display: none;
    }

    .crm-modal-overlay.is-open {
        display: flex;
        opacity: 1;
        pointer-events: auto;
    }

    #mainContent.is-blurred {
        filter: blur(4px);
        pointer-events: none;
        user-select: none;
    }
</style>
@endpush

@section('content')
<div class="segmentation-content" id="mainContent">
    <div class="segmentation-header">
        <div>
            <h1>Customer Segmentation</h1>
            <p>Segment customers for marketing and loyalty programs.</p>
        </div>

        <div class="header-right">
            <div class="search">
                <input type="text" placeholder="What are you looking for?">
                <i class="fas fa-magnifying-glass"></i>
            </div>

            <button type="button" id="openSegmentModalBtn">
                <i class="fas fa-plus"></i> Create Segment
            </button>
        </div>

    </div>

    <div class="cards">
        <div class="card">
            <div class="circle green"><i class="fas fa-user"></i></div>
            <h5>New Customers</h5>
            <h2>{{ $newCount }}</h2>
            <span>{{ $totalCount > 0 ? number_format(($newCount / $totalCount) * 100, 1) : 0 }}%</span>
        </div>
        <div class="card">
            <div class="circle blue"><i class="fas fa-users"></i></div>
            <h5>Regular Customers</h5>
            <h2>{{ $regularCount }}</h2>
            <span>{{ $totalCount > 0 ? number_format(($regularCount / $totalCount) * 100, 1) : 0 }}%</span>
        </div>
        <div class="card">
            <div class="circle purple"><i class="fas fa-crown"></i></div>
            <h5>VIP Customers</h5>
            <h2>{{ $vipCount }}</h2>
            <span>{{ $totalCount > 0 ? number_format(($vipCount / $totalCount) * 100, 1) : 0 }}%</span>
        </div>
        <div class="card">
            <div class="circle red"><i class="fas fa-user-slash"></i></div>
            <h5>Inactive Customers</h5>
            <h2>{{ $inactiveCount }}</h2>
            <span>{{ $totalCount > 0 ? number_format(($inactiveCount / $totalCount) * 100, 1) : 0 }}%</span>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Segment</th>
                    <th>Description</th>
                    <th>Customers</th>
                    <th>Revenue</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($segments as $seg)
                @php
                    $class = match($seg->segment_id) {
                        'SEG-NEW' => 'new',
                        'SEG-REG' => 'regular',
                        'SEG-VIP' => 'vip',
                        'SEG-INA' => 'inactive',
                        default => 'text-green-700 font-semibold'
                    };
                    $rowId = 'row-' . $seg->segment_id;
                @endphp
                <tr id="{{ $rowId }}">
                    <td class="{{ $class }} segment-name-cell">{{ $seg->segment_name }}</td>
                    <td class="segment-desc-cell">{{ $seg->description }}</td>
                    <td class="segment-count-cell">{{ $seg->estimated_count }}</td>
                    <td class="segment-sales-cell">₱{{ number_format($seg->projected_sales, 2) }}</td>
                    <td>
                        <button type="button" class="edit-segment-btn text-gray-500 hover:text-green-700 bg-transparent border-0 p-0 cursor-pointer" 
                                data-name="{{ $seg->segment_name }}"
                                data-description="{{ $seg->description }}"
                                data-count="{{ $seg->estimated_count }}"
                                data-sales="{{ $seg->projected_sales }}"
                                data-row-id="{{ $rowId }}"
                                data-segment-id="{{ $seg->segment_id }}">
                            <i class="fas fa-pen"></i>
                        </button>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <section class="marketing">
        <h3>Marketing Tools</h3>
        <p>Create campaigns and export customer lists.</p>
        <div class="marketing-buttons">
            <button type="button" id="openCampaignModalBtn" class="primary-btn inline-flex items-center justify-center gap-2">
                <i class="fas fa-bullhorn"></i> <span>Create Campaign</span>
            </button>
            <button type="button" id="openExportModalBtn" class="secondary-btn inline-flex items-center justify-center gap-2">
                <i class="fas fa-file-export"></i> <span>Export Customer List</span>
            </button>
        </div>

    </section>
</div>

<div id="campaignModalOverlay" class="crm-modal-overlay fixed inset-0 z-50 items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-2xl mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 relative" id="campaignModalCard" role="dialog" aria-modal="true" aria-labelledby="campaignModalTitle">
        <button
            type="button"
            id="closeCampaignModalBtn"
            class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition"
            aria-label="Close"
        >
            <i class="fas fa-times text-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-gray-900" id="campaignModalTitle">Create Campaign</h2>
        <p class="text-sm text-gray-500 mt-1 mb-6">Schedule outreach for one of your current customer segments.</p>

        <form id="campaignForm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="campaignName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Campaign name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="campaign_name"
                        id="campaignName"
                        placeholder="e.g. July VIP Offer"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                        required
                    >
                </div>

                <div>
                    <label for="targetSegment" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Target segment <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="target_segment"
                        id="targetSegment"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                        required
                    >
                        <option value="" disabled selected>Select segment</option>
                        <option value="New Customers">New Customers</option>
                        <option value="Regular Customers">Regular Customers</option>
                        <option value="VIP Customers">VIP Customers</option>
                        <option value="Inactive Customers">Inactive Customers</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="campaignMessage" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="message"
                    id="campaignMessage"
                    rows="4"
                    placeholder="Write the campaign message"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-y min-h-[100px] focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    required
                ></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    id="cancelCampaignModalBtn"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold border border-gray-200 text-gray-700 hover:bg-gray-50 transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition"
                >
                    Schedule Campaign
                </button>
            </div>
        </form>
    </div>
</div>

<div id="segmentModalOverlay" class="crm-modal-overlay fixed inset-0 z-50 items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-2xl mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 relative" id="segmentModalCard">
        <button
            type="button"
            id="closeSegmentModalBtn"
            class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition"
            aria-label="Close"
        >
            <i class="fas fa-times text-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-gray-900">Create Segment</h2>
        <p class="text-sm text-gray-500 mt-1 mb-6">Define a customer group for more focused outreach.</p>

        <form id="segmentForm" action="{{ route('crm.segmentation.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="segmentName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Segment name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="segment_name"
                        id="segmentName"
                        placeholder="e.g. Loyalty Members"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                        required
                    >
                </div>
                <div>
                    <label for="estimatedCount" class="block text-sm font-semibold text-gray-700 mb-1.5">Estimated customer count</label>
                    <input
                        type="number"
                        name="estimated_count"
                        id="estimatedCount"
                        value="0"
                        min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    >
                </div>
            </div>

            <div class="mb-4">
                <label for="segmentDescription" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Description <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="description"
                    id="segmentDescription"
                    placeholder="Describe the customer group"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    required
                >
            </div>

            <div class="mb-6">
                <label for="projectedSales" class="block text-sm font-semibold text-gray-700 mb-1.5">Projected sales (₱)</label>
                <input
                    type="number"
                    name="projected_sales"
                    id="projectedSales"
                    value="0"
                    min="0"
                    step="0.01"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                >
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    id="cancelSegmentModalBtn"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold border border-gray-200 text-gray-700 hover:bg-gray-50 transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition"
                >
                    Save Segment
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editSegmentModalOverlay" class="crm-modal-overlay fixed inset-0 z-50 items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-2xl mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 relative" id="editSegmentModalCard">
        <button
            type="button"
            id="closeEditSegmentModalBtn"
            class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition"
            aria-label="Close"
        >
            <i class="fas fa-times text-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-gray-900">Edit Segment</h2>
        <p class="text-sm text-gray-500 mt-1 mb-6">Update the details of this customer segment.</p>

        <form id="editSegmentForm" action="{{ route('crm.segmentation.update') }}" method="POST">
            @csrf
            <input type="hidden" name="segment_id" id="editSegmentId">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="editSegmentName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Segment name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="segment_name"
                        id="editSegmentName"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    >
                </div>
                <div>
                    <label for="editEstimatedCount" class="block text-sm font-semibold text-gray-700 mb-1.5">Estimated customer count</label>
                    <input
                        type="number"
                        name="estimated_count"
                        id="editEstimatedCount"
                        min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                    >
                </div>
            </div>

            <div class="mb-4">
                <label for="editSegmentDescription" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Description <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="description"
                    id="editSegmentDescription"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                >
            </div>

            <div class="mb-6">
                <label for="editProjectedSales" class="block text-sm font-semibold text-gray-700 mb-1.5">Projected sales (₱)</label>
                <input
                    type="number"
                    name="projected_sales"
                    id="editProjectedSales"
                    min="0"
                    step="0.01"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
                >
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    id="cancelEditSegmentModalBtn"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold border border-gray-200 text-gray-700 hover:bg-gray-50 transition"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition"
                >
                    Update Segment
                </button>
            </div>
        </form>
    </div>
</div>

<div id="exportModalOverlay" class="crm-modal-overlay fixed inset-0 z-50 items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-2xl mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 relative" id="exportModalCard">
        <button
            type="button"
            id="closeExportModalBtn"
            class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition"
            aria-label="Close"
        >
            <i class="fas fa-times text-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-gray-900">Export Customer List</h2>
        <p class="text-sm text-gray-500 mt-1 mb-6">Preview your customer segments before downloading.</p>

        <div class="border border-gray-200 rounded-xl overflow-hidden mb-6">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3.5">Segment</th>
                        <th class="px-5 py-3.5 text-center">Customers</th>
                        <th class="px-5 py-3.5 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="exportPreviewBody">
                </tbody>
            </table>
        </div>

        <div class="flex justify-end gap-3">
            <button
                type="button"
                id="cancelExportModalBtn"
                class="px-5 py-2.5 rounded-lg text-sm font-semibold border border-gray-200 text-gray-700 hover:bg-gray-50 transition"
            >
                Cancel
            </button>
            <button
                type="button"
                id="confirmExportBtn"
                class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition inline-flex items-center justify-center gap-1.5 cursor-pointer"
                onclick="document.getElementById('cancelExportModalBtn')?.click()"
            >
                <i class="fas fa-check"></i> Done
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mainContent = document.getElementById('mainContent');

    function openModal(overlay, card) {
        if (!overlay || !card) return;

        overlay.classList.add('is-open');
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100', 'pointer-events-auto');
        card.classList.remove('translate-y-1.5');
        card.classList.add('translate-y-0');
        mainContent?.classList.add('is-blurred', 'blur-sm', 'pointer-events-none', 'select-none');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(overlay, card, form) {
        if (!overlay || !card) return;

        overlay.classList.remove('is-open');
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100', 'pointer-events-auto');
        card.classList.add('translate-y-1.5');
        card.classList.remove('translate-y-0');
        mainContent?.classList.remove('is-blurred', 'blur-sm', 'pointer-events-none', 'select-none');
        document.body.classList.remove('overflow-hidden');
        form?.reset();
    }

    function setupModal({ overlayId, cardId, openBtnId, closeBtnIds, formId, onSubmit }) {
        const overlay = document.getElementById(overlayId);
        const card = document.getElementById(cardId);
        const form = document.getElementById(formId);
        const openBtn = document.getElementById(openBtnId);

        const close = () => closeModal(overlay, card, form);
        const open = () => openModal(overlay, card);

        if (openBtn) {
            openBtn.addEventListener('click', open);
        }
        closeBtnIds.forEach((id) => {
            document.getElementById(id)?.addEventListener('click', close);
        });

        card?.addEventListener('click', (event) => {
            event.stopPropagation();
        });

        overlay?.addEventListener('click', (event) => {
            if (event.target === overlay) {
                close();
            }
        });

        if (form && onSubmit) {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                onSubmit();
            });
        }

        return { overlay, close };
    }


    const segmentModal = setupModal({
        overlayId: 'segmentModalOverlay',
        cardId: 'segmentModalCard',
        openBtnId: 'openSegmentModalBtn',
        closeBtnIds: ['closeSegmentModalBtn', 'cancelSegmentModalBtn'],
        formId: 'segmentForm',
    });

    const campaignModal = setupModal({
        overlayId: 'campaignModalOverlay',
        cardId: 'campaignModalCard',
        openBtnId: 'openCampaignModalBtn',
        closeBtnIds: ['closeCampaignModalBtn', 'cancelCampaignModalBtn'],
        formId: 'campaignForm',
        onSubmit: () => {
            const name = document.getElementById('campaignName').value;
            alert(`Campaign "${name}" has been scheduled successfully!`);
            campaignModal.close();
        }
    });

    const editSegmentModal = setupModal({
        overlayId: 'editSegmentModalOverlay',
        cardId: 'editSegmentModalCard',
        openBtnId: null,
        closeBtnIds: ['closeEditSegmentModalBtn', 'cancelEditSegmentModalBtn'],
        formId: 'editSegmentForm',
    });

    const exportModal = setupModal({
        overlayId: 'exportModalOverlay',
        cardId: 'exportModalCard',
        openBtnId: 'openExportModalBtn',
        closeBtnIds: ['closeExportModalBtn', 'cancelExportModalBtn'],
        formId: null,
    });

    document.getElementById('openExportModalBtn')?.addEventListener('click', () => {
        const previewBody = document.getElementById('exportPreviewBody');
        if (previewBody) {
            previewBody.innerHTML = '';
            
            const rows = document.querySelectorAll('.table-card tbody tr');
            rows.forEach(tr => {
                const name = tr.querySelector('.segment-name-cell').innerText;
                const count = tr.querySelector('.segment-count-cell').innerText;
                const revenue = tr.querySelector('.segment-sales-cell').innerText;
                
                const newRowHtml = `
                    <tr>
                        <td class="px-5 py-4 font-medium text-gray-900">${name}</td>
                        <td class="px-5 py-4 text-center text-gray-600">${count}</td>
                        <td class="px-5 py-4 text-right text-gray-600">${revenue}</td>
                    </tr>
                `;
                previewBody.insertAdjacentHTML('beforeend', newRowHtml);
            });
        }
    });

    document.getElementById('confirmExportBtn')?.addEventListener('click', () => {
        exportModal.close();
    });

    function bindEditButton(btn) {
        btn.addEventListener('click', () => {
            const name = btn.getAttribute('data-name');
            const desc = btn.getAttribute('data-description');
            const count = btn.getAttribute('data-count');
            const sales = btn.getAttribute('data-sales');
            const segmentId = btn.getAttribute('data-segment-id');

            // Show current values as placeholders/guides, values themselves are empty
            const nameInput = document.getElementById('editSegmentName');
            nameInput.placeholder = name;
            nameInput.value = '';

            const countInput = document.getElementById('editEstimatedCount');
            countInput.placeholder = count;
            countInput.value = '';

            const descInput = document.getElementById('editSegmentDescription');
            descInput.placeholder = desc;
            descInput.value = '';

            const salesInput = document.getElementById('editProjectedSales');
            salesInput.placeholder = Math.round(parseFloat(sales) || 0);
            salesInput.value = '';

            document.getElementById('editSegmentId').value = segmentId;

            openModal(document.getElementById('editSegmentModalOverlay'), document.getElementById('editSegmentModalCard'));
        });
    }

    document.querySelectorAll('.edit-segment-btn').forEach(btn => {
        bindEditButton(btn);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        if (campaignModal.overlay?.classList.contains('is-open')) {
            campaignModal.close();
        } else if (segmentModal.overlay?.classList.contains('is-open')) {
            segmentModal.close();
        } else if (editSegmentModal.overlay?.classList.contains('is-open')) {
            editSegmentModal.close();
        } else if (exportModal.overlay?.classList.contains('is-open')) {
            exportModal.close();
        }
    });
</script>
@endpush
