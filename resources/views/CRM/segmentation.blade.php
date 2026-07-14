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
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .segmentation-header h1 {
        font-size: 30px;
        font-weight: 700;
        color: #111827;
    }

    .segmentation-header p {
        color: #6b7280;
        margin-top: 4px;
        font-size: 14px;
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
</style>
@endpush

@section('content')
<div class="segmentation-content">
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

            <button>
                <i class="fas fa-plus"></i> Create Segment
            </button>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <div class="circle green"><i class="fas fa-user"></i></div>
            <h5>New Customers</h5>
            <h2>120</h2>
            <span>8.0%</span>
        </div>
        <div class="card">
            <div class="circle blue"><i class="fas fa-users"></i></div>
            <h5>Regular Customers</h5>
            <h2>650</h2>
            <span>52.0%</span>
        </div>
        <div class="card">
            <div class="circle purple"><i class="fas fa-crown"></i></div>
            <h5>VIP Customers</h5>
            <h2>250</h2>
            <span>20.0%</span>
        </div>
        <div class="card">
            <div class="circle red"><i class="fas fa-user-slash"></i></div>
            <h5>Inactive Customers</h5>
            <h2>230</h2>
            <span>16.4%</span>
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
                <tr>
                    <td class="new">New Customers</td>
                    <td>Customers who registered within the last 3 months.</td>
                    <td>120</td>
                    <td>₱1,250,000</td>
                    <td><i class="fas fa-pen"></i><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td class="regular">Regular Customers</td>
                    <td>Customers with regular purchases.</td>
                    <td>650</td>
                    <td>₱4,200,000</td>
                    <td><i class="fas fa-pen"></i><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td class="vip">VIP Customers</td>
                    <td>High value customers and frequent purchasers.</td>
                    <td>250</td>
                    <td>₱3,500,000</td>
                    <td><i class="fas fa-pen"></i><i class="fas fa-trash"></i></td>
                </tr>
                <tr>
                    <td class="inactive">Inactive Customers</td>
                    <td>Customers with no purchases in 6 months.</td>
                    <td>230</td>
                    <td>₱930,000</td>
                    <td><i class="fas fa-pen"></i><i class="fas fa-trash"></i></td>
                </tr>
            </tbody>
        </table>
    </div>

    <section class="marketing">
        <h3>Marketing Tools</h3>
        <p>Create campaigns and export customer lists.</p>
        <div class="marketing-buttons">
            <button class="primary-btn"><i class="fas fa-bullhorn"></i> Create Campaign</button>
            <button class="secondary-btn"><i class="fas fa-file-export"></i> Export Customer List</button>
        </div>
    </section>
</div>
@endsection
