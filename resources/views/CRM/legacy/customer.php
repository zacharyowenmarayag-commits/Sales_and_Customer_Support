<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customers - CRM</title>
<style>
  :root{
    --green: #2f9e44;
    --green-dark: #2b8a3e;
    --green-light: #ebfbee;
    --text-dark: #1a1a1a;
    --text-gray: #6b7280;
    --border: #e9ecef;
    --bg: #f8f9fa;
  }
  *{ box-sizing: border-box; margin:0; padding:0; }
  body{
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: #fff;
    color: var(--text-dark);
    display:flex;
    min-height: 100vh;
  }

  /* Sidebar */
  .sidebar{
    width: 220px;
    background: #fff;
    border-right: 1px solid var(--border);
    padding: 20px 14px;
    flex-shrink: 0;
  }
  .nav-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid var(--border);
    margin-bottom: 10px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-dark);
    cursor: pointer;
  }
  .nav-item.active{
    background: var(--green);
    color: #fff;
    border-color: var(--green);
  }
  .icon{
    width: 16px;
    display:inline-flex;
    justify-content:center;
    font-size: 14px;
  }

  .submenu{
    display:flex;
    flex-direction:column;
    gap: 2px;
    margin: 4px 0 14px 0;
    padding-left: 2px;
  }
  .submenu-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding: 9px 12px;
    border-radius: 8px;
    font-size: 13.5px;
    color: var(--text-dark);
    cursor: pointer;
  }
  .submenu-item.active{
    background: var(--green);
    color: #fff;
    font-weight: 500;
  }
  .submenu-item:not(.active):hover{
    background: var(--bg);
  }

  /* Main content */
  .main{
    flex: 1;
    padding: 32px 40px;
  }
  h1{
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 4px;
  }
  .subtitle{
    color: var(--text-gray);
    font-size: 14px;
    margin-bottom: 20px;
  }

  .search-bar{
    display:flex;
    align-items:center;
    gap:8px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 9px 14px;
    width: 260px;
    color: var(--text-gray);
    font-size: 13.5px;
    margin-bottom: 28px;
  }

  table{
    width: 100%;
    border-collapse: collapse;
  }
  thead th{
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    background: var(--bg);
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
  }
  tbody td{
    padding: 14px 16px;
    font-size: 13.5px;
    border-bottom: 1px solid var(--border);
    color: var(--text-dark);
  }
  tbody tr:hover{
    background: #fafafa;
  }

  .status{
    display:inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }
  .status.active{
    background: var(--green-light);
    color: var(--green-dark);
  }
  .status.inactive{
    background: #fdecea;
    color: #c0392b;
  }

  .actions{
    display:flex;
    gap: 12px;
  }
  .action-btn{
    background:none;
    border:none;
    cursor:pointer;
    font-size: 14px;
    color: #333;
  }
  .action-btn.delete{
    color: #333;
  }

  .pagination{
    display:flex;
    justify-content:flex-end;
    align-items:center;
    gap: 6px;
    margin-top: 24px;
  }
  .page-btn{
    width: 30px;
    height: 30px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius: 6px;
    font-size: 13px;
    color: var(--text-gray);
    cursor: pointer;
    border: none;
    background: none;
  }
  .page-btn.active{
    background: var(--green);
    color: #fff;
    font-weight: 600;
  }
</style>
</head>
<body>

  <aside class="sidebar">
    <a href="<?php echo e(route('som')); ?>" class="nav-item <?php echo e(request()->routeIs('som') ? 'active' : ''); ?>">
      <span class="icon">📋</span> SOM
    </a>
    <a href="<?php echo e(route('sprf')); ?>" class="nav-item <?php echo e(request()->routeIs('sprf') ? 'active' : ''); ?>">
      <span class="icon">📊</span> SPRF
    </a>
    <a href="<?php echo e(route('crm.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('crm.*') ? 'active' : ''); ?>">
      <span class="icon">👥</span> CRM
    </a>

    <div class="submenu">
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="submenu-item <?php echo e(request()->routeIs('crm.dashboard') ? 'active' : ''); ?>">
        <span class="icon">🏠</span> Dashboard
      </a>
      <a href="<?php echo e(route('crm.customers')); ?>" class="submenu-item <?php echo e(request()->routeIs('crm.customers') ? 'active' : ''); ?>">
        <span class="icon">👤</span> Customers
      </a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="submenu-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>">
        <span class="icon">🧾</span> Purchase History
      </a>
      <a href="<?php echo e(route('crm.comlog')); ?>" class="submenu-item <?php echo e(request()->routeIs('crm.comlog') ? 'active' : ''); ?>">
        <span class="icon">💬</span> Communication Logs
      </a>
      <a href="<?php echo e(route('crm.followup')); ?>" class="submenu-item <?php echo e(request()->routeIs('crm.followup') ? 'active' : ''); ?>">
        <span class="icon">🚩</span> Follow-Ups
      </a>
      <a href="<?php echo e(route('crm.dashboard')); ?>" class="submenu-item">
        <span class="icon">🎯</span> Segmentation
      </a>
    </div>

    <a href="<?php echo e(route('dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
      <span class="icon">🛠️</span> AFSSM
    </a>
  </aside>

  <main class="main">
    <h1>Customers</h1>
    <p class="subtitle">Manage your customer information and details.</p>

    <div class="search-bar">🔍 Search customer...</div>

    <table>
      <thead>
        <tr>
          <th>Customer ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>CUST-0001</td>
          <td>Juan Dela Cruz</td>
          <td>juan@email.com</td>
          <td>09123456789</td>
          <td><span class="status active">Active</span></td>
          <td>
            <div class="actions">
              <button class="action-btn">✏️</button>
              <button class="action-btn delete">🗑️</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>CUST-0002</td>
          <td>Maria Santos</td>
          <td>maria@email.com</td>
          <td>09171112222</td>
          <td><span class="status active">Active</span></td>
          <td>
            <div class="actions">
              <button class="action-btn">✏️</button>
              <button class="action-btn delete">🗑️</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>CUST-0003</td>
          <td>Pedro Reyes</td>
          <td>pedro@email.com</td>
          <td>09981234567</td>
          <td><span class="status inactive">Inactive</span></td>
          <td>
            <div class="actions">
              <button class="action-btn">✏️</button>
              <button class="action-btn delete">🗑️</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>CUST-0002</td>
          <td>Maria Santos</td>
          <td>maria@email.com</td>
          <td>09171112222</td>
          <td><span class="status active">Active</span></td>
          <td>
            <div class="actions">
              <button class="action-btn">✏️</button>
              <button class="action-btn delete">🗑️</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>CUST-0003</td>
          <td>Pedro Reyes</td>
          <td>pedro@email.com</td>
          <td>09981234567</td>
          <td><span class="status inactive">Inactive</span></td>
          <td>
            <div class="actions">
              <button class="action-btn">✏️</button>
              <button class="action-btn delete">🗑️</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="pagination">
      <button class="page-btn active">1</button>
      <button class="page-btn">2</button>
      <button class="page-btn">3</button>
      <button class="page-btn">4</button>
      <button class="page-btn">5</button>
      <button class="page-btn">›</button>
    </div>
  </main>

</body>
</html>
