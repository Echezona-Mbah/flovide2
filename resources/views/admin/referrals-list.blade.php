<div class="dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="section-title">Referrals</div>
            <div class="section-subtitle">People this account has referred, and their progress toward the referral bonus.</div>
        </div>
        <span class="custom-status status-muted">
            {{ $referrals->count() }} total ·
            {{ $referrals->filter(fn($r) => $r['progress']['completed'])->count() }} completed
        </span>
    </div>

    <div class="card-body">
        @if($referrals->count() > 0)
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Joined</th>
                            <th class="text-center">Progress</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referrals as $r)
                            @php
                                $ref = $r['model'];
                                $p = $r['progress'];
                                $name = $ref->business_name ?? trim(($ref->firstname ?? '') . ' ' . ($ref->lastname ?? ''));
                            @endphp
                            <tr>
                                <td class="fw-bold">{{ $name ?: 'N/A' }}</td>
                                <td>{{ $ref->email }}</td>
                                <td class="text-center">{{ optional($ref->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="text-center" style="min-width: 180px;">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>{{ $p['currency'] }} {{ number_format($p['total'], 2) }}</span>
                                        <span>{{ $p['currency'] }} {{ number_format($p['threshold'], 2) }}</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 999px;">
                                        <div class="progress-bar {{ $p['completed'] ? 'bg-success' : 'bg-primary' }}"
                                             style="width: {{ $p['percent'] }}%; border-radius: 999px;"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($p['completed'])
                                        <span class="custom-status status-success">
                                            <i class="fa-solid fa-check"></i> Completed
                                        </span>
                                    @else
                                        <span class="custom-status status-warning">
                                            {{ $p['percent'] }}% there
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">This account hasn't referred anyone yet.</div>
        @endif
    </div>
</div>