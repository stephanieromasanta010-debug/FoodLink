<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodLink - System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        body { background: #f8fafc; color: #1e293b; display: flex; justify-content: center; padding: 20px; min-height: 100vh; }
        .app-container { max-width: 1280px; width: 100%; background: #fff; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.08); display: flex; flex-direction: column; min-height: 85vh;}

        /* Auth Screen */
        .auth-screen { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 40px; min-height: 600px; background: #f8fafc; }
        .auth-card { background: #fff; border-radius: 20px; padding: 40px; max-width: 460px; width: 100%; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .auth-card h1 { font-size: 26px; font-weight: 700; margin-bottom: 8px; color: #0f172a; }
        .auth-card .sub { font-size: 14px; color: #64748b; margin-bottom: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 500; color: #334155; margin-bottom: 6px; }
        .input-wrapper { display: flex; align-items: center; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 0 14px; transition: border-color 0.2s; }
        .input-wrapper:focus-within { border-color: #ea580c; background: #fff; box-shadow: 0 0 0 3px rgba(234,88,12,0.1); }
        .input-wrapper .icon { font-size: 18px; color: #94a3b8; margin-right: 10px; }
        .input-wrapper input { border: none; outline: none; background: transparent; padding: 14px 0; font-size: 15px; width: 100%; color: #0f172a; }
        .input-wrapper input::placeholder { color: #94a3b8; }
        .btn-primary { width: 100%; padding: 14px; background: #ea580c; color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-primary:hover { background: #c2410c; }

        /* Role Picker */
        .role-picker { display: flex; gap: 10px; margin-bottom: 24px; }
        .role-btn { flex: 1; padding: 14px 8px; border: 2px solid #e2e8f0; border-radius: 14px; background: #fff; cursor: pointer; text-align: center; transition: all 0.2s; }
        .role-btn:hover { border-color: #fdba74; background: #fff7ed; }
        .role-btn input[type="radio"]:checked + .role-content { border-color: #ea580c; background: #fff7ed; box-shadow: 0 0 0 3px rgba(234,88,12,0.1); }
        .role-btn .role-icon { font-size: 24px; margin-bottom: 4px; }
        .role-btn .role-name { font-size: 13px; font-weight: 600; color: #0f172a; }

        /* Dashboard Layout */
        .dashboard-layout { display: flex; flex-direction: column; flex: 1; width: 100%; }
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; background: #fff; border-bottom: 1px solid #e2e8f0; }
        .topbar .logo { font-size: 22px; font-weight: 800; color: #0f172a; }
        .topbar .logo span { color: #ea580c; }
        .topbar .user-info { display: flex; align-items: center; gap: 16px; font-size: 14px; }
        .topbar .role-badge { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; }
        .role-badge.customer { background: #ffedd5; color: #c2410c; }
        .role-badge.vendor { background: #dbeafe; color: #2563eb; }
        .role-badge.delivery { background: #d1fae5; color: #059669; }
        .topbar .signout { color: #ea580c; text-decoration: none; font-weight: 500; }
        .main-body { display: flex; flex: 1; width: 100%; }
        .sidebar { width: 230px; background: #1e293b; padding: 24px 0; flex-shrink: 0; display: flex; flex-direction: column; border-bottom-left-radius: 24px; }
        .sidebar .section-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #94a3b8; padding: 0 24px; margin-bottom: 12px; }
        .sidebar a { display: block; padding: 12px 24px; color: #cbd5e1; text-decoration: none; font-size: 14px; font-weight: 500; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.06); color: #fff; border-left-color: #ea580c; }
        .content-area { flex: 1; padding: 32px; background: #f8fafc; }
        .content-area h1 { font-size: 26px; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
        .content-area .page-sub { font-size: 14px; color: #64748b; margin-bottom: 28px; }

        /* Cards & Tables */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: #fff; border-radius: 16px; padding: 20px 24px; border-left: 4px solid #ea580c; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .stat-card.green { border-left-color: #10b981; }
        .stat-card.amber { border-left-color: #f59e0b; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card .label { font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .stat-card .value { font-size: 28px; font-weight: 700; color: #0f172a; }
        .table-section { background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 24px; }
        .table-section h3 { font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { text-align: left; padding: 10px 12px; background: #f1f5f9; color: #475569; font-weight: 600; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge.pending { background: #fef3c7; color: #d97706; }
        .badge.delivered { background: #d1fae5; color: #059669; }
        .hidden { display: none !important; }
    </style>
</head>
<body>
<div class="app-container">