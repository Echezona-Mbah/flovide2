@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-deep: #1d4ed8;
        --brand-sky: #0ea5e9;
        --paper: #ffffff;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --border-soft: #e2e8f0;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
        --success-soft: rgba(22, 163, 74, 0.12);
        --warning-soft: rgba(245, 158, 11, 0.14);
        --danger-soft: rgba(220, 38, 38, 0.12);
    }

    .dashboard-shell { padding-bottom: 32px; }

    .hero-banner {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.12), transparent 22%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 60%, #0ea5e9 100%);
        box-shadow: 0 24px 55px rgba(29, 78, 216, 0.18);
        margin-bottom: 28px;
    }

    .hero-banner .card-body { padding: 32px 36px; }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .dashboard-card {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: var(--surface);
        box-shadow: var(--shadow-soft);
        margin-bottom: 22px;
    }

    .dashboard-card .card-header {
        border: 0;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dashboard-card .card-body { padding: 24px; }

    .section-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .icon-blue  { background: rgba(37,99,235,0.12);  color: #2563eb; }
    .icon-green { background: rgba(22,163,74,0.12);   color: #16a34a; }
    .icon-amber { background: rgba(245,158,11,0.14);  color: #d97706; }
    .icon-red   { background: rgba(220,38,38,0.12);   color: #dc2626; }
    .icon-slate { background: rgba(100,116,139,0.12); color: #64748b; }

    .section-title { font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 2px; }
    .section-subtitle { font-size: 12px; color: var(--text-soft); }

    .detail-table { width: 100%; }
    .detail-table tr:not(:last-child) { border-bottom: 1px solid var(--border-soft); }
    .detail-table th, .detail-table td { padding: 13px 0; font-size: 14px; vertical-align: top; }
    .detail-table th { color: var(--text-soft); font-weight: 700; width: 200px; }
    .detail-table td { color: var(--text-main); font-weight: 600; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .pill-pending    { background: rgba(245,158,11,0.14); color: #d97706; }
    .pill-processing { background: rgba(14,165,233,0.14); color: #0ea5e9; }
    .pill-approved   { background: rgba(22,163,74,0.12);  color: #16a34a; }
    .pill-rejected   { background: rgba(220,38,38,0.12);  color: #dc2626; }

    .masked-field {
        font-family: monospace;
        background: var(--surface-soft);
        border: 1px solid var(--border-soft);
        border-radius: 10px;
        padding: 8px 14px;
        display: inline-block;
        letter-spacing: 0.08em;
        color: var(--text-main);
    }

    .back-btn {
        border: 0;
        border-radius: 14px;
        padding: 10px 20px;
        font-weight: 700;
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-size: 13px;
        transition: background 0.2s;
    }
    .back-btn:hover { background: rgba(255,255,255,0.22); color: #fff; }
</style>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')

        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')

            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="dashboard-shell">

                        {{-- Hero Banner --}}
                        <div class="card hero-banner">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                    <div>
                                        <div class="hero-pill">
                                            <i class="fa-solid fa-bank"></i>
                                            Bank Account Request #{{ $bankAccountRequest->id }}
                                        </div>
                                        <h2 class="text-white mb-1">
                                            {{ $bankAccountRequest->personal->firstname ?? 'N/A' }}
                                            {{ $bankAccountRequest->personal->lastname ?? '' }}
                                        </h2>
                                        <p class="text-white-50 mb-0">{{ $bankAccountRequest->personal->email ?? '—' }}</p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-2">
                                        @php
                                            $s = $bankAccountRequest->status;
                                            $pillClass = match($s) {
                                                'pending'    => 'pill-pending',
                                                'processing' => 'pill-processing',
                                                'approved'   => 'pill-approved',
                                                'rejected'   => 'pill-rejected',
                                                default      => 'pill-pending',
                                            };
                                        @endphp
                                        <span class="status-pill {{ $pillClass }}">
                                            <i class="fa fa-circle" style="font-size:8px;"></i>
                                            {{ ucfirst($s) }}
                                        </span>
                                        <a href="{{ route('admin.bank-account-requests') }}" class="back-btn">
                                            <i class="fa fa-arrow-left me-1"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">

                            {{-- Request Details --}}
                            <div class="col-lg-7">
                                <div class="dashboard-card">
                                    <div class="card-header">
                                        <div class="section-icon icon-blue">
                                            <i class="fa-solid fa-id-card"></i>
                                        </div>
                                        <div>
                                            <div class="section-title">Request Details</div>
                                            <div class="section-subtitle">Submitted verification information</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="detail-table">
                                            <tr>
                                                <th>Request ID</th>
                                                <td>#{{ $bankAccountRequest->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>BVN</th>
                                                <td>
                                                    <span class="masked-field">****{{ substr($bankAccountRequest->bvn, -4) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>NIN</th>
                                                <td>
                                                    <span class="masked-field">****{{ substr($bankAccountRequest->nin, -4) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="status-pill {{ $pillClass }}">
                                                        <i class="fa fa-circle" style="font-size:8px;"></i>
                                                        {{ ucfirst($bankAccountRequest->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Submitted</th>
                                                <td>{{ $bankAccountRequest->created_at->format('d M Y, h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Processed At</th>
                                                <td>{{ $bankAccountRequest->processed_at ? $bankAccountRequest->processed_at->format('d M Y, h:i A') : '—' }}</td>
                                            </tr>
                                            @if($bankAccountRequest->admin_note)
                                                <tr>
                                                    <th>Admin Note</th>
                                                    <td>{{ $bankAccountRequest->admin_note }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                        <div class="d-flex align-items-center gap-2 flex-wrap mt-3 pt-3 border-top">
                                            {{-- Update (UI-only for now) --}}
                                            <button type="button" id="btnApproveRequest"
                                                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                                                    style="background:rgba(22,163,74,0.12);color:#16a34a;border:0;border-radius:14px;font-weight:700;">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Update
                                            </button>

                                            {{-- Reject --}}
                                            <form id="formReject"
                                                  action="{{ route('admin.bank-account-request.reject', $bankAccountRequest->id) }}"
                                                  method="POST" style="display:none;">
                                                @csrf
                                                <input type="hidden" name="admin_note" id="rejectNote">
                                            </form>
                                            <button type="button" id="btnRejectRequest"
                                                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2"
                                                    style="background:rgba(245,158,11,0.12);color:#d97706;border:0;border-radius:14px;font-weight:700;">
                                                <i class="fa-solid fa-ban"></i>
                                                Reject
                                            </button>

                                            {{-- Delete --}}
                                            <form id="formDelete" action="{{ route('admin.bank-account-request.destroy', $bankAccountRequest->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button" id="btnDeleteRequest"
                                                    class="btn d-inline-flex align-items-center gap-2 px-4 py-2 ms-auto"
                                                    style="background:rgba(220,38,38,0.12);color:#dc2626;border:0;border-radius:14px;font-weight:700;">
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Customer Profile --}}
                            <div class="col-lg-5">
                                <div class="dashboard-card">
                                    <div class="card-header">
                                        <div class="section-icon icon-green">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="section-title">Customer Profile</div>
                                            <div class="section-subtitle">Linked personal account info</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if($bankAccountRequest->personal)
                                            @php $p = $bankAccountRequest->personal; @endphp
                                            <table class="detail-table">
                                                <tr>
                                                    <th>Full Name</th>
                                                    <td>{{ $p->firstname }} {{ $p->lastname }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $p->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone</th>
                                                    <td>{{ $p->person_phone ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Country</th>
                                                    <td>{{ $p->country ?? '—' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email Verified</th>
                                                    <td>
                                                        @if($p->email_verified_status === 'yes')
                                                            <span class="status-pill pill-approved"><i class="fa fa-check" style="font-size:9px;"></i> Verified</span>
                                                        @else
                                                            <span class="status-pill pill-rejected"><i class="fa fa-times" style="font-size:9px;"></i> Unverified</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="mt-3">
                                                <!-- <a href="#" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">
                                                    <i class="fa fa-user me-1"></i> View Full Profile
                                                </a> -->
                                            </div>
                                        @else
                                            <p class="text-muted text-center py-3">No customer linked.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Reject
        document.getElementById('btnRejectRequest')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Reject this request?',
                html: `
                    <p class="text-muted mb-3">You can optionally leave a note for the customer.</p>
                    <textarea id="swalNote" class="swal2-textarea" placeholder="Admin note (optional)…" rows="3"></textarea>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d97706',
                focusCancel: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.getElementById('rejectNote').value =
                        document.getElementById('swalNote')?.value ?? '';
                    document.getElementById('formReject').submit();
                }
            });
        });

        // Delete
        document.getElementById('btnDeleteRequest')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Delete this request?',
                text: 'The record will be soft-deleted and can be recovered later.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                focusCancel: true,
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.getElementById('formDelete').submit();
                }
            });
        });

    });
</script>
</body>
