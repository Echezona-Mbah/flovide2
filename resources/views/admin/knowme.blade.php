@include('admin.head')
<style>
    :root {
        --flovide-primary: #0f172a;
        --flovide-secondary: #1d4ed8;
        --flovide-accent: #2563eb;
        --flovide-gold: #fbbf24;
        --flovide-bg: #f3f6fb;
        --flovide-card: #ffffff;
        --flovide-border: #dfe7f2;
        --flovide-text: #10213a;
        --flovide-muted: #64748b;
        --flovide-success: #18a957;
        --flovide-warning: #f59e0b;
        --flovide-danger: #ef4444;
        --flovide-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background: linear-gradient(180deg, #edf3fb 0%, #f7f9fc 100%);
        color: var(--flovide-text);
    }

    .page-shell {
        max-width: 1440px;
        margin: 0 auto;
        padding: 32px 20px 48px;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        background: rgba(255,255,255,0.72);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(148, 163, 184, 0.2);
        border-radius: 18px;
        padding: 16px 20px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
    }

    .brand-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .brand-mark {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--flovide-primary) 0%, var(--flovide-secondary) 100%);
        position: relative;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.18);
    }

    .brand-mark::before {
        content: "F";
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 1.2rem;
        font-weight: 800;
    }

    .brand-text strong {
        display: block;
        font-size: 1rem;
        letter-spacing: 0.04em;
        color: var(--flovide-primary);
    }

    .brand-text span {
        display: block;
        color: var(--flovide-muted);
        font-size: 0.75rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        padding: 6px 2px;
    }

    .header-copy h1 {
        margin: 0;
        font-size: clamp(2rem, 3vw, 2.5rem);
        letter-spacing: -0.04em;
        color: var(--flovide-primary);
    }

    .header-copy p {
        margin: 8px 0 0;
        color: var(--flovide-muted);
        font-size: 0.95rem;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.08);
        border: 1px solid rgba(37, 99, 235, 0.15);
        color: var(--flovide-secondary);
        font-weight: 600;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: var(--flovide-success);
        box-shadow: 0 0 0 4px rgba(24, 169, 87, 0.14);
    }

    .content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.75fr);
        gap: 24px;
    }

    .panel {
        background: var(--flovide-card);
        border: 1px solid var(--flovide-border);
        border-radius: 24px;
        box-shadow: var(--flovide-shadow);
    }

    .form-panel {
        padding: 26px;
    }

    .panel-header {
        margin-bottom: 22px;
    }

    .panel-header h2 {
        margin: 0;
        font-size: 1.2rem;
        color: var(--flovide-primary);
        letter-spacing: -0.03em;
    }

    .panel-header p {
        margin: 8px 0 0;
        color: var(--flovide-muted);
        font-size: 0.9rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 24px 0 16px;
        padding-top: 8px;
        border-top: 1px solid var(--flovide-border);
        color: var(--flovide-primary);
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .section-title:first-of-type {
        margin-top: 0;
        border-top: none;
        padding-top: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        font-size: 0.84rem;
        color: var(--flovide-primary);
        font-weight: 600;
    }

    .required {
        color: var(--flovide-danger);
        margin-left: 4px;
    }

    .input-wrap {
        position: relative;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    select {
        width: 100%;
        border: 1.5px solid var(--flovide-border);
        background: #f8fafc;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 0.96rem;
        color: var(--flovide-text);
        outline: none;
        transition: 0.2s ease;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: rgba(37, 99, 235, 0.65);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        background: #fff;
    }

    select {
        appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, #64748b 50%), linear-gradient(135deg, #64748b 50%, transparent 50%);
        background-position: calc(100% - 18px) calc(50% - 2px), calc(100% - 12px) calc(50% - 2px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        padding-right: 42px;
    }

    .upload-card {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 18px;
        border: 1.5px dashed rgba(37, 99, 235, 0.3);
        border-radius: 18px;
        background: linear-gradient(180deg, rgba(37, 99, 235, 0.03), rgba(15, 23, 42, 0.015));
    }

    .avatar-preview {
        width: 76px;
        height: 76px;
        border-radius: 22px;
        overflow: hidden;
        background: linear-gradient(135deg, #dfeafc, #ecf4ff);
        border: 1px solid rgba(148, 163, 184, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .avatar-placeholder {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--flovide-primary), var(--flovide-secondary));
        color: #fff;
        display: grid;
        place-items: center;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .upload-meta {
        flex: 1;
        min-width: 0;
    }

    .upload-meta strong {
        display: block;
        margin-bottom: 6px;
        color: var(--flovide-primary);
    }

    .upload-meta span {
        display: block;
        color: var(--flovide-muted);
        font-size: 0.82rem;
        margin-bottom: 12px;
    }

    .file-input {
        position: relative;
        display: inline-block;
        overflow: hidden;
        cursor: pointer;
    }

    .file-input input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border-radius: 12px;
        background: var(--flovide-primary);
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        box-shadow: 0 10px 18px rgba(15, 23, 42, 0.15);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid var(--flovide-border);
    }

    .btn {
        appearance: none;
        border: none;
        border-radius: 14px;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 0.94rem;
        letter-spacing: -0.01em;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--flovide-secondary), var(--flovide-accent));
        color: #fff;
        box-shadow: 0 14px 22px rgba(37, 99, 235, 0.25);
    }

    .btn-secondary {
        background: #edf3fb;
        color: var(--flovide-primary);
        border: 1px solid var(--flovide-border);
    }

    .preview-panel {
        padding: 22px;
        position: relative;
        overflow: hidden;
    }

    .preview-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(37,99,235,0.10), transparent 38%);
        pointer-events: none;
    }

    .preview-card {
        position: relative;
        background: linear-gradient(180deg, #0b1530 0%, #111d3a 100%);
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid rgba(148,163,184,0.22);
        box-shadow: 0 18px 32px rgba(15,23,42,0.14);
        padding: 18px;
        color: #e5eeff;
    }

    .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .mini-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .mini-brand-mark {
        width: 22px;
        height: 22px;
        border-radius: 8px;
        background: linear-gradient(135deg, #f4cf6d, #f59e0b);
        display: grid;
        place-items: center;
        color: #0b1530;
        font-size: 0.72rem;
        font-weight: 800;
    }

    .verification-badge {
        border-radius: 999px;
        padding: 7px 10px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .profile-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
    }

    .profile-avatar {
        width: 88px;
        height: 88px;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(37,99,235,0.32), rgba(17,29,58,0.8));
        border: 1px solid rgba(255,255,255,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 1.8rem;
        flex-shrink: 0;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-meta h3 {
        margin: 0;
        font-size: clamp(1.2rem, 2vw, 1.7rem);
        letter-spacing: -0.04em;
    }

    .profile-meta p {
        margin: 6px 0 0;
        color: rgba(228, 234, 255, 0.8);
        font-size: 0.82rem;
    }

    .employee-id {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 14px;
        padding: 8px 10px;
        border-radius: 10px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        color: #dce8ff;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 12px;
        margin-top: 18px;
    }

    .stat-box {
        padding: 12px 14px;
        border-radius: 14px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
    }

    .stat-box strong {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(228, 234, 255, 0.7);
        margin-bottom: 5px;
    }

    .stat-box span {
        color: #fff;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .qr-box {
        margin-top: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 12px 14px;
    }

    .qr-code {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #dfe9ff, #eef6ff);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .qr-code::before,
    .qr-code::after {
        content: "";
        position: absolute;
        inset: 10px;
        background: repeating-linear-gradient(
            0deg,
            #0e213f 0,
            #0e213f 6px,
            transparent 6px,
            transparent 14px
        );
        opacity: 0.92;
        border-radius: 8px;
    }

    .qr-code::after {
        inset: 0;
        background: repeating-linear-gradient(
            90deg,
            #0e213f 0,
            #0e213f 6px,
            transparent 6px,
            transparent 14px
        );
        mask: radial-gradient(circle at center, transparent 0 34%, black 35% 100%);
    }

    .qr-text strong {
        display: block;
        color: #fff;
        font-size: 0.8rem;
        margin-bottom: 3px;
    }

    .qr-text span {
        color: rgba(228,234,255,0.72);
        font-size: 0.72rem;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .preview-panel {
            order: -1;
        }
    }

    @media (max-width: 680px) {
        .topbar,
        .page-header,
        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .page-shell {
            padding: 20px 14px 36px;
        }

        .form-panel,
        .preview-panel {
            padding: 18px;
        }

        .btn {
            width: 100%;
        }

        .upload-card {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>





<style>
    .employees-panel {
        margin-top: 28px;
        padding: 0 0 20px;
    }

    .employee-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .table-tools {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .table-search {
        display: block;
        position: relative;
        min-width: min(100%, 320px);
    }

    .table-search input {
        width: 100%;
        border: 1px solid rgba(148, 163, 184, 0.6);
        border-radius: 10px;
        background: #fff;
        color: #0f172a;
        padding: 11px 14px;
        font-size: 0.96rem;
        outline: none;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.04);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .table-search input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.13);
    }

    .employee-table-wrap {
        overflow-x: auto;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.05);
    }

    .employee-table {
        width: 100%;
        min-width: 1200px;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .employee-table th,
    .employee-table td {
        padding: 14px 12px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.22);
        vertical-align: middle;
        text-align: left;
        word-break: break-word;
    }

    .employee-table thead {
        background: #f8fafc;
    }

    .employee-table thead th {
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #475569;
        font-weight: 700;
    }

    .employee-photo-cell {
        width: 68px;
    }

    .employee-photo,
    .employee-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        object-fit: cover;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1d4ed8;
        font-weight: 700;
        font-size: 0.76rem;
        border: 2px solid rgba(37, 99, 235, 0.12);
    }

    .employee-name-cell {
        font-weight: 700;
        color: #0f172a;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: capitalize;
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-on-leave {
        background: #fef3c7;
        color: #92400e;
    }

    .action-cell {
        width: 180px;
    }

    .action-links {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .action-link:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .action-link.primary {
        background: #1d4ed8;
        color: #fff;
    }

    .action-link.secondary {
        background: #eef2ff;
        color: #1e3a8a;
    }

    .action-link.danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .employee-pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 18px;
    }

    .employee-pagination nav {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .employee-pagination .page-link,
    .employee-pagination .page-item .page-link,
    .employee-pagination .page-item.active .page-link {
        border-radius: 8px;
        border: 1px solid rgba(148, 163, 184, 0.5);
        padding: 7px 11px;
        color: #1f2937;
        background: #fff;
        text-decoration: none;
        font-size: 0.85rem;
    }

    .employee-pagination .page-item.active .page-link {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
    }

    .empty-state {
        background: linear-gradient(180deg, rgba(239, 246, 255, 0.7), rgba(255, 255, 255, 0.9));
        border: 1px dashed rgba(96, 165, 250, 0.5);
        border-radius: 18px;
        padding: 42px 18px;
        text-align: center;
        color: #334155;
    }

    .empty-icon {
        font-size: 2.2rem;
        margin-bottom: 10px;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 1.4rem;
    }

    .empty-state p {
        margin: 0;
        color: #475569;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    @media (max-width: 768px) {
        .employee-header {
            align-items: flex-start;
        }

        .table-tools,
        .table-search {
            width: 100%;
        }

        .table-search {
            min-width: 100%;
        }

        .employee-table {
            min-width: 980px;
        }
    }
</style>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            
            <div class="app-main__outer">
                <div class="app-main__inner">

                <!-- Donations Page Title -->
                <div class="page-shell">

                    <header class="page-header">
                        <div class="header-copy">
                            <h1>Employee Management</h1>
                            <p>Add and maintain employee verification profiles.</p>
                        </div>
                        <div class="status-chip">
                            <span class="status-dot"></span>
                            Employee record ready
                        </div>
                    </header>

                    <div class="content-grid">
                        <section class="panel form-panel" aria-labelledby="employee-form-title">
                            <div class="panel-header">
                                <h2 id="employee-form-title">Add Employee Details</h2>
                                <p>Capture the employee information used in the public verification and QR-enabled ID cards.</p>
                            </div>

                            <form id="employeeForm" action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="section-title">Profile</div>
                                <div class="field full">
                                    <label for="profile-image">Profile Picture <span class="required">*</span></label>
                                    <div class="upload-card">
                                        <div class="avatar-preview" id="avatarPreviewWrap">
                                            <img id="avatarPreview" src="" alt="Profile preview" style="display:none;">
                                            <div class="avatar-placeholder" id="avatarPlaceholder">+</div>
                                        </div>
                                        <div class="upload-meta">
                                            <strong>Upload employee photo</strong>
                                            <span>Recommended: 800x800px, JPG or PNG, clear headshot.</span>
                                            <label class="file-input" for="profile-image">
                                                <input id="profile-image" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
                                                <span class="file-btn">Choose image</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-title">Personal Information</div>
                                <div class="form-grid">
                                    <div class="field full">
                                        <label for="full-name">Full Name <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <input id="full-name" name="name" type="text" value="Employee Name" placeholder="Enter full name">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="employee-id">Employee ID <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <input id="employee-id" name="employee_id" type="text" value="FLV-2048" placeholder="e.g. FLV-2048">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="job-title">Job Title <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <input id="job-title" name="job_title" type="text" value="Senior Product Analyst" placeholder="Enter job title">
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="department">Department <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <select id="department" name="department">
                                                <option value="Software Engineer">Software Engineer</option>
                                                <option value="Mobile Engineer">Mobile Engineer</option>
                                                <option value="Digital Marketer">Digital Marketer</option>
                                                <option value="Customer Support">Customer Support</option>
                                                <option value="Finance">Finance</option>
                                                <option value="Product & Technology">Product & Technology</option>
                                                <option value="Operations">Operations</option>
                                                <option value="Human Resources">Human Resources</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <label for="email">Official Work Email <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <input id="email" name="work_email" type="email" value="example@flovide.com" placeholder="name@company.com">
                                        </div>
                                    </div>

                                    {{-- <div class="field">
                                        <label for="employment-date">Employment Date <span class="required">*</span></label>
                                        <div class="input-wrap"> --}}
                                            <input id="employment-date" hidden name="employment_date" type="date" value="2023-11-12">
                                        {{-- </div>
                                    </div> --}}

                                    <div class="field">
                                        <label for="id-card-expiration-date">
                                            ID Card Expiration Date <span class="required">*</span>
                                        </label>
                                        <div class="input-wrap">
                                            <input id="id-card-expiration-date" name="id_card_expiration_date" type="date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" >
                                        </div>
                                    </div>

                                    <div class="field full">
                                        <label for="status">Employment Status <span class="required">*</span></label>
                                        <div class="input-wrap">
                                            <select id="status" name="status">
                                                <option selected value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                                <option value="on_leave">On Leave</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn btn-secondary">Cancel</button>
                                    <button type="submit" id="saveEmployeeBtn" class="btn btn-primary">Save Employee</button>
                                </div>
                            </form>
                        </section>

                        <aside class="panel preview-panel" aria-label="Employee preview card">
                            <div class="panel-header">
                                <h2>Verification Preview</h2>
                                <p>Preview how this employee profile appears publicly.</p>
                            </div>

                            <div class="preview-card">
                                <div class="preview-header">
                                    <div class="mini-brand">
                                        <div class="mini-brand-mark">F</div>
                                        Flovide
                                    </div>
                                    <div class="verification-badge">Verified</div>
                                </div>

                                <div class="profile-row">
                                    <div class="profile-avatar" id="profileAvatarPreview">
                                        <img id="profileAvatarImage" src="" alt="Employee preview" style="display:none;">
                                        <span id="profileAvatarFallback">N</span>
                                    </div>
                                    <div class="profile-meta">
                                        <h3 id="preview-name">Nadia Adebayo</h3>
                                        <p id="preview-role">Senior Product Analyst</p>
                                        <div class="employee-id"><span>FLV-2048</span></div>
                                    </div>
                                </div>

                                <div class="stats">
                                    <div class="stat-box">
                                        <strong>Department</strong>
                                        <span>Product &amp; Technology</span>
                                    </div>
                                    <div class="stat-box">
                                        <strong>Status</strong>
                                        <span>Active</span>
                                    </div>
                                    <div class="stat-box">
                                        <strong>Email</strong>
                                        <span>nadia.adebayo@flovide.com</span>
                                    </div>
                                    <div class="stat-box">
                                        <strong>ID Card Expiration</strong>
                                        <span>12 Nov 2023</span>
                                    </div>
                                </div>

                                <div class="qr-box">
                                    <div class="qr-code" aria-hidden="true"></div>
                                    <div class="qr-text">
                                        <strong>Scan to verify</strong>
                                        <span>Employee ID QR</span>
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>


                    <section class="employees-panel panel" aria-label="Employee list section">
                        <div class="panel-header employee-header">
                            <div>
                                <h2>Employee Directory</h2>
                                <p>All employees created and saved in the system.</p>
                            </div>

                            @if(isset($employees) && $employees->count() > 0)
                                <div class="table-tools">
                                    <label class="table-search" for="employeeSearch">
                                        <span class="sr-only">Search employees</span>
                                        <input
                                            id="employeeSearch"
                                            type="search"
                                            placeholder="Search by name, employee ID or email"
                                            aria-label="Search employees"
                                        >
                                    </label>
                                </div>
                            @endif
                        </div>

                        @if(isset($employees) && $employees->count() > 0)
                            <div class="employee-table-wrap">
                                <table id="employeesTable" class="employee-table">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Full Name</th>
                                            <th>Employee ID</th>
                                            <th>Job Title</th>
                                            <th>Department</th>
                                            <th>Work Email</th>
                                            <th>Status</th>
                                            <th>ID Expiration</th>
                                            <th>Date Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                            @php
                                                
                                                $employeeName = $employee->name ?? 'Unnamed Employee';
                                                $employeeId = $employee->employee_id ?? $employee->employeeId ?? '—';
                                                $jobTitle = $employee->job_title ?? '—';
                                                $department = $employee->department ?? '—';
                                                $workEmail = $employee->work_email ?? '—';
                                                $status = $employee->status ?? '—';
                                                $expirationDate = $employee->id_card_expiration_date
                                                    ? \Carbon\Carbon::parse($employee->id_card_expiration_date)->format('d M Y')
                                                    : '—';
                                                $createdAt = $employee->created_at
                                                    ? \Carbon\Carbon::parse($employee->created_at)->format('d M Y')
                                                    : '—';
                                                $photo = $employee->photo_path ?? null;

                                                $verifyUrl = null;
                                                try {
                                                    if (!empty($employee->public_id)) {
                                                        $verifyUrl = route('employee.public', ['public_id' => $employee->public_id]);
                                                    }
                                                } catch (\Throwable $e) {
                                                    $verifyUrl = null;
                                                }

                                                if (!$verifyUrl && !empty($employee->public_id)) {
                                                    $verifyUrl = url('/employee/' . $employee->public_id);
                                                }

                                                $editUrl = null;
                                            @endphp

                                            <tr class="data-row" data-name="{{ strtolower($employeeName) }}" data-id="{{ strtolower((string)$employeeId) }}" data-email="{{ strtolower((string)$workEmail) }}">
                                                <td class="employee-photo-cell">
                                                    @if(!empty($photo))
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="{{ $employeeName }}" class="employee-photo">
                                                    @else
                                                        <span class="employee-avatar">
                                                            {{ strtoupper(substr(str_replace(' ', '', $employeeName), 0, 2) ?: 'EM') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="employee-name-cell">{{ $employeeName }}</td>
                                                <td>{{ $employeeId }}</td>
                                                <td>{{ $jobTitle }}</td>
                                                <td>{{ $department }}</td>
                                                <td>{{ $workEmail }}</td>
                                                <td>
                                                    <span class="status-pill status-{{ Str::slug((string)$status) }}">
                                                        {{ ucfirst((string)$status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $expirationDate }}</td>
                                                <td>{{ $createdAt }}</td>
                                                <td class="action-cell">
                                                    <div class="action-links">
                                                        @if($verifyUrl)
                                                            <a href="{{ $verifyUrl }}" target="_blank" rel="noopener noreferrer" class="action-link primary">View Profile</a>
                                                        @endif
                                                        @if($editUrl)
                                                            <a href="{{ $editUrl }}" class="action-link secondary">Edit</a>
                                                        @endif
                                                        <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="delete-employee-form">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="btn btn-danger btn-sm"title="Delete employee" aria-label="Delete {{ $employeeName }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if(method_exists($employees, 'links'))
                                <div class="employee-pagination">
                                    {{ $employees->onEachSide(1)->links() }}
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">👥</div>
                                <h3>No employees saved yet</h3>
                                <p>Employees created from the form above will appear here automatically.</p>
                            </div>
                        @endif
                    </section>
                </div>
            


              
            </div>

        </div>
    </div>

@include('admin.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('employeeSearch');
        const table = document.getElementById('employeesTable');

        if (!searchInput || !table) return;

        const rows = table.querySelectorAll('tbody tr.data-row');

        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const id = row.dataset.id || '';
                const email = row.dataset.email || '';
                const match = name.includes(query) || id.includes(query) || email.includes(query);
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>


<script>
    const imageInput = document.getElementById('profile-image');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');
    const profileAvatarImage = document.getElementById('profileAvatarImage');
    const profileAvatarFallback = document.getElementById('profileAvatarFallback');

    imageInput.addEventListener('change', function (event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const src = e.target.result;
            avatarPreview.src = src;
            avatarPreview.style.display = 'block';
            avatarPlaceholder.style.display = 'none';

            profileAvatarImage.src = src;
            profileAvatarImage.style.display = 'block';
            profileAvatarFallback.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    const nameInput = document.getElementById('full-name');
    const roleInput = document.getElementById('job-title');
    const employeeIdInput = document.getElementById('employee-id');
    const departmentInput = document.getElementById('department');
    const emailInput = document.getElementById('email');
    const statusInput = document.getElementById('status');
    // const dateInput = document.getElementById('employment-date');
    const dateInput = document.getElementById('id-card-expiration-date');

    const previewName = document.getElementById('preview-name');
    const previewRole = document.getElementById('preview-role');
    const previewEmployeeId = document.querySelector('.employee-id span');
    const previewDepartment = document.querySelector('.stat-box:nth-of-type(1) span');
    const previewStatus = document.querySelector('.stat-box:nth-of-type(2) span');
    const previewEmail = document.querySelector('.stat-box:nth-of-type(3) span');
    const previewJoin = document.querySelector('.stat-box:nth-of-type(4) span');

    function updatePreview() {
        previewName.textContent = nameInput.value || 'Employee Name';
        previewRole.textContent = roleInput.value || 'Role Title';
        previewEmployeeId.textContent = (employeeIdInput.value || 'FLV-0000').toUpperCase();
        previewDepartment.textContent = departmentInput.value || 'Department';
        previewStatus.textContent = statusInput.value || 'Active';
        previewEmail.textContent = emailInput.value || 'employee@flovide.com';

        const dateValue = dateInput.value;
        if (dateValue) {
            const formatted = new Date(dateValue + 'T00:00:00');
            previewJoin.textContent = formatted.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        } else {
            previewJoin.textContent = 'Not set';
        }

        const initials = (nameInput.value || 'Employee').split(' ').map(part => part[0]).slice(0, 2).join('').toUpperCase();
        const fallback = profileAvatarFallback;
        fallback.textContent = initials || 'E';
    }

    [nameInput, roleInput, employeeIdInput, departmentInput, emailInput, statusInput, dateInput].forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    updatePreview();









    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('employeeForm');

        if (!form) return;

        const submitButton = document.getElementById('saveEmployeeBtn');
        const originalButtonText = submitButton
            ? submitButton.innerHTML
            : 'Save Employee';

        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            // Respect browser validation.
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'Saving employee...';
            }

            Swal.fire({
                title: 'Saving employee',
                text: 'Please wait while we save the employee details.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const contentType = response.headers.get('content-type') || '';

                if (!contentType.includes('application/json')) {
                    throw new Error(
                        response.status === 401 || response.status === 419
                            ? 'Your session may have expired. Refresh the page and log in again.'
                            : 'The server returned an unexpected response. Please try again.'
                    );
                }

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Employee Added!',
                        text: data.message || 'Employee details saved successfully.',
                        confirmButtonText: 'Great',
                        confirmButtonColor: '#1d4ed8'
                    }).then(() => {
                        form.reset();

                        // Reset the profile picture preview.
                        const avatarPreview = document.getElementById('avatarPreview');
                        const avatarPlaceholder = document.getElementById('avatarPlaceholder');
                        const profileAvatarImage = document.getElementById('profileAvatarImage');
                        const profileAvatarFallback = document.getElementById('profileAvatarFallback');

                        if (avatarPreview) {
                            avatarPreview.src = '';
                            avatarPreview.style.display = 'none';
                        }

                        if (avatarPlaceholder) {
                            avatarPlaceholder.style.display = 'grid';
                        }

                        if (profileAvatarImage) {
                            profileAvatarImage.src = '';
                            profileAvatarImage.style.display = 'none';
                        }

                        if (profileAvatarFallback) {
                            profileAvatarFallback.style.display = 'block';
                            profileAvatarFallback.textContent = 'E';
                        }

                        // Reset preview text to safe placeholders.
                        const previewName = document.getElementById('preview-name');
                        const previewRole = document.getElementById('preview-role');
                        const previewEmployeeId = document.querySelector('.employee-id span');

                        if (previewName) previewName.textContent = 'Employee Name';
                        if (previewRole) previewRole.textContent = 'Role Title';
                        if (previewEmployeeId) previewEmployeeId.textContent = 'FLV-0000';

                        // Reset the other preview fields.
                        const statValues = document.querySelectorAll('.stat-box span');
                        if (statValues.length >= 4) {
                            statValues[0].textContent = 'Department';
                            statValues[1].textContent = 'Active';
                            statValues[2].textContent = 'employee@flovide.com';
                            statValues[3].textContent = 'Employment date';
                        }

                        form.dispatchEvent(new Event('employee:saved'));
                    });
                } else if (response.status === 422 && data.errors) {
                    const messages = Object.values(data.errors).flat();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Check Your Details',
                        html: messages.map(message =>
                            `<div>${escapeHtml(message)}</div>`
                        ).join(''),
                        confirmButtonColor: '#1d4ed8'
                    });
                } else if (response.status === 401 || response.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Session Expired',
                        text: 'Refresh the page and log in again before submitting.',
                        confirmButtonColor: '#1d4ed8'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to Save',
                        text: data.message || 'Something went wrong. Please try again.',
                        confirmButtonColor: '#1d4ed8'
                    });
                }
            } catch (error) {
                console.error('Employee submission failed:', error);

                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: error.message || 'Please check your connection and try again.',
                    confirmButtonColor: '#1d4ed8'
                });
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                }
            }
        });

        function escapeHtml(value) {
            const element = document.createElement('div');
            element.textContent = String(value);
            return element.innerHTML;
        }
    });










    document.addEventListener('submit', async function (event) {
        const form = event.target.closest('.delete-employee-form');

        if (!form) return;

        event.preventDefault();

        const result = await Swal.fire({
            title: 'Delete employee?',
            text: 'This employee will be removed from the employee list. You can restore the record later.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        });

        if (!result.isConfirmed) return;

        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to delete employee.');
            }

            const row = form.closest('tr');
            if (row) row.remove();

            await Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Something went wrong.'
            });
        } finally {
            if (button.isConnected) {
                button.disabled = false;
            }
        }
    });

</script>