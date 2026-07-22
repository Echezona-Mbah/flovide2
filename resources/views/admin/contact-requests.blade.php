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
                            @if($contactRequests->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fa-solid fa-inbox fa-2x text-muted mb-3"></i>
                                    <h5 class="text-muted">No contact requests found</h5>
                                    <p class="text-muted mb-0">There are currently no contact requests to display.</p>
                                </div>
                            @else
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
                                                <td>{{ empty($contactRequest->company_name) ? "N/A" : $contactRequest->company_name }}</td>
                                                <td>
                                                    <span onclick="navigator.clipboard.writeText('{{ $contactRequest->email }}')" title="Click to copy" class="badge bg-light text-dark border px-3 py-2">
                                                        <i class="fa fa-envelope text-primary me-1"></i>
                                                        {{ $contactRequest->email }}
                                                    </span>
                                                </td>
                                                <td>{{ $contactRequest->phone ?? "N/A" }}</td>
                                                <td>{{ $contactRequest->message }}</td>
                                                <td>
                                                    <span class="badge
                                                        @if($contactRequest->status == 'pending')
                                                            bg-warning text-dark
                                                        @elseif($contactRequest->status == 'in_progress')
                                                            bg-info text-white
                                                        @elseif($contactRequest->status == 'resolved')
                                                            bg-success
                                                        @elseif($contactRequest->status == 'closed')
                                                            bg-secondary
                                                        @endif">

                                                        {{ ucwords(str_replace('_', ' ', $contactRequest->status)) }}

                                                    </span>
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
                                                    <button class="btn btn-primary btn-sm mb-1" onclick="openReplyModal({{ $contactRequest->id }}, '{{ $contactRequest->email }}')">
                                                        <i class="fa fa-reply"></i>
                                                        Reply
                                                    </button>

                                                    <br>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteContactRequest({{ $contactRequest->id }})">
                                                        <i class="fa fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>


                    

                </div>
                
            </div>

        </div>
    </div>


    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Reply to Contact Request
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="contact_id">
                    <div class="mb-3">
                        <label>Recipient</label>
                        <input type="email" id="recipient_email" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" id="reply_subject" class="form-control" placeholder="Re: Your Contact Request">
                    </div>

                    <div class="mb-3">
                        <label>Message</label>
                        <textarea id="reply_message" rows="8" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button id="sendReplyBtn" class="btn btn-success" onclick="sendReply()">
                        <span id="sendReplySpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        <i class="fa fa-paper-plane"></i>
                        <span id="sendReplyText">Send Reply</span>
                    </button>
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

    function updateStatus(id, status) {
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


    function openReplyModal(id, email) {
        document.getElementById('contact_id').value = id;
        document.getElementById('recipient_email').value = email;

        const modal = new bootstrap.Modal(
            document.getElementById('replyModal')
        );

        modal.show();
    }


    function setReplyButtonLoading(isLoading) {
        const btn = document.getElementById('sendReplyBtn');
        const spinner = document.getElementById('sendReplySpinner');
        const text = document.getElementById('sendReplyText');

        if (!btn || !spinner || !text) return;

        btn.disabled = isLoading;
        spinner.classList.toggle('d-none', !isLoading);
        text.textContent = isLoading ? 'Sending...' : 'Send Reply';
    }

    function sendReply() {
        const id = document.getElementById('contact_id').value;
        const subject = document.getElementById('reply_subject').value;
        const message = document.getElementById('reply_message').value;

        setReplyButtonLoading(true);

        fetch(`/admin/contact-requests/${id}/reply`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                subject: subject,
                message: message
            })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Unable to send reply.');
            return data;
        })
        .then(data => {
            Swal.fire({
                icon:"success",
                title:"Reply Sent",
                text:data.message
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('replyModal'));
            if (modal) modal.hide();
        })
        .catch(error => {
            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: error.message || "Unable to send reply."
            });
        })
        .finally(() => {
            setReplyButtonLoading(false);
        });
    }
</script>



