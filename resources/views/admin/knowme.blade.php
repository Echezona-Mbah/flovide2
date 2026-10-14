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
        margin-top: 36px;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 4px 10px -2px rgba(15, 23, 42, 0.02);
        padding: 26px 28px;
    }

    .employee-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 22px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .employee-header-info h2 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.01em;
    }

    .employee-total-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #1d4ed8;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
    }

    .employee-header-desc {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.88rem;
    }

    .table-tools-container {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* Filter segmented controls */
    .status-filter-group {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .status-tab-btn {
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 6px 13px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-tab-btn:hover {
        color: #0f172a;
    }

    .status-tab-btn.active {
        background: #ffffff;
        color: #1d4ed8;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    /* Modern search bar */
    .table-search-box {
        position: relative;
        display: flex;
        align-items: center;
        min-width: 310px;
    }

    .search-icon-left {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        pointer-events: none;
        font-size: 0.88rem;
        transition: color 0.2s ease;
    }

    .table-search-box:focus-within .search-icon-left {
        color: #2563eb;
    }

    .table-search-box input {
        width: 100%;
        height: 40px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #0f172a;
        padding: 0 38px 0 38px;
        font-size: 0.88rem;
        outline: none;
        transition: all 0.2s ease;
    }

    .table-search-box input:focus {
        background: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .search-clear-btn {
        position: absolute;
        right: 10px;
        background: #e2e8f0;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        color: #64748b;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.72rem;
        transition: all 0.15s ease;
    }

    .search-clear-btn:hover {
        background: #cbd5e1;
        color: #0f172a;
    }

    .search-meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        font-size: 0.82rem;
        color: #64748b;
        padding: 0 2px;
    }

    .search-count-pill {
        font-weight: 600;
        color: #334155;
    }

    /* Table wrap & styling */
    .employee-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
    }

    .employee-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 1080px;
    }

    .employee-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 13px 16px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }

    .employee-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
        font-size: 0.88rem;
    }

    .employee-table tbody tr.data-row {
        transition: background 0.15s ease;
    }

    .employee-table tbody tr.data-row:hover {
        background: #f8fbff;
    }

    .employee-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Employee Profile Cell */
    .emp-profile-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 220px;
    }

    .emp-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .emp-avatar-img,
    .emp-avatar-fallback {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .emp-avatar-img {
        border: 2px solid #e2e8f0;
    }

    .emp-avatar-fallback {
        background: linear-gradient(135deg, #1e3a8a, #2563eb);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.18);
    }

    .emp-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }

    .emp-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.92rem;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .emp-email {
        font-size: 0.78rem;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        text-decoration: none;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        max-width: 190px;
        transition: color 0.15s ease;
    }

    .emp-email:hover {
        color: #2563eb;
    }

    /* Monospace ID Badge */
    .emp-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.8rem;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
    }

    .emp-id-badge i {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    /* Job & Dept Cell */
    .emp-role-wrap {
        display: flex;
        flex-direction: column;
        gap: 3px;
        min-width: 140px;
    }

    .emp-title-text {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.88rem;
    }

    .emp-dept-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
        border-radius: 6px;
        font-size: 0.74rem;
        font-weight: 600;
        width: fit-content;
        white-space: nowrap;
    }

    /* Status Pills */
    .emp-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-badge-active {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-badge-active .status-dot {
        background: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }

    .status-badge-inactive {
        background: #fff1f2;
        color: #9f1239;
        border: 1px solid #fecdd3;
    }

    .status-badge-inactive .status-dot {
        background: #f43f5e;
        box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.2);
    }

    .status-badge-on-leave,
    .status-badge-on_leave {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-badge-on-leave .status-dot,
    .status-badge-on_leave .status-dot {
        background: #f59e0b;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
    }

    /* Date Wrap */
    .date-cell-wrap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 0.82rem;
        white-space: nowrap;
    }

    .date-cell-wrap i {
        color: #94a3b8;
        font-size: 0.78rem;
    }

    /* Actions */
    .emp-actions-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 34px;
        padding: 0 12px;
        border-radius: 9px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.18s ease;
        white-space: nowrap;
    }

    .action-btn-view {
        background: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .action-btn-view:hover {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(29, 78, 216, 0.2);
    }

    .action-btn-delete {
        width: 34px;
        height: 34px;
        padding: 0;
        background: #fff1f2;
        color: #e11d48;
        border-color: #fecdd3;
    }

    .action-btn-delete:hover {
        background: #e11d48;
        color: #ffffff;
        border-color: #e11d48;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(225, 29, 72, 0.2);
    }

    /* No search results feedback */
    .no-results-box {
        padding: 44px 20px;
        text-align: center;
        color: #64748b;
    }

    .no-results-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin: 0 auto 12px;
    }

    .no-results-box h4 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .no-results-box p {
        margin: 0 0 16px;
        font-size: 0.88rem;
        color: #64748b;
    }

    .reset-search-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        padding: 7px 16px;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .reset-search-btn:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .employee-pagination {
        display: flex;
        justify-content: flex-end;
        margin-top: 18px;
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

    @media (max-width: 900px) {
        .employee-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-tools-container {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
        }

        .status-filter-group {
            display: flex;
            overflow-x: auto;
        }

        .status-tab-btn {
            flex: 1;
            justify-content: center;
            white-space: nowrap;
        }

        .table-search-box {
            min-width: 100%;
            width: 100%;
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
                            <div class="employee-header-info">
                                <h2>
                                    Employee Directory
                                    <span id="employeeTotalBadge" class="employee-total-badge">{{ isset($employees) ? $employees->count() : 0 }}</span>
                                </h2>
                                <p class="employee-header-desc">Search, filter, and manage verified employee identity records.</p>
                            </div>

                            @if(isset($employees) && $employees->count() > 0)
                                <div class="table-tools-container">
                                    <div class="status-filter-group" role="tablist" aria-label="Filter by employee status">
                                        <button type="button" class="status-tab-btn active" data-status-filter="all">All</button>
                                        <button type="button" class="status-tab-btn" data-status-filter="active">Active</button>
                                        <button type="button" class="status-tab-btn" data-status-filter="inactive">Inactive</button>
                                        <button type="button" class="status-tab-btn" data-status-filter="on_leave">On Leave</button>
                                    </div>

                                    <div class="table-search-box">
                                        <i class="fas fa-search search-icon-left" aria-hidden="true"></i>
                                        <input
                                            id="employeeSearch"
                                            type="text"
                                            placeholder="Search by name, ID, email, title..."
                                            aria-label="Search employees"
                                            autocomplete="off"
                                        >
                                        <button type="button" id="clearSearchBtn" class="search-clear-btn" aria-label="Clear search">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if(isset($employees) && $employees->count() > 0)
                            <div class="search-meta-bar">
                                <span id="searchResultCount" class="search-count-pill">
                                    Showing all {{ $employees->count() }} {{ Str::plural('employee', $employees->count()) }}
                                </span>
                            </div>

                            <div class="employee-table-wrap">
                                <table id="employeesTable" class="employee-table">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Employee ID</th>
                                            <th>Role & Department</th>
                                            <th>Status</th>
                                            <th>ID Expiration</th>
                                            <th>Date Added</th>
                                            <th style="text-align: right;">Actions</th>
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
                                                $rawStatus = Str::slug((string)($employee->status ?? 'active'), '_');
                                                $status = $employee->status ?? 'active';
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

                                                $initials = strtoupper(substr(str_replace(' ', '', $employeeName), 0, 2) ?: 'EM');
                                            @endphp

                                            <tr class="data-row"
                                                data-name="{{ strtolower($employeeName) }}"
                                                data-id="{{ strtolower((string)$employeeId) }}"
                                                data-email="{{ strtolower((string)$workEmail) }}"
                                                data-title="{{ strtolower((string)$jobTitle) }}"
                                                data-dept="{{ strtolower((string)$department) }}"
                                                data-status="{{ $rawStatus }}">
                                                <td>
                                                    <div class="emp-profile-cell">
                                                        <div class="emp-avatar-wrap">
                                                            @if(!empty($photo))
                                                                <img src="{{ asset('storage/' . $photo) }}" alt="{{ $employeeName }}" class="emp-avatar-img">
                                                            @else
                                                                <div class="emp-avatar-fallback">
                                                                    {{ $initials }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="emp-info">
                                                            <div class="emp-name" title="{{ $employeeName }}">{{ $employeeName }}</div>
                                                            @if($workEmail !== '—')
                                                                <a href="mailto:{{ $workEmail }}" class="emp-email" title="{{ $workEmail }}">
                                                                    <i class="far fa-envelope" aria-hidden="true"></i> {{ $workEmail }}
                                                                </a>
                                                            @else
                                                                <span class="emp-email">—</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="emp-id-badge">
                                                        <i class="fas fa-id-badge" aria-hidden="true"></i>
                                                        {{ $employeeId }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="emp-role-wrap">
                                                        <span class="emp-title-text">{{ $jobTitle }}</span>
                                                        @if($department !== '—')
                                                            <span class="emp-dept-pill">{{ $department }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="emp-status-badge status-badge-{{ $rawStatus }}">
                                                        <span class="status-dot"></span>
                                                        {{ ucwords(str_replace('_', ' ', (string)$status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="date-cell-wrap">
                                                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                                        {{ $expirationDate }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="date-cell-wrap">
                                                        <i class="far fa-clock" aria-hidden="true"></i>
                                                        {{ $createdAt }}
                                                    </span>
                                                </td>
                                                <td style="text-align: right;">
                                                    <div class="emp-actions-group" style="justify-content: flex-end;">
                                                        @if($verifyUrl)
                                                            <a href="{{ $verifyUrl }}" target="_blank" rel="noopener noreferrer" class="action-btn action-btn-view" title="View Public Profile">
                                                                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                                                <span>View</span>
                                                            </a>
                                                        @endif

                                                        <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="delete-employee-form">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit" class="action-btn action-btn-delete" title="Delete employee" aria-label="Delete {{ $employeeName }}">
                                                                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        <!-- Hidden empty state row when no search results match -->
                                        <tr id="noResultsRow" style="display: none;">
                                            <td colspan="7">
                                                <div class="no-results-box">
                                                    <div class="no-results-icon">
                                                        <i class="fas fa-search" aria-hidden="true"></i>
                                                    </div>
                                                    <h4>No matching employees found</h4>
                                                    <p id="noResultsQueryText">No employee records match your search or filter.</p>
                                                    <button type="button" id="resetSearchBtn" class="reset-search-btn">
                                                        <i class="fas fa-undo" aria-hidden="true"></i> Clear filters
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
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
        const clearBtn = document.getElementById('clearSearchBtn');
        const resetBtn = document.getElementById('resetSearchBtn');
        const table = document.getElementById('employeesTable');
        const resultCount = document.getElementById('searchResultCount');
        const totalBadge = document.getElementById('employeeTotalBadge');
        const noResultsRow = document.getElementById('noResultsRow');
        const noResultsQueryText = document.getElementById('noResultsQueryText');
        const statusTabs = document.querySelectorAll('.status-tab-btn');

        if (!table) return;

        let activeStatus = 'all';

        function filterEmployees() {
            const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
            const rows = table.querySelectorAll('tbody tr.data-row');
            let matchCount = 0;
            const totalCount = rows.length;

            if (clearBtn) {
                clearBtn.style.display = query.length > 0 ? 'inline-flex' : 'none';
            }

            rows.forEach(row => {
                const name = row.dataset.name || '';
                const id = row.dataset.id || '';
                const email = row.dataset.email || '';
                const title = row.dataset.title || '';
                const dept = row.dataset.dept || '';
                const status = row.dataset.status || '';

                const matchesQuery = !query ||
                    name.includes(query) ||
                    id.includes(query) ||
                    email.includes(query) ||
                    title.includes(query) ||
                    dept.includes(query) ||
                    status.includes(query);

                const matchesStatus = (activeStatus === 'all') || (status === activeStatus);

                if (matchesQuery && matchesStatus) {
                    row.style.display = '';
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update result count message
            if (resultCount) {
                if (query.length > 0 || activeStatus !== 'all') {
                    resultCount.textContent = `Found ${matchCount} of ${totalCount} ${totalCount === 1 ? 'employee' : 'employees'}`;
                } else {
                    resultCount.textContent = `Showing all ${totalCount} ${totalCount === 1 ? 'employee' : 'employees'}`;
                }
            }

            if (totalBadge) {
                totalBadge.textContent = totalCount;
            }

            // Show or hide "no results" state
            if (noResultsRow) {
                if (matchCount === 0 && totalCount > 0) {
                    noResultsRow.style.display = '';
                    if (noResultsQueryText) {
                        let text = 'No employee records match';
                        if (query) {
                            text += ` "${query}"`;
                        }
                        if (activeStatus !== 'all') {
                            text += ` in ${activeStatus.replace('_', ' ')} status`;
                        }
                        text += '.';
                        noResultsQueryText.textContent = text;
                    }
                } else {
                    noResultsRow.style.display = 'none';
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterEmployees);
            searchInput.addEventListener('keyup', function (e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    filterEmployees();
                }
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.focus();
                }
                filterEmployees();
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (searchInput) {
                    searchInput.value = '';
                }
                activeStatus = 'all';
                statusTabs.forEach(tab => {
                    tab.classList.toggle('active', tab.dataset.statusFilter === 'all');
                });
                filterEmployees();
                if (searchInput) {
                    searchInput.focus();
                }
            });
        }

        statusTabs.forEach(tab => {
            tab.addEventListener('click', function () {
                statusTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                activeStatus = this.dataset.statusFilter || 'all';
                filterEmployees();
            });
        });

        // Expose a helper to re-filter when rows change (e.g. after deletion)
        window.refreshEmployeeTableFilter = filterEmployees;
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
            if (typeof window.refreshEmployeeTableFilter === 'function') {
                window.refreshEmployeeTableFilter();
            }

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