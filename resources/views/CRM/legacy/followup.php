<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Follow-Up Management</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lucide-static/0.378.0/umd/lucide.min.js"></script>
<style>
  :root {
    --green-700: #15803d;
    --green-800: #166534;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-900: #111827;
    --orange-text: #f97316;
    --orange-bg: #fff7ed;
    --green-text: #16a34a;
    --green-bg: #dcfce7;
    --red-text: #ef4444;
    --red-bg: #fef2f2;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: var(--gray-50);
    color: var(--gray-900);
  }

  .app {
    display: flex;
    min-height: 100vh;
  }

  /* Sidebar */
  .sidebar {
    width: 224px;
    flex-shrink: 0;
    background: #fff;
    border-right: 1px solid var(--gray-200);
    padding: 16px 12px;
  }

  .nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
    color: var(--gray-600);
    cursor: pointer;
    margin-bottom: 4px;
    transition: background 0.15s ease;
  }
  .nav-item:hover { background: var(--gray-100); }
  .nav-item.active {
    background: var(--green-700);
    color: #fff;
    font-weight: 500;
  }
  .nav-item.active:hover { background: var(--green-700); }
  .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; }

  .sub-nav {
    margin-left: 8px;
    padding-left: 8px;
    border-left: 1px solid var(--gray-100);
    margin-top: 4px;
    margin-bottom: 4px;
  }
  .sub-nav .nav-item { color: var(--gray-500); font-size: 14px; }
  .sub-nav .nav-item.current {
    background: var(--gray-100);
    color: var(--gray-900);
    font-weight: 500;
  }
  .sub-nav .nav-item svg { width: 15px; height: 15px; }

  /* Main content */
  .main {
    flex: 1;
    padding: 24px 32px;
  }

  .page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
  }

  .page-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    color: var(--gray-900);
  }

  .page-header p {
    font-size: 14px;
    color: var(--gray-500);
    margin: 4px 0 0;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--green-700);
    color: #fff;
    border: none;
    font-size: 14px;
    font-weight: 500;
    padding: 9px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s ease;
  }
  .btn-primary:hover { background: var(--green-800); }
  .btn-primary svg { width: 16px; height: 16px; }

  /* Table */
  .table-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    overflow: hidden;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
  }

  thead tr {
    border-bottom: 1px solid var(--gray-200);
  }

  thead th {
    font-size: 12px;
    font-weight: 500;
    color: var(--gray-500);
    padding: 12px 24px;
  }

  tbody tr {
    border-bottom: 1px solid var(--gray-100);
    font-size: 14px;
    color: var(--gray-700);
  }
  tbody tr:last-child { border-bottom: none; }

  tbody td {
    padding: 16px 24px;
  }

  .cell-muted { color: var(--gray-500); }

  .badge {
    display: inline-block;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 9999px;
  }
  .badge.Pending { background: var(--orange-bg); color: var(--orange-text); }
  .badge.Completed { background: var(--green-bg); color: var(--green-text); }
  .badge.Cancelled { background: var(--red-bg); color: var(--red-text); }

  .actions {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--gray-400);
  }
  .actions button {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: inherit;
    display: flex;
  }
  .actions button:hover.edit { color: var(--gray-700); }
  .actions button:hover.delete { color: var(--red-text); }
  .actions svg { width: 15px; height: 15px; }

  .empty-row td {
    text-align: center;
    padding: 40px 24px;
    color: var(--gray-400);
    font-size: 14px;
  }

  /* Modal */
  .modal-overlay {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 50;
  }
  .modal-overlay.open { display: flex; }

  .modal-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
  }

  .modal {
    position: relative;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 520px;
    margin: 0 16px;
    padding: 24px;
  }

  .modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  .modal-header h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: var(--gray-900);
  }
  .modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--gray-400);
    padding: 0;
    display: flex;
  }
  .modal-close:hover { color: var(--gray-600); }
  .modal-close svg { width: 18px; height: 18px; }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
  }

  .field label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    color: var(--gray-600);
    margin-bottom: 6px;
  }

  .field input,
  .field select,
  .field textarea {
    width: 100%;
    border: 1px solid var(--gray-300);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    color: var(--gray-700);
    font-family: inherit;
    background: #fff;
  }

  .field input:focus,
  .field select:focus,
  .field textarea:focus {
    outline: none;
    border-color: var(--green-700);
    box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.15);
  }

  .field textarea {
    resize: none;
  }

  .field-notes { margin-bottom: 24px; }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
  }

  .modal-footer .btn-primary {
    padding: 8px 20px;
  }
</style>
</head>
<body>

<div class="app">
  <!-- Sidebar -->
  <aside class="sidebar">
    <a href="<?php echo e(route('som')); ?>" class="nav-item <?php echo e(request()->routeIs('som') ? 'active' : ''); ?>"><i data-lucide="file-text"></i><span>SOM</span></a>
    <a href="<?php echo e(route('sprf')); ?>" class="nav-item <?php echo e(request()->routeIs('sprf') ? 'active' : ''); ?>"><i data-lucide="bar-chart-2"></i><span>SPRF</span></a>
    <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.*') ? 'active' : ''); ?>"><i data-lucide="shield-check"></i><span>CRM</span></a>

    <div class="sub-nav">
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.dashboard') ? 'active' : ''); ?>"><i data-lucide="layout-grid"></i><span>Dashboard</span></a>
      <a href="<?php echo e(route('crm.customers')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.customers') ? 'active' : ''); ?>"><i data-lucide="users"></i><span>Customers</span></a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>"><i data-lucide="receipt"></i><span>Purchase History</span></a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>"><i data-lucide="message-square"></i><span>Communication Logs</span></a>
      <a href="<?php echo e(route('crm.followup')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.followup') ? 'current' : ''); ?>"><i data-lucide="calendar-clock"></i><span>Follow-Ups</span></a>
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.dashboard') ? 'active' : ''); ?>"><i data-lucide="pie-chart"></i><span>Segmentation</span></a>
    </div>

    <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"><i data-lucide="wrench"></i><span>AFSSM</span></a>
  </aside>

  <!-- Main -->
  <main class="main">
    <div class="page-header">
      <div>
        <h1>Follow-Up Management</h1>
        <p>Manage and track customer follow-ups.</p>
      </div>
      <button class="btn-primary" id="openModalBtn">
        <i data-lucide="plus"></i>
        Add Follow-Up
      </button>
    </div>

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th>Follow-Up ID</th>
            <th>Customer</th>
            <th>Assigned To</th>
            <th>Follow-Up Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <!-- rows injected by JS -->
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-backdrop" id="modalBackdrop"></div>
  <div class="modal">
    <div class="modal-header">
      <h2>Add / Update Follow-Up</h2>
      <button class="modal-close" id="closeModalBtn"><i data-lucide="x"></i></button>
    </div>

    <form id="followUpForm">
      <div class="form-grid">
        <div class="field">
          <label for="customer">Customer</label>
          <input type="text" id="customer" name="customer" />
        </div>
        <div class="field">
          <label for="assignedTo">Assigned To</label>
          <input type="text" id="assignedTo" name="assignedTo" />
        </div>
        <div class="field">
          <label for="date">Follow-Up Date</label>
          <input type="date" id="date" name="date" />
        </div>
        <div class="field">
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="">Select status</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <div class="field field-notes">
        <label for="notes">Notes</label>
        <textarea id="notes" name="notes" rows="4" placeholder="Enter notes here"></textarea>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  // ---- Data ----
  let followUps = [
    { id: "FU-0001", customer: "Juan Dela Cruz", assignedTo: "Admin", date: "Jul 01, 2024", status: "Pending" },
    { id: "FU-0002", customer: "Maria Santos", assignedTo: "Support 1", date: "Jul 02, 2024", status: "Completed" },
    { id: "FU-0003", customer: "Pedro Reyes", assignedTo: "Support 2", date: "Jul 03, 2024", status: "Pending" },
    { id: "FU-0004", customer: "ABC Corporation", assignedTo: "Admin", date: "Jul 05, 2024", status: "Cancelled" },
    { id: "FU-0005", customer: "Ella Garcia", assignedTo: "Support 1", date: "Jul 06, 2024", status: "Pending" },
  ];

  const tableBody = document.getElementById("tableBody");
  const modalOverlay = document.getElementById("modalOverlay");
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const modalBackdrop = document.getElementById("modalBackdrop");
  const followUpForm = document.getElementById("followUpForm");

  function renderTable() {
    tableBody.innerHTML = "";

    if (followUps.length === 0) {
      tableBody.innerHTML = `
        <tr class="empty-row">
          <td colspan="6">No follow-ups yet. Click "Add Follow-Up" to create one.</td>
        </tr>`;
      return;
    }

    followUps.forEach((f) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${f.id}</td>
        <td>${f.customer}</td>
        <td class="cell-muted">${f.assignedTo}</td>
        <td class="cell-muted">${f.date}</td>
        <td><span class="badge ${f.status}">${f.status}</span></td>
        <td>
          <div class="actions">
            <button class="edit" title="Edit"><i data-lucide="pencil"></i></button>
            <button class="delete" title="Delete" data-id="${f.id}"><i data-lucide="trash-2"></i></button>
          </div>
        </td>
      `;
      tableBody.appendChild(tr);
    });

    lucide.createIcons();

    document.querySelectorAll(".actions .delete").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        followUps = followUps.filter((f) => f.id !== id);
        renderTable();
      });
    });
  }

  function formatDate(dateStr) {
    if (!dateStr) return "—";
    const d = new Date(dateStr + "T00:00:00");
    return d.toLocaleDateString("en-US", { month: "short", day: "2-digit", year: "numeric" });
  }

  function openModal() {
    followUpForm.reset();
    modalOverlay.classList.add("open");
  }

  function closeModal() {
    modalOverlay.classList.remove("open");
  }

  openModalBtn.addEventListener("click", openModal);
  closeModalBtn.addEventListener("click", closeModal);
  modalBackdrop.addEventListener("click", closeModal);

  followUpForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = new FormData(followUpForm);
    const nextIndex = followUps.length + 1;
    const nextId = "FU-" + String(nextIndex).padStart(4, "0");

    followUps.push({
      id: nextId,
      customer: formData.get("customer") || "—",
      assignedTo: formData.get("assignedTo") || "—",
      date: formatDate(formData.get("date")),
      status: formData.get("status") || "Pending",
    });

    renderTable();
    closeModal();
  });

  // Initial render
  renderTable();
  lucide.createIcons();
</script>

</body>
</html>
