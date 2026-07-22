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
                                    <i class="fa-solid fa-envelope icon-gradient bg-ripe-malin"></i>
                                </div>
                                <div>
                                    Contact Requests
                                    <div class="page-title-subheading">
                                        View Contact Requests 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
        
                    
                    <div class="main-card mb-3 card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Contact Requests</h5>
                        </div>

                        <div id="toast-container"></div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Company Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contactRequests as $contactRequest)
                                        <tr id="row-{{ $contactRequest->id }}">
                                            <td>{{ $contactRequest->first_name }}</td>
                                            <td>{{ $contactRequest->last_name }}</td>
                                            <td>{{ $contactRequest->company_name ?? "N/A" }}</td>
                                            <td>
                                                <span onclick="navigator.clipboard.writeText('{{ $contactRequest->email }}')" title="Click to copy" class="badge bg-light text-dark border px-3 py-2">
                                                    <i class="fa fa-envelope text-primary me-1"></i>
                                                    {{ $contactRequest->email }}
                                                </span>
                                            </td>
                                            <td>{{ $contactRequest->phone ?? "N/A" }}</td>
                                            <td>{{ $contactRequest->message }}</td>
                                            <td>
                                                <select class="form-select form-select-sm" onchange="updateStatus({{ $contactRequest->id }}, this.value)">

                                                    <option value="pending"
                                                        {{ $contactRequest->status == 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>

                                                    <option value="in_progress"
                                                        {{ $contactRequest->status == 'in_progress' ? 'selected' : '' }}>
                                                        In Progress
                                                    </option>

                                                    <option value="resolved"
                                                        {{ $contactRequest->status == 'resolved' ? 'selected' : '' }}>
                                                        Resolved
                                                    </option>

                                                    <option value="closed"
                                                        {{ $contactRequest->status == 'closed' ? 'selected' : '' }}>
                                                        Closed
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-danger btn-sm" onclick="deleteContactRequest({{ $contactRequest->id }})">
                                                    <i class="fa fa-trash"></i>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                    </div>

                </div>
                
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function updateStatus(id, status) {`

        fetch(`/admin/contact-requests/${id}/update-status`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Updated",
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    function deleteContactRequest(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This contact request will be moved to the trash.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {

            if (!result.isConfirmed) return;

            fetch(`/admin/contact-requests/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Request failed.");
                }

                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: "success",
                    title: "Deleted!",
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                document.getElementById(`row-${id}`).remove();
            })
            .catch(error => {
                console.error(error);

                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Unable to delete the contact request."
                });
            });

        });
    }
</script>



