@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            
            <div class="app-main__outer">
                <div class="app-main__inner">

                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph icon-gradient bg-ripe-malin"></i>
                                </div>
                                  <div>
                                        Teammember  
                                        <div class="page-title-subheading">
                                            View and manage all Teammember.  
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 
        
                    
                    
   
            


            

                     <div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Team Members</h5>
    </div>

    <div class="card-body">

        @if($allteammembers->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th>Invite Token</th>
                        <th>Token Expires</th>
                        <th>Used At</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($allteammembers as $index => $member)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->role }}</td>

                        <td>
                            @if($member->permissions)
                                <span class="badge bg-info text-dark">
                                    {{ implode(', ', json_decode($member->permissions, true)) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>

                        <td>
                            @if($member->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($member->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($member->status === 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-danger">Unknown</span>
                            @endif
                        </td>

                        <td>{{ $member->invite_token ?? '—' }}</td>
                        <td>{{ $member->invite_token_expires_at ?? '—' }}</td>
                        <td>{{ $member->invite_token_used_at ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @else
            <div class="alert alert-warning text-center">No team members found.</div>
        @endif
    </div>
</div>
                </div>
                        {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                              
                                    <button type="button" class="btn me-2 mb-2 btn-primary" data-bs-toggle="modal"
                                        data-bs-target=".bd-example-modal-lg">Large modal</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}


           
            </div>

        </div>
    </div>

@include('admin.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('transactionSearch');
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const match = cells.some(td => td.textContent.toLowerCase().includes(query));
            row.style.display = match ? '' : 'none';
        });
    });
});
</script>

