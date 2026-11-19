@include('admin.head')

<style>
    /* Custom premium styling for Blog Editor */
    .blog-title-input {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        border: none;
        border-bottom: 2px solid #e2e8f0;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        transition: all 0.3s ease;
        background: transparent;
    }
    .blog-title-input:focus {
        box-shadow: none;
        border-color: #3f51b5;
        background: transparent;
    }
    
    .editor-toolbar {
        background: #f8fafc;
        border: 1px solid #ced4da;
        border-bottom: none;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 8px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .editor-btn {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 4px;
        color: #495057;
        padding: 5px 10px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .editor-btn:hover {
        background: #e9ecef;
        color: #3f51b5;
        border-color: #adb5bd;
    }
    
    .editor-textarea {
        border: 1px solid #ced4da;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        padding: 15px;
        font-size: 1rem;
        line-height: 1.6;
        min-height: 350px;
        color: #495057;
        resize: vertical;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .editor-textarea:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
    }
    
    /* Dropzone Custom Styling */
    .image-dropzone {
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .image-dropzone:hover {
        border-color: #3f51b5;
        background: #f1f3f9;
    }
    
    .image-preview-container {
        display: none;
        width: 100%;
        height: 180px;
        border-radius: 6px;
        background-size: cover;
        background-position: center;
        position: relative;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .remove-image-btn:hover {
        background: rgba(220, 53, 69, 1);
    }
    
    /* SEO Search Snippet Preview */
    .google-preview-card {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .google-preview-title {
        color: #1a0dab;
        font-size: 19px;
        line-height: 1.3;
        margin-bottom: 3px;
        font-family: Arial, sans-serif;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .google-preview-title:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    
    .google-preview-url {
        color: #202124;
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 4px;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .google-preview-description {
        color: #4d5156;
        font-size: 14px;
        line-height: 1.58;
        font-family: Arial, sans-serif;
        word-wrap: break-word;
    }
    
    /* Tags styling */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .tag-badge {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: scaleIn 0.2s ease;
    }
    
    .tag-badge .remove-tag {
        cursor: pointer;
        color: #6c757d;
        font-weight: bold;
        transition: color 0.2s ease;
    }
    .tag-badge .remove-tag:hover {
        color: #dc3545;
    }
    
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    /* Custom button styling */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #2196f3 0%, #3f51b5 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        transform: translateY(-1px);
    }
    
    .btn-gradient-secondary {
        background: #ffffff;
        color: #495057;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .btn-gradient-secondary:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #b1b5ba;
    }
    
    /* Character counts */
    .char-counter {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
        margin-top: 4px;
        display: block;
    }
    
    /* Status Badge styling */
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .status-draft { background-color: #f7b924; }
    .status-published { background-color: #3ac47d; }
    .status-scheduled { background-color: #16aaff; }

    .badge-success {
        background-color: #099244 !important;
    }

    .badge-debit {
        background-color: #dc3545 !important;
    }

    .badge-warning {
        background-color: #ffc107 !important;
    }

    .badge-pending {
        background-color: #f7b924 !important;
    }

    .modal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* ── Premium Transaction Modal ── */
    .txn-modal .modal-dialog {
        max-width: 620px;
    }
    .txn-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.22);
    }
    .txn-modal-header {
        background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 60%, #a855f7 100%);
        padding: 24px 28px 20px;
        position: relative;
    }
    .txn-modal-header .close-btn {
        position: absolute;
        top: 14px;
        right: 16px;
        background: rgba(255,255,255,.15);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        color: #fff;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .2s;
    }
    .txn-modal-header .close-btn:hover { background: rgba(255,255,255,.28); }
    .txn-modal-header .ref-label {
        color: rgba(255,255,255,.7);
        font-size: .75rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .txn-modal-header .ref-value {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: .04em;
    }
    .txn-modal-header .amount-display {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        margin-top: 10px;
    }
    .txn-modal-header .amount-display small {
        font-size: 1rem;
        font-weight: 500;
        opacity: .8;
        margin-left: 4px;
    }
    .txn-badge-row {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }
    .txn-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .txn-badge-credit  { background: rgba(52,211,153,.22); color: #6ee7b7; border: 1px solid rgba(52,211,153,.35); }
    .txn-badge-debit   { background: rgba(248,113,113,.22); color: #fca5a5; border: 1px solid rgba(248,113,113,.35); }
    .txn-badge-type    { background: rgba(255,255,255,.15); color: #e9d5ff; border: 1px solid rgba(255,255,255,.2); }
    .txn-badge-success { background: rgba(52,211,153,.22); color: #6ee7b7; border: 1px solid rgba(52,211,153,.35); }
    .txn-badge-pending { background: rgba(251,191,36,.22); color: #fde68a; border: 1px solid rgba(251,191,36,.35); }
    .txn-badge-failed  { background: rgba(248,113,113,.22); color: #fca5a5; border: 1px solid rgba(248,113,113,.35); }
    /* Tabs */
    .txn-tabs {
        display: flex;
        background: #f8f6ff;
        border-bottom: 1px solid #e8e0f8;
    }
    .txn-tab-btn {
        flex: 1;
        padding: 11px 6px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        font-size: .78rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all .2s;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .txn-tab-btn:hover { color: #7c3aed; }
    .txn-tab-btn.active { color: #7c3aed; border-bottom-color: #7c3aed; background: #fff; }
    .txn-tab-pane { display: none; padding: 20px 24px; }
    .txn-tab-pane.active { display: block; }
    /* Row items */
    .txn-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f3f0ff;
        gap: 12px;
    }
    .txn-row:last-child { border-bottom: none; }
    .txn-row-label {
        color: #9ca3af;
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .txn-row-value {
        color: #1f2937;
        font-size: .88rem;
        font-weight: 600;
        text-align: right;
        word-break: break-all;
    }
    .txn-row-value .pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
    }
    .pill-success { background: #d1fae5; color: #065f46; }
    .pill-warning { background: #fef3c7; color: #92400e; }
    .pill-danger  { background: #fee2e2; color: #991b1b; }
    .pill-credit  { background: #ede9fe; color: #5b21b6; }
    .pill-debit   { background: #fee2e2; color: #991b1b; }
    .pill-neutral { background: #f3f4f6; color: #374151; }
    .txn-section-title {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #7c3aed;
        margin: 0 0 8px;
        padding-bottom: 6px;
        border-bottom: 2px solid #ede9fe;
    }
    .txn-modal-footer {
        padding: 14px 24px;
        background: #fafafa;
        border-top: 1px solid #e8e0f8;
        display: flex;
        justify-content: flex-end;
    }
    .txn-close-btn {
        background: linear-gradient(135deg, #5b21b6, #7c3aed);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 28px;
        font-size: .88rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
    }
    .txn-close-btn:hover { opacity: .88; }
    .webhook-metric {
        height: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        padding: 18px;
    }
    .webhook-metric-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .webhook-metric-value {
        display: block;
        margin-top: 6px;
        color: #0f172a;
        font-size: 26px;
        font-weight: 800;
    }
    .webhook-detail {
        max-width: 420px;
        white-space: normal;
    }
    .webhook-detail pre {
        max-height: 220px;
        overflow: auto;
        margin: 10px 0;
        padding: 12px;
        border-radius: 8px;
        background: #0f172a;
        color: #e2e8f0;
        font-size: 11px;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }
    .business-history-hero {
        position: relative;
        overflow: hidden;
        border: 0;
        border-radius: 18px;
        background: linear-gradient(120deg, #17113b 0%, #38226f 58%, #6141a5 100%);
        color: #fff;
        box-shadow: 0 14px 36px rgba(49, 30, 101, .2);
    }
    .business-history-hero:after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        top: -150px;
        right: 7%;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255,255,255,.035), 0 0 0 70px rgba(255,255,255,.025);
        pointer-events: none;
    }
    .business-history-hero .card-body { position: relative; z-index: 1; padding: 28px; }
    .business-history-eyebrow {
        color: #d7cafa;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .13em;
        text-transform: uppercase;
    }
    .business-history-title { margin: 6px 0; font-size: 25px; font-weight: 800; }
    .business-history-meta { color: #e4dcf8; overflow-wrap: anywhere; }
    .business-history-stat {
        height: 100%;
        padding: 17px 18px;
        border: 1px solid #e8eaf0;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 5px 16px rgba(15, 23, 42, .035);
    }
    .business-history-stat-label {
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .business-history-stat-value {
        display: block;
        margin-top: 5px;
        color: #172033;
        font-size: 25px;
        font-weight: 800;
        line-height: 1.15;
    }
    .business-history-stat-note { display: block; margin-top: 5px; color: #8490a2; font-size: 12px; }
    .business-webhook-routes {
        overflow: hidden;
        border: 1px solid #e6e9f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .04);
    }
    .business-webhook-routes-header { padding: 17px 20px; border-bottom: 1px solid #edf0f5; }
    .business-webhook-route-card { height: 100%; padding: 17px; border: 1px solid #e8ebf1; border-radius: 12px; background: #fbfcfe; }
    .business-webhook-route-title { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 13px; color: #263248; font-size: 14px; font-weight: 800; }
    .business-webhook-route-label { display: block; margin: 11px 0 5px; color: #768398; font-size: 10px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .business-webhook-route-value {
        display: block;
        min-height: 42px;
        padding: 10px 11px;
        border: 1px solid #e6eaf0;
        border-radius: 8px;
        background: #fff;
        color: #344054;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12px;
        overflow-wrap: anywhere;
    }
    .business-webhook-route-empty { color: #98a2b3; font-family: inherit; font-style: italic; }
    .webhook-panel {
        border: 1px solid #e6e9f0;
        border-radius: 18px;
        box-shadow: 0 8px 26px rgba(15, 23, 42, .06);
    }
    .webhook-panel > .card-header { padding: 18px 22px; border-bottom: 1px solid #edf0f5; background: #fff; }
    .webhook-panel > .card-body { padding: 22px; }
    .webhook-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: 9px; }
    .webhook-filter.active { color: #fff; background: #5b3a9d; border-color: #5b3a9d; }
    .webhook-search { width: min(100%, 300px); }
    .webhook-event-list { display: grid; gap: 14px; }
    .webhook-event-card { overflow: hidden; border: 1px solid #e5e9f1; border-radius: 14px; background: #fff; }
    .webhook-event-head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; padding: 16px 18px; background: #fbfcfe; }
    .webhook-event-title { color: #172033; font-size: 15px; font-weight: 800; }
    .webhook-event-subtitle { margin-top: 4px; color: #718096; font-size: 12px; overflow-wrap: anywhere; }
    .webhook-event-badges { display: flex; align-items: flex-start; flex-wrap: wrap; gap: 6px; }
    .webhook-event-body { padding: 16px 18px; }
    .webhook-facts { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .webhook-fact { min-width: 0; }
    .webhook-fact-label { display: block; color: #8490a2; font-size: 10px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .webhook-fact-value { display: block; margin-top: 3px; color: #263248; font-size: 13px; font-weight: 700; overflow-wrap: anywhere; }
    .webhook-event-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 15px; }
    .webhook-event-details { padding: 0 18px 17px; }
    .webhook-event-details summary { color: #54368f; cursor: pointer; font-size: 13px; font-weight: 700; }
    .webhook-detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-top: 12px; }
    .webhook-json-block { min-width: 0; }
    .webhook-json-block pre { max-height: 280px; overflow: auto; margin: 6px 0 0; padding: 13px; border-radius: 9px; background: #111827; color: #e2e8f0; font-size: 11px; white-space: pre-wrap; overflow-wrap: anywhere; }
    .webhook-attempt-list { display: grid; gap: 8px; margin-top: 12px; }
    .webhook-attempt { padding: 11px 12px; border: 1px solid #e7eaf0; border-radius: 9px; background: #fafbfc; }
    .webhook-attempt-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 6px; font-size: 12px; font-weight: 700; }
    .webhook-attempt-response { margin-top: 7px; color: #64748b; font-size: 12px; overflow-wrap: anywhere; }
    .webhook-empty { padding: 42px 18px; border: 1px dashed #cfd6e2; border-radius: 14px; color: #718096; text-align: center; }
    @media (max-width: 767.98px) {
        .business-history-hero .card-body { padding: 22px; }
        .business-history-title { font-size: 21px; }
        .webhook-panel > .card-header, .webhook-panel > .card-body { padding: 16px; }
        .webhook-facts { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .webhook-detail-grid { grid-template-columns: 1fr; }
        .webhook-search { width: 100%; }
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

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif

                    <section class="business-history-hero card mb-4">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <span class="business-history-eyebrow">Admin · Business account</span>
                                <h1 class="business-history-title">{{ $user->business_name ?: ($user->name ?? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''))) }} transaction overview</h1>
                                <div class="business-history-meta">{{ $user->email }} <span class="mx-2">·</span> Account #{{ $user->id }}</div>
                            </div>
                            <div class="text-right mt-3 mt-md-0">
                                <span class="d-block business-history-eyebrow">Transaction monitoring</span>
                                <span class="business-history-meta small">Live records · webhook activity</span>
                            </div>
                        </div>
                    </section>

                    <section class="business-webhook-routes mb-4" aria-labelledby="businessWebhookRoutesTitle">
                        <div class="business-webhook-routes-header d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <h2 id="businessWebhookRoutesTitle" class="h5 mb-1 font-weight-bold">
                                    <i class="fa-solid fa-diagram-project text-primary mr-2"></i>Business collection &amp; payout routes
                                </h2>
                                <small class="text-muted">Configured webhook destinations for this business. Routes are shown separately for live and test mode.</small>
                            </div>
                            @if(!$webhookRoutes['available'])
                                <span class="badge badge-secondary mt-2 mt-md-0">Route settings unavailable</span>
                            @endif
                        </div>
                        <div class="p-3 p-md-4">
                            <div class="row">
                                @foreach(['live' => 'Live routes', 'test' => 'Test routes'] as $mode => $modeLabel)
                                    <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                                        <div class="business-webhook-route-card">
                                            <div class="business-webhook-route-title">
                                                <span><i class="fa-solid {{ $mode === 'live' ? 'fa-circle-check text-success' : 'fa-flask text-primary' }} mr-2"></i>{{ $modeLabel }}</span>
                                                <span class="badge {{ $mode === 'live' ? 'badge-success' : 'badge-info' }}">{{ strtoupper($mode) }}</span>
                                            </div>
                                            <span class="business-webhook-route-label">Collection URL</span>
                                            <span class="business-webhook-route-value {{ empty($webhookRoutes[$mode]['collection']) ? 'business-webhook-route-empty' : '' }}">{{ $webhookRoutes[$mode]['collection'] ?: 'Not configured' }}</span>
                                            <span class="business-webhook-route-label">Payout URL</span>
                                            <span class="business-webhook-route-value {{ empty($webhookRoutes[$mode]['payout']) ? 'business-webhook-route-empty' : '' }}">{{ $webhookRoutes[$mode]['payout'] ?: 'Not configured' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <div class="row mb-4">
                        <div class="col-6 col-xl mb-3">
                            <div class="business-history-stat"><span class="business-history-stat-label">Transactions</span><strong class="business-history-stat-value">{{ number_format($transactionSummary['total']) }}</strong><span class="business-history-stat-note">All live records</span></div>
                        </div>
                        <div class="col-6 col-xl mb-3">
                            <div class="business-history-stat"><span class="business-history-stat-label">Successful</span><strong class="business-history-stat-value text-success">{{ number_format($transactionSummary['successful']) }}</strong><span class="business-history-stat-note">Completed transactions</span></div>
                        </div>
                        <div class="col-6 col-xl mb-3">
                            <div class="business-history-stat"><span class="business-history-stat-label">Pending</span><strong class="business-history-stat-value text-warning">{{ number_format($transactionSummary['pending']) }}</strong><span class="business-history-stat-note">Awaiting a final status</span></div>
                        </div>
                        <div class="col-6 col-xl mb-3">
                            <div class="business-history-stat"><span class="business-history-stat-label">Failed</span><strong class="business-history-stat-value text-danger">{{ number_format($transactionSummary['failed']) }}</strong><span class="business-history-stat-note">Failed or declined</span></div>
                        </div>
                        <div class="col-6 col-xl mb-3">
                            <div class="business-history-stat"><span class="business-history-stat-label">API sourced</span><strong class="business-history-stat-value text-primary">{{ number_format($transactionSummary['api']) }}</strong><span class="business-history-stat-note">{{ $transactionSummary['api_source_available'] ? 'Created through the API' : 'Source tracking is not available' }}</span></div>
                        </div>
                    </div>

                    <section class="mb-5" id="businessWebhookActivity" data-url="{{ route('admin.alltransactions.webhook-activity', $user->id) }}">
                        <div class="main-card card webhook-panel">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                        <i class="fa-solid fa-tower-broadcast text-primary mr-2"></i>Business Webhook Activity
                                    </div>
                                    <small class="text-muted">Inspect callback events, endpoint delivery results, payloads, and retry responses. Refreshes every 15 seconds.</small>
                                </div>
                                <div class="d-flex align-items-center mt-2 mt-md-0">
                                    <span id="webhookActivityUpdated" class="small text-muted mr-3">Loading activity…</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="refreshWebhookActivity">
                                        <i class="fa-solid fa-rotate"></i> Refresh
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="webhookActivityNotice" class="alert alert-info py-2 d-none" role="status"></div>
                                <div class="row mb-3">
                                    <div class="col-6 col-lg">
                                        <div class="webhook-metric"><span class="webhook-metric-label">Events</span><strong id="webhookCountEvents" class="webhook-metric-value">—</strong><small class="text-muted">Recorded callbacks</small></div>
                                    </div>
                                    <div class="col-6 col-lg">
                                        <div class="webhook-metric"><span class="webhook-metric-label">Delivered</span><strong id="webhookCountSuccessful" class="webhook-metric-value text-success">—</strong><small class="text-muted">Successful deliveries</small></div>
                                    </div>
                                    <div class="col-6 col-lg">
                                        <div class="webhook-metric"><span class="webhook-metric-label">Failed</span><strong id="webhookCountFailed" class="webhook-metric-value text-danger">—</strong><small class="text-muted">Undelivered events</small></div>
                                    </div>
                                    <div class="col-6 col-lg">
                                        <div class="webhook-metric"><span class="webhook-metric-label">Pending</span><strong id="webhookCountPending" class="webhook-metric-value text-warning">—</strong><small class="text-muted">Awaiting delivery</small></div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        <div class="webhook-metric"><span class="webhook-metric-label">Delivery attempts</span><strong id="webhookCountAttempts" class="webhook-metric-value text-primary">—</strong><small class="text-muted">Across all callbacks</small></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                    <p id="webhookTransactionStatusCounts" class="small text-muted mb-2 mb-md-0"></p>
                                    <div class="webhook-toolbar">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Filter webhook activity">
                                            <button type="button" class="btn btn-outline-secondary webhook-filter active" data-filter="all" aria-pressed="true">All</button>
                                            <button type="button" class="btn btn-outline-secondary webhook-filter" data-filter="successful" aria-pressed="false">Delivered</button>
                                            <button type="button" class="btn btn-outline-secondary webhook-filter" data-filter="failed" aria-pressed="false">Failed</button>
                                            <button type="button" class="btn btn-outline-secondary webhook-filter" data-filter="pending" aria-pressed="false">Pending</button>
                                        </div>
                                        <input id="webhookActivitySearch" type="search" class="form-control form-control-sm webhook-search" placeholder="Search reference, event, or endpoint" aria-label="Search webhook events">
                                    </div>
                                </div>
                                <div id="webhookActivityRows" class="webhook-event-list" aria-live="polite">
                                    <div class="webhook-empty">Loading webhook activity…</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mb-5">
                        <div class="main-card mb-3 card">
                            <div class="card-header">
                                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                    <i class="fa-solid fa-note-sticky icon-gradient bg-ripe-malin"></i>
                                    Recent transactions
                                </div>
                                <div class="btn-actions-pane-right">
                                    <form action="{{ route('admin.alltransactions.history', $user->id) }}" method="GET">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="form-control">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Reference</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Source</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Fee charged</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $key => $transaction)
                                        @php($displayType = $transaction->display_type)
                                        <tr>
                                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left flex2">
                                                            <div class="widget-heading">{{ $transaction->reference ?? 'N/A' }}</div>
                                                            <div class="widget-subheading opacity-7">{{ $transaction->payment_provider ?? $transaction->method ?? 'Transfer' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if($displayType === 'Collection' || strtolower($transaction->type ?? '') === 'credit')
                                                    <div class="badge badge-success">{{ $displayType }}</div>
                                                @elseif($displayType === 'Payout' || strtolower($transaction->type ?? '') === 'debit')
                                                    <div class="badge badge-danger">{{ $displayType }}</div>
                                                @else
                                                    <div class="badge badge-info">{{ $displayType }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($transaction->api_source)
                                                    <span class="badge badge-info">API</span>
                                                @else
                                                    <span class="badge badge-secondary">Normal</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="font-weight-bold">
                                                    {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if((float) ($transaction->fees ?? 0) > 0)
                                                    <div class="font-weight-bold text-danger">
                                                        {{ number_format((float) $transaction->fees, 2) }} {{ strtoupper($transaction->currency ?? '') }}
                                                    </div>
                                                    <small class="text-muted">Fixed/percentage fee</small>
                                                @else
                                                    <span class="text-muted">0.00 {{ strtoupper($transaction->currency ?? '') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(in_array(strtolower($transaction->status), ['completed', 'success', 'successful']))
                                                    <div class="badge badge-success">{{ ucfirst($transaction->status) }}</div>
                                                @elseif(in_array(strtolower($transaction->status), ['pending', 'processing']))
                                                    <div class="badge badge-warning">{{ ucfirst($transaction->status) }}</div>
                                                @else
                                                    <div class="badge badge-danger">{{ ucfirst($transaction->status) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $transaction->created_at->format('M d, Y') }}</span><br>
                                                <span class="text-muted" style="font-size: 0.85em;">{{ $transaction->created_at->format('h:i A') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transactionModal{{ $transaction->id }}">
                                                    Details
                                                </button>
                                                @if($transaction->api_source && in_array(strtolower((string) $transaction->status), ['success', 'successful', 'completed'], true) && in_array(strtolower((string) $displayType), ['collection', 'payout'], true))
                                                    <form action="{{ route('admin.alltransactions.send-webhook', ['user' => $user->id, 'transaction' => $transaction->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Send this successful transaction to the business client webhook now?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm mt-1">
                                                            <i class="fa-solid fa-paper-plane"></i> Send webhook
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted" style="font-size: 1.2rem;">
                                                    <i class="fa-solid fa-circle-info d-block mb-2" style="font-size: 2rem;"></i>
                                                    No transactions found for this user.
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($transactions->hasPages())
                            <div class="d-block p-4 text-center card-footer d-flex justify-content-center">
                                {{ $transactions->links('pagination::bootstrap-4') }}
                            </div>
                            @endif
                        </div>
                    </section>

                </div>
            </div>
        </div>

    </div>




    <!-- Transaction Modals (Redesigned) -->
    @foreach($transactions as $transaction)
        <div class="modal fade txn-modal" id="transactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="txnLabel{{ $transaction->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered shadow-none modal-dialog-scrollable">
                <div class="modal-content shadow-none">

                    {{-- ── Gradient Header ── --}}
                    <div class="txn-modal-header">
                        <button class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="ref-label">Transaction Reference</div>
                        <div class="ref-value">{{ $transaction->reference ?? '—' }}</div>
                        <div class="amount-display">
                            {{ number_format($transaction->amount, 2) }}<small>{{ strtoupper($transaction->currency ?? '') }}</small>
                        </div>
                        <div class="txn-badge-row">
                            <span class="txn-badge {{ in_array(strtolower($transaction->display_type), ['credit', 'collection'], true) ? 'txn-badge-credit' : (in_array(strtolower($transaction->display_type), ['debit', 'payout', 'withdrawal'], true) ? 'txn-badge-debit' : 'txn-badge-type') }}">
                                <i class="fa-solid fa-{{ in_array(strtolower($transaction->display_type), ['credit', 'collection'], true) ? 'arrow-down-left' : (strtolower($transaction->display_type) === 'swap' ? 'exchange-alt' : 'arrow-up-right') }}"></i>
                                {{ $transaction->display_type }}
                            </span>
                            <span class="txn-badge {{ in_array(strtolower($transaction->status ?? ''), ['completed', 'success', 'successful'], true) ? 'txn-badge-success' : (in_array(strtolower($transaction->status ?? ''), ['pending', 'processing'], true) ? 'txn-badge-pending' : 'txn-badge-failed') }}">
                                <i class="fa-solid fa-circle" style="font-size:.5rem;"></i>
                                {{ ucfirst($transaction->status ?? 'N/A') }}
                            </span>
                            @if($transaction->method)
                            <span class="txn-badge txn-badge-type">
                                <i class="fa-solid fa-credit-card"></i>
                                {{ ucfirst($transaction->method) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- ── Tab Nav ── --}}
                    <div class="txn-tabs" id="txnTabNav{{ $transaction->id }}">
                        <button class="txn-tab-btn active" data-tab="overview-{{ $transaction->id }}">
                            <i class="fa-solid fa-chart-pie me-1"></i>Overview
                        </button>
                        <button class="txn-tab-btn" data-tab="recipient-{{ $transaction->id }}">
                            <i class="fa-solid fa-user me-1"></i>Recipient
                        </button>
                        <button class="txn-tab-btn" data-tab="payment-{{ $transaction->id }}">
                            <i class="fa-solid fa-coins me-1"></i>Payment
                        </button>
                        <button class="txn-tab-btn" data-tab="promo-{{ $transaction->id }}">
                            <i class="fa-solid fa-tag me-1"></i>Promo
                        </button>
                    </div>

                    {{-- ── Tab Panes ── --}}
                    <div>
                        {{-- Overview --}}
                        <div class="txn-tab-pane active" id="overview-{{ $transaction->id }}">
                            <p class="txn-section-title">Core Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Reference</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->reference ?? '—' }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Amount</span>
                                <span class="txn-row-value">{{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @if($transaction->recipient_amount)
                            <div class="txn-row">
                                <span class="txn-row-label">Recipient Amount</span>
                                <span class="txn-row-value">{{ number_format($transaction->recipient_amount, 2) }} {{ strtoupper($transaction->to_currency ?? $transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            @if($transaction->total_amount)
                            <div class="txn-row">
                                <span class="txn-row-label">Total Charged</span>
                                <span class="txn-row-value">{{ number_format($transaction->total_amount, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            @if($transaction->fees)
                            <div class="txn-row">
                                <span class="txn-row-label">Fees</span>
                                <span class="txn-row-value">{{ number_format($transaction->fees, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Type</span>
                                <span class="txn-row-value"><span class="pill {{ in_array(strtolower($transaction->display_type), ['credit', 'collection'], true) ? 'pill-credit' : (in_array(strtolower($transaction->display_type), ['debit', 'payout', 'withdrawal'], true) ? 'pill-debit' : 'pill-neutral') }}">{{ $transaction->display_type }}</span></span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Status</span>
                                <span class="txn-row-value"><span class="pill {{ in_array(strtolower($transaction->status ?? ''), ['completed', 'success', 'successful'], true) ? 'pill-success' : (in_array(strtolower($transaction->status ?? ''), ['pending', 'processing'], true) ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst($transaction->status ?? '—') }}</span></span>
                            </div>
                            @if($transaction->failure_reason)
                            <div class="txn-row">
                                <span class="txn-row-label">Failure Reason</span>
                                <span class="txn-row-value" style="color:#dc2626;">{{ $transaction->failure_reason }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Date &amp; Time</span>
                                <span class="txn-row-value">{{ $transaction->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $transaction->created_at->format('h:i:s A') }}</small></span>
                            </div>
                            @if($transaction->created_at_external)
                            <div class="txn-row">
                                <span class="txn-row-label">External Date</span>
                                <span class="txn-row-value">{{ $transaction->created_at_external }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Recipient --}}
                        <div class="txn-tab-pane" id="recipient-{{ $transaction->id }}">
                            <p class="txn-section-title">Sender</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Sender</span>
                                <span class="txn-row-value">{{ $transaction->sender ?? '—' }}</span>
                            </div>
                            <p class="txn-section-title mt-3">Recipient</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Name</span>
                                <span class="txn-row-value">{{ $transaction->recipient_account_name ?? $transaction->recipient_alias ?? $transaction->recipient ?? '—' }}</span>
                            </div>
                            @if($transaction->recipient_alias)
                            <div class="txn-row">
                                <span class="txn-row-label">Alias</span>
                                <span class="txn-row-value">{{ $transaction->recipient_alias }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_type)
                            <div class="txn-row">
                                <span class="txn-row-label">Recipient Type</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->recipient_type) }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_country)
                            <div class="txn-row">
                                <span class="txn-row-label">Country</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->recipient_country) }}</span>
                            </div>
                            @endif
                            <p class="txn-section-title mt-3">Bank Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Name</span>
                                <span class="txn-row-value">{{ $transaction->recipient_bank_name ?? '—' }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Account Number</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->recipient_account_number ?? '—' }}</span>
                            </div>
                            @if($transaction->recipient_sort_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Sort Code</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->recipient_sort_code }}</span>
                            </div>
                            @endif
                            @if($transaction->bank_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Code</span>
                                <span class="txn-row-value">{{ $transaction->bank_code }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_bank_currency)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Currency</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->recipient_bank_currency) }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_default_reference)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Reference</span>
                                <span class="txn-row-value">{{ $transaction->recipient_default_reference }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Payment --}}
                        <div class="txn-tab-pane" id="payment-{{ $transaction->id }}">
                            <p class="txn-section-title">Payment Method</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->method ?? '—') }}</span>
                            </div>
                            @if($transaction->transfer_method)
                            <div class="txn-row">
                                <span class="txn-row-label">Transfer Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->transfer_method) }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_method)
                            <div class="txn-row">
                                <span class="txn-row-label">Payment Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->payment_method) }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_provider)
                            <div class="txn-row">
                                <span class="txn-row-label">Provider</span>
                                <span class="txn-row-value">{{ $transaction->payment_provider }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_reference)
                            <div class="txn-row">
                                <span class="txn-row-label">Payment Ref</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->payment_reference }}</span>
                            </div>
                            @endif
                            @if($transaction->order_id)
                            <div class="txn-row">
                                <span class="txn-row-label">Order ID</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->order_id }}</span>
                            </div>
                            @endif
                            @if($transaction->exchange_rate)
                            <p class="txn-section-title mt-3">Exchange</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Exchange Rate</span>
                                <span class="txn-row-value">{{ $transaction->exchange_rate }}</span>
                            </div>
                            @if($transaction->single_rate)
                            <div class="txn-row">
                                <span class="txn-row-label">Single Rate</span>
                                <span class="txn-row-value">{{ $transaction->single_rate }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">To Currency</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->to_currency ?? '—') }}</span>
                            </div>
                            @endif
                            @if($transaction->card_number)
                            <p class="txn-section-title mt-3">Card Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Card Number</span>
                                <span class="txn-row-value" style="font-family:monospace;">**** **** **** {{ substr($transaction->card_number, -4) }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Expiry</span>
                                <span class="txn-row-value">{{ $transaction->expiry_month ?? '—' }} / {{ $transaction->expiry_year ?? '—' }}</span>
                            </div>
                            @endif
                            @if($transaction->interac_email)
                            <p class="txn-section-title mt-3">Interac</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Interac Email</span>
                                <span class="txn-row-value">{{ $transaction->interac_email }}</span>
                            </div>
                            @if($transaction->interac_first_name)
                            <div class="txn-row">
                                <span class="txn-row-label">Interac Name</span>
                                <span class="txn-row-value">{{ $transaction->interac_first_name }} {{ $transaction->interac_last_name }}</span>
                            </div>
                            @endif
                            @endif
                        </div>

                        {{-- Promo --}}
                        <div class="txn-tab-pane" id="promo-{{ $transaction->id }}">
                            <p class="txn-section-title">Promo Code</p>
                            @if($transaction->promo_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Code</span>
                                <span class="txn-row-value">
                                    <span class="pill pill-credit" style="font-family:monospace;letter-spacing:.08em;">{{ $transaction->promo_code }}</span>
                                </span>
                            </div>
                            @if($transaction->promo_code_id)
                            <div class="txn-row">
                                <span class="txn-row-label">Code ID</span>
                                <span class="txn-row-value">#{{ $transaction->promo_code_id }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Fee Waived</span>
                                <span class="txn-row-value">
                                    @if($transaction->promo_fee_waived)
                                        <span class="pill pill-success"><i class="fa-solid fa-check"></i> Yes</span>
                                    @else
                                        <span class="pill pill-neutral">No</span>
                                    @endif
                                </span>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-tag" style="font-size:2rem;color:#d8b4fe;"></i>
                                <p class="mt-2 mb-0" style="color:#9ca3af;font-size:.85rem;">No promo code was applied to this transaction.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="txn-modal-footer">
                        <button class="txn-close-btn" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    
    @include('admin.footer')

    <script>
    // Tab switching for transaction modals
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.txn-tab-btn');
        if (!btn) return;
        const tabId = btn.dataset.tab;
        const nav   = btn.closest('.txn-tabs');
        const modal = btn.closest('.modal-content');
        nav.querySelectorAll('.txn-tab-btn').forEach(b => b.classList.remove('active'));
        modal.querySelectorAll('.txn-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const pane = modal.querySelector('#' + tabId);
        if (pane) pane.classList.add('active');
    });

    // Reset tab to Overview when modal opens
    document.querySelectorAll('.txn-modal').forEach(function (modal) {
        modal.addEventListener('show.bs.modal', function () {
            modal.querySelectorAll('.txn-tab-btn').forEach((b, i) => b.classList.toggle('active', i === 0));
            modal.querySelectorAll('.txn-tab-pane').forEach((p, i) => p.classList.toggle('active', i === 0));
        });
    });
    </script>
    <script>
    (() => {
        const panel = document.getElementById('businessWebhookActivity');
        if (!panel) return;

        const endpoint = panel.dataset.url;
        const eventList = document.getElementById('webhookActivityRows');
        const notice = document.getElementById('webhookActivityNotice');
        const updated = document.getElementById('webhookActivityUpdated');
        const searchInput = document.getElementById('webhookActivitySearch');
        const transactionStatuses = document.getElementById('webhookTransactionStatusCounts');
        const metricIds = {
            events: 'webhookCountEvents',
            successful: 'webhookCountSuccessful',
            failed: 'webhookCountFailed',
            pending: 'webhookCountPending',
            attempts: 'webhookCountAttempts',
        };
        let allEvents = [];
        let activeFilter = 'all';

        const text = (value, fallback = '—') => (
            value === null || value === undefined || value === '' ? fallback : String(value)
        );
        const formatDate = (value) => {
            if (!value) return '—';
            const date = new Date(value);
            return Number.isNaN(date.getTime()) ? String(value) : date.toLocaleString();
        };
        const formatJson = (value) => {
            if (value === null || value === undefined || value === '') return 'No response recorded';
            if (typeof value === 'string') {
                try { return JSON.stringify(JSON.parse(value), null, 2); }
                catch { return value; }
            }
            return JSON.stringify(value, null, 2);
        };

        const statusBadge = (value) => {
            const badge = document.createElement('span');
            const normalized = String(value || 'unknown').toLowerCase();
            badge.className = 'badge ' + (
                ['successful', 'success'].includes(normalized) ? 'badge-success' :
                ['failed', 'declined'].includes(normalized) ? 'badge-danger' :
                'badge-warning'
            );
            badge.textContent = value || 'Unknown';
            return badge;
        };

        const addFact = (container, label, value) => {
            const fact = document.createElement('div');
            fact.className = 'webhook-fact';
            const title = document.createElement('span');
            title.className = 'webhook-fact-label';
            title.textContent = label;
            const content = document.createElement('span');
            content.className = 'webhook-fact-value';
            content.textContent = text(value);
            fact.append(title, content);
            container.appendChild(fact);
        };

        const addJsonBlock = (container, title, value) => {
            const block = document.createElement('div');
            block.className = 'webhook-json-block';
            const heading = document.createElement('strong');
            heading.textContent = title;
            const pre = document.createElement('pre');
            pre.textContent = formatJson(value);
            block.append(heading, pre);
            container.appendChild(block);
        };

        const createEventCard = (event) => {
            const card = document.createElement('article');
            card.className = 'webhook-event-card';

            const head = document.createElement('div');
            head.className = 'webhook-event-head';
            const titleBlock = document.createElement('div');
            const title = document.createElement('div');
            title.className = 'webhook-event-title';
            title.textContent = text(event.event || event.type, 'Webhook event');
            const subtitle = document.createElement('div');
            subtitle.className = 'webhook-event-subtitle';
            subtitle.textContent = `${text(event.reference, 'No transaction reference')} · ${formatDate(event.created_at)}`;
            titleBlock.append(title, subtitle);

            const badges = document.createElement('div');
            badges.className = 'webhook-event-badges';
            badges.append(statusBadge(event.transaction_status), statusBadge(event.delivery_status));
            const mode = document.createElement('span');
            mode.className = 'badge badge-light text-uppercase';
            mode.textContent = text(event.mode, 'live');
            badges.appendChild(mode);
            head.append(titleBlock, badges);
            card.appendChild(head);

            const body = document.createElement('div');
            body.className = 'webhook-event-body';
            const facts = document.createElement('div');
            facts.className = 'webhook-facts';
            addFact(facts, 'Event ID', event.event_id || event.id);
            addFact(facts, 'Transaction ID', event.transaction_id);
            addFact(facts, 'Transaction amount', event.amount === null || event.amount === undefined ? '—' : `${event.amount} ${event.currency || ''}`.trim());
            addFact(facts, 'Amount before fee', event.amount_without_fee === null || event.amount_without_fee === undefined ? '—' : `${event.amount_without_fee} ${event.currency || ''}`.trim());
            addFact(facts, 'Transaction fee', event.fee === null || event.fee === undefined ? '—' : `${event.fee} ${event.currency || ''}`.trim());
            addFact(facts, 'Delivery attempts', Array.isArray(event.attempts) ? event.attempts.length : 0);
            addFact(facts, 'Endpoint', event.url);
            addFact(facts, 'Recipient', event.recipient && (event.recipient.account_name || event.recipient.bank_name || event.recipient.account_number)
                ? [event.recipient.account_name, event.recipient.bank_name, event.recipient.account_number].filter(Boolean).join(' · ')
                : 'Not included');
            body.appendChild(facts);

            const details = document.createElement('details');
            details.className = 'webhook-event-details';
            const summary = document.createElement('summary');
            summary.textContent = 'Inspect payload, latest response, and attempt history';
            details.appendChild(summary);

            const detailGrid = document.createElement('div');
            detailGrid.className = 'webhook-detail-grid';
            addJsonBlock(detailGrid, 'Sent payload', event.payload);
            addJsonBlock(detailGrid, 'Latest endpoint response', event.response);
            details.appendChild(detailGrid);

            const attemptsHeading = document.createElement('strong');
            attemptsHeading.className = 'd-block mt-3';
            attemptsHeading.textContent = 'Delivery attempt history';
            details.appendChild(attemptsHeading);

            const attempts = document.createElement('div');
            attempts.className = 'webhook-attempt-list';
            if (!Array.isArray(event.attempts) || event.attempts.length === 0) {
                const emptyAttempt = document.createElement('div');
                emptyAttempt.className = 'small text-muted';
                emptyAttempt.textContent = 'No delivery attempt details have been recorded.';
                attempts.appendChild(emptyAttempt);
            } else {
                event.attempts.forEach((attempt) => {
                    const item = document.createElement('div');
                    item.className = 'webhook-attempt';
                    const top = document.createElement('div');
                    top.className = 'webhook-attempt-top';
                    const number = document.createElement('span');
                    number.textContent = `Attempt ${text(attempt.attempt, '—')} · HTTP ${text(attempt.http_status, 'no response')}`;
                    const time = document.createElement('span');
                    time.className = 'text-muted';
                    time.textContent = formatDate(attempt.created_at);
                    top.append(number, time);
                    const attemptUrl = document.createElement('div');
                    attemptUrl.className = 'webhook-attempt-response';
                    attemptUrl.textContent = `Endpoint: ${text(attempt.url || event.url)}`;
                    const responseText = document.createElement('div');
                    responseText.className = 'webhook-attempt-response';
                    responseText.textContent = `Response: ${formatJson(attempt.response)}`;
                    item.append(top, attemptUrl, responseText);
                    attempts.appendChild(item);
                });
            }
            details.appendChild(attempts);
            card.append(body, details);
            return card;
        };

        const renderEvents = () => {
            const query = searchInput.value.trim().toLowerCase();
            const visibleEvents = allEvents.filter((event) => {
                const deliveryStatus = String(event.delivery_status || 'pending').toLowerCase();
                const normalizedDeliveryStatus = ['success', 'successfully_delivered'].includes(deliveryStatus)
                    ? 'successful'
                    : deliveryStatus;
                const matchesFilter = activeFilter === 'all' || normalizedDeliveryStatus === activeFilter;
                const searchText = [
                    event.event, event.type, event.reference, event.url, event.event_id, event.transaction_id,
                    event.transaction_status, event.delivery_status,
                ].filter(Boolean).join(' ').toLowerCase();
                return matchesFilter && (!query || searchText.includes(query));
            });

            eventList.replaceChildren();
            if (!visibleEvents.length) {
                const empty = document.createElement('div');
                empty.className = 'webhook-empty';
                empty.textContent = allEvents.length
                    ? 'No webhook events match the current filter or search.'
                    : 'No webhook events have been recorded for this business yet.';
                eventList.appendChild(empty);
                return;
            }
            visibleEvents.forEach((event) => eventList.appendChild(createEventCard(event)));
        };

        const loadActivity = async () => {
            try {
                const response = await fetch(endpoint, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });
                if (!response.ok) throw new Error(`Request failed (${response.status})`);
                const result = await response.json();

                if (!result.available) {
                    notice.textContent = result.message;
                    notice.classList.remove('d-none');
                    allEvents = [];
                    renderEvents();
                    return;
                }

                notice.classList.add('d-none');
                Object.entries(metricIds).forEach(([key, elementId]) => {
                    document.getElementById(elementId).textContent = result.counts[key] ?? 0;
                });
                const transactionStatuses = Object.entries(result.counts.transaction_statuses || {})
                    .map(([status, count]) => `${status || 'Unknown'}: ${count}`)
                    .join(' · ');
                document.getElementById('webhookTransactionStatusCounts').textContent =
                    transactionStatuses ? `Transaction statuses in sent events: ${transactionStatuses}` : 'No transaction status events yet.';
                allEvents = result.events || [];
                renderEvents();
                updated.textContent = `Updated ${new Date().toLocaleTimeString()}`;
            } catch (error) {
                notice.textContent = `Could not refresh webhook activity: ${error.message}`;
                notice.classList.remove('d-none');
                updated.textContent = 'Refresh failed';
            }
        };

        document.getElementById('refreshWebhookActivity').addEventListener('click', loadActivity);
        searchInput.addEventListener('input', renderEvents);
        document.querySelectorAll('.webhook-filter').forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.filter;
                document.querySelectorAll('.webhook-filter').forEach((filterButton) => {
                    filterButton.classList.toggle('active', filterButton === button);
                    filterButton.setAttribute('aria-pressed', filterButton === button ? 'true' : 'false');
                });
                renderEvents();
            });
        });
        loadActivity();
        window.setInterval(loadActivity, 15000);
    })();
    </script>
</body>
</html>
