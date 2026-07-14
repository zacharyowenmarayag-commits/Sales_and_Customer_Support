<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Communication Logs</title>
<style>
  :root{
    --green: #1f7a4d;
    --green-dark: #16603b;
    --bg: #f4f5f7;
    --sidebar-bg: #ffffff;
    --border: #e5e7eb;
    --text: #1f2430;
    --text-muted: #9aa1ac;
    --text-soft: #6b7280;
  }

  *{ box-sizing: border-box; }

  body{
    margin:0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: var(--bg);
    color: var(--text);
    display:flex;
    min-height:100vh;
  }

  /* ---------- Sidebar ---------- */
  .sidebar{
    width: 220px;
    background: var(--sidebar-bg);
    border-right: 1px solid var(--border);
    padding: 20px 14px;
    flex-shrink:0;
  }

  .nav-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:8px;
    font-size:14px;
    font-weight:500;
    color:#374151;
    margin-bottom:6px;
    cursor:pointer;
    border:1px solid transparent;
  }

  .nav-item:hover{ background:#f9fafb; }

  .nav-item.active{
    background: var(--green);
    color:#fff;
  }

  .nav-item .icon{
    width:16px;
    height:16px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
  }

  .nav-group{ margin-top:2px; }

  .sub-items{
    border: 1px solid var(--border);
    border-radius:8px;
    padding:4px;
    margin-bottom:10px;
    margin-top:2px;
  }

  .sub-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:9px 10px;
    border-radius:6px;
    font-size:13.5px;
    color:#4b5563;
    cursor:pointer;
  }

  .sub-item:hover{ background:#f9fafb; }

  .sub-item.active{
    background: var(--green);
    color:#fff;
    font-weight:500;
  }

  .sub-item .icon{
    width:15px;
    height:15px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    flex-shrink:0;
    opacity:0.9;
  }

  .bottom-nav{
    margin-top:10px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:9px 10px;
    font-size:14px;
    font-weight:500;
    color:#374151;
    display:flex;
    align-items:center;
    gap:10px;
  }

  /* ---------- Main content ---------- */
  .main{
    flex:1;
    padding:32px 40px;
    transition: filter .2s ease;
  }

  .main.blurred{
    filter: blur(2px);
    pointer-events:none;
    user-select:none;
  }

  .page-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:24px;
  }

  .page-header h1{
    font-size:22px;
    font-weight:700;
    margin:0 0 6px 0;
  }

  .page-header p{
    margin:0;
    color: var(--text-soft);
    font-size:13.5px;
  }

  .btn-primary{
    background: var(--green);
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:6px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    transition: background .15s ease;
  }

  .btn-primary:hover{ background: var(--green-dark); }

  /* ---------- Table ---------- */
  .card{
    background:#fff;
    border-radius:12px;
    border:1px solid var(--border);
    overflow:hidden;
  }

  table{
    width:100%;
    border-collapse:collapse;
  }

  thead th{
    text-align:left;
    font-size:12.5px;
    font-weight:600;
    color:#6b7280;
    text-transform:none;
    padding:14px 24px;
    background:#fafbfc;
    border-bottom:1px solid var(--border);
  }

  tbody td{
    padding:16px 24px;
    font-size:14px;
    border-bottom:1px solid var(--border);
    color:#1f2430;
  }

  tbody tr:last-child td{ border-bottom:none; }

  tbody tr:hover td{ background:#fafbfc; }

  .muted{ color: var(--text-muted); }

  /* ---------- Modal ---------- */
  .modal-overlay{
    position:fixed;
    inset:0;
    background: rgba(255,255,255,0.55);
    display:flex;
    align-items:center;
    justify-content:center;
    opacity:0;
    pointer-events:none;
    transition: opacity .18s ease;
    z-index:100;
  }

  .modal-overlay.open{
    opacity:1;
    pointer-events:auto;
  }

  .modal{
    background:#fff;
    width:520px;
    max-width:92vw;
    border-radius:12px;
    padding:28px 28px 24px;
    box-shadow: 0 20px 45px rgba(20,25,35,0.18), 0 2px 8px rgba(20,25,35,0.08);
    transform: translateY(6px);
    transition: transform .18s ease;
  }

  .modal-overlay.open .modal{
    transform: translateY(0);
  }

  .modal h2{
    margin:0 0 20px 0;
    font-size:16px;
    font-weight:700;
  }

  .form-row{
    display:flex;
    gap:16px;
    margin-bottom:16px;
  }

  .field{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:6px;
  }

  .field label{
    font-size:13px;
    font-weight:600;
    color:#374151;
  }

  .field input,
  .field textarea{
    border:1px solid var(--border);
    border-radius:8px;
    padding:10px 12px;
    font-size:13.5px;
    font-family:inherit;
    color:#1f2430;
    outline:none;
  }

  .field input::placeholder,
  .field textarea::placeholder{
    color:#b0b6bf;
  }

  .field input:focus,
  .field textarea:focus{
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgba(31,122,77,0.12);
  }

  .field textarea{
    resize:vertical;
    min-height:80px;
  }

  .modal-actions{
    display:flex;
    justify-content:flex-end;
    margin-top:20px;
  }
</style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="<?php echo e(route('som')); ?>" class="nav-item <?php echo e(request()->routeIs('som') ? 'active' : ''); ?>">
      <span class="icon">📄</span> SOM
    </a>
    <a href="<?php echo e(route('sprf')); ?>" class="nav-item <?php echo e(request()->routeIs('sprf') ? 'active' : ''); ?>">
      <span class="icon">📊</span> SPRF
    </a>
    <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.*') ? 'active' : ''); ?>">
      <span class="icon">🔁</span> CRM
    </a>

    <div class="sub-items">
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="sub-item <?php echo e(request()->routeIs('crm.dashboard') ? 'active' : ''); ?>">
        <span class="icon">▦</span> Dashboard
      </a>
      <a href="<?php echo e(route('crm.customers')); ?>" class="sub-item <?php echo e(request()->routeIs('crm.customers') ? 'active' : ''); ?>">
        <span class="icon">👥</span> Customers
      </a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="sub-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>">
        <span class="icon">🧾</span> Purchase History
      </a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="sub-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>">
        <span class="icon">💬</span> Communication Logs
      </a>
      <a href="<?php echo e(route('crm.followup')); ?>" class="sub-item <?php echo e(request()->routeIs('crm.followup') ? 'active' : ''); ?>">
        <span class="icon">🕘</span> Follow-Ups
      </a>
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="sub-item">
        <span class="icon">◔</span> Segmentation
      </a>
    </div>

    <a href="<?php echo e(route('dashboard')); ?>" class="bottom-nav <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
      <span class="icon">✂</span> AFSSM
    </a>
  </div>

  <!-- Main content -->
  <div class="main" id="mainContent">
    <div class="page-header">
      <div>
        <h1>Communication Logs</h1>
        <p>Track all communications and interactions with customers.</p>
      </div>
      <button class="btn-primary" id="openModalBtn">+ Add Log</button>
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Customer</th>
            <th>Type</th>
            <th>Subject</th>
            <th>Handled By</th>
          </tr>
        </thead>
        <tbody id="logsBody">
          <tr>
            <td class="muted">Jun 26, 2024</td>
            <td>Juan Dela Cruz</td>
            <td class="muted">Email</td>
            <td>Product Inquiry</td>
            <td class="muted">Admin</td>
          </tr>
          <tr>
            <td class="muted">Jun 26, 2024</td>
            <td>Maria Santos</td>
            <td class="muted">Phone Call</td>
            <td>Order Follow-up</td>
            <td class="muted">Support 1</td>
          </tr>
          <tr>
            <td class="muted">Jun 27, 2024</td>
            <td>Pedro Reyes</td>
            <td class="muted">Chat</td>
            <td>Shipping Status</td>
            <td class="muted">Support 2</td>
          </tr>
          <tr>
            <td class="muted">Jun 26, 2024</td>
            <td>ABC Corporation</td>
            <td class="muted">Email</td>
            <td>Quotation Request</td>
            <td class="muted">Admin</td>
          </tr>
          <tr>
            <td class="muted">Jun 25, 2024</td>
            <td>Ella Garcia</td>
            <td class="muted">Phone Call</td>
            <td>Payment Confirmation</td>
            <td class="muted">Support 1</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <h2>Add Communication Log</h2>
      <form id="logForm">
        <div class="form-row">
          <div class="field">
            <label for="customer">Customer</label>
            <input type="text" id="customer" placeholder="">
          </div>
          <div class="field">
            <label for="commType">Communication Type</label>
            <input type="text" id="commType" placeholder="">
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="subject">Subject</label>
            <input type="text" id="subject" placeholder="Enter subject">
          </div>
        </div>

        <div class="form-row">
          <div class="field">
            <label for="remarks">Message / Remarks</label>
            <textarea id="remarks" placeholder="Enter message or remarks"></textarea>
          </div>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-primary" id="saveLogBtn">Save Log</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const mainContent = document.getElementById('mainContent');
    const modalOverlay = document.getElementById('modalOverlay');
    const openModalBtn = document.getElementById('openModalBtn');
    const logForm = document.getElementById('logForm');
    const logsBody = document.getElementById('logsBody');

    function openModal(){
      modalOverlay.classList.add('open');
      mainContent.classList.add('blurred');
    }

    function closeModal(){
      modalOverlay.classList.remove('open');
      mainContent.classList.remove('blurred');
      logForm.reset();
    }

    openModalBtn.addEventListener('click', openModal);

    // Click outside the modal card closes it
    modalOverlay.addEventListener('click', (e)=>{
      if(e.target === modalOverlay) closeModal();
    });

    // Escape key closes it
    document.addEventListener('keydown', (e)=>{
      if(e.key === 'Escape') closeModal();
    });

    logForm.addEventListener('submit', (e)=>{
      e.preventDefault();

      const customer = document.getElementById('customer').value.trim() || '—';
      const type = document.getElementById('commType').value.trim() || '—';
      const subject = document.getElementById('subject').value.trim() || '—';

      const today = new Date();
      const dateStr = today.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });

      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="muted">${dateStr}</td>
        <td>${customer}</td>
        <td class="muted">${type}</td>
        <td>${subject}</td>
        <td class="muted">Admin</td>
      `;
      logsBody.prepend(row);

      closeModal();
    });
  </script>

</body>
</html>
