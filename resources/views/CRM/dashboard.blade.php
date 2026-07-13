@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Dashboard')

@push('styles')
<style>
    .content {
        padding: 0;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header h1 {
        font-size: 28px;
    }

    .header p {
        color: #888;
        margin-top: 5px;
    }

    .search-box {
        width: 330px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 13px 45px 13px 18px;
        border: 1px solid #e5e5e5;
        outline: none;
        border-radius: 30px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,.08);
    }

    .search-box i {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: #fff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,.05);
    }

    .card p {
        color: #888;
    }

    .card h2 {
        margin: 15px 0;
        font-size: 30px;
    }

    .green {
        color: #1ca64c;
        font-weight: 600;
    }

    .red {
        color: #ff4d4d;
        font-weight: 600;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
    }

    .graph-card,
    .task-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,.05);
    }

    .graph-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .graph-header button {
        border: none;
        background: #1ca64c;
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
    }

    .small {
        color: #777;
        margin-bottom: 25px;
    }

    .status {
        color: #1ca64c;
        font-weight: 600;
    }

    .graph {
        height: 260px;
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        margin-top: 20px;
    }

    .bar {
        width: 55px;
        background: #1ca64c;
        border-radius: 10px 10px 0 0;
        transition: .3s;
    }

    .bar:hover {
        opacity: .8;
        transform: translateY(-5px);
    }

    .months {
        display: flex;
        justify-content: space-around;
        margin-top: 15px;
        color: #666;
    }

    .task-card h2 {
        margin-bottom: 20px;
    }

    .task {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
    }

    .orange {
        background: #ff9800;
    }

    .gray {
        background: #999;
    }

    .task-btn {
        width: 100%;
        border: none;
        background: #1ca64c;
        color: #fff;
        padding: 14px;
        border-radius: 10px;
        cursor: pointer;
        margin-top: 10px;
    }

    footer.page-footer {
        margin-top: 40px;
        background: #fff;
        padding: 40px;
        border-radius: 15px;
    }

    .footer-container {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 30px;
    }

    .footer-box h3 {
        margin-bottom: 15px;
    }

    .footer-box p,
    .footer-box li {
        color: #666;
        margin-bottom: 10px;
        list-style: none;
    }

    .footer-box a {
        text-decoration: none;
        color: #666;
    }

    .footer-box a:hover {
        color: #1ca64c;
    }

    @media (max-width: 1024px) {
        .cards {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .footer-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .search-box {
            width: 100%;
        }

        .cards {
            grid-template-columns: 1fr;
        }

        .graph {
            height: 220px;
        }

        .bar {
            width: 38px;
        }
    }

    @media (max-width: 480px) {
        .header h1 {
            font-size: 22px;
        }

        .card h2 {
            font-size: 24px;
        }

        .graph-preview {
            gap: 10px;
        }

        .bar {
            width: 28px;
        }

        footer.page-footer {
            padding: 25px;
        }
    }
</style>
@endpush

@section('content')
<div class="content">
    <div class="header">
        <div>
            <h1>Dashboard Overview</h1>
            <p>Welcome back, Ariel. Here's what's happening today.</p>
        </div>

        <div class="search-box">
            <input type="text" placeholder="What are you looking for?">
            <i class="fas fa-magnifying-glass"></i>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <p>Total Customers</p>
            <h2>2,420</h2>
            <span class="green">+12.5%</span>
        </div>

        <div class="card">
            <p>Active Deals</p>
            <h2>342</h2>
            <span class="green">+9.2%</span>
        </div>

        <div class="card">
            <p>Revenue (YTD)</p>
            <h2>$1.2M</h2>
            <span class="green">+18%</span>
        </div>

        <div class="card">
            <p>Churn Rate</p>
            <h2>2.4%</h2>
            <span class="red">-0.5%</span>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="graph-card">
            <div class="graph-header">
                <div>
                    <h2>Revenue Growth</h2>
                    <p class="small">Status: <span class="status"><i class="fas fa-circle"></i> Growing (+16.3%)</span></p>
                </div>
                <button>Last 6 months <i class="fas fa-angle-down"></i></button>
            </div>

            <p class="small">Revenue trend over the last 6 months</p>

            <div class="graph">
                <div class="bar" style="height:120px"></div>
                <div class="bar" style="height:135px"></div>
                <div class="bar" style="height:128px"></div>
                <div class="bar" style="height:150px"></div>
                <div class="bar" style="height:185px"></div>
                <div class="bar" style="height:220px"></div>
            </div>

            <div class="months">
                <span>Jan</span>
                <span>Feb</span>
                <span>March</span>
                <span>Apr</span>
                <span>May</span>
                <span>Jun</span>
            </div>
        </section>

        <section class="task-card">
            <h2>Upcoming Tasks</h2>

            <div class="task">
                <div class="dot green"></div>
                <div>
                    <h4>Call Sarah Jenkins</h4>
                    <p>1:00 PM</p>
                </div>
            </div>

            <div class="task">
                <div class="dot orange"></div>
                <div>
                    <h4>Prepare Q3 Report</h4>
                    <p>1:30 PM</p>
                </div>
            </div>

            <div class="task">
                <div class="dot red"></div>
                <div>
                    <h4>Meeting with TechCorp</h4>
                    <p>3:00 PM</p>
                </div>
            </div>

            <div class="task">
                <div class="dot gray"></div>
                <div>
                    <h4>Follow up Email</h4>
                    <p>Tomorrow</p>
                </div>
            </div>

            <button class="task-btn">View All Tasks</button>
        </section>
    </div>

    <footer class="page-footer">
        <div class="footer-container">
            <div class="footer-box">
                <h3>Support</h3>
                <p>AMBATUGROW</p>
                <p>ambatugrow@gmail.com</p>
                <p>+63 915 888 8888</p>
            </div>
            <div class="footer-box">
                <h3>Account</h3>
                <ul>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Login / Register</a></li>
                    <li><a href="#">Cart</a></li>
                    <li><a href="#">Wishlist</a></li>
                    <li><a href="#">Shop</a></li>
                </ul>
            </div>
            <div class="footer-box">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>
@endsection
