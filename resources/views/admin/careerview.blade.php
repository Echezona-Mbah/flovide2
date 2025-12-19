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
                                        Career Listings
                                        <div class="page-title-subheading">
                                            View Career Listings 
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 
        
                    
                    
   
    <div class="main-card mb-3 card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Career Listings</h5>

            <!-- Add Career Button -->
            <a href="{{ route('admin.career.create') }}" class="btn btn-success">
                + Add Career
            </a>
        </div>

        <div id="toast-container"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Job Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($jobs as $job)
                    <tr id="row-{{ $job->id }}">
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->job_type }}</td>
                        <td>{{ $job->location }}</td>
                        <td>{{ $job->status ? 'Active' : 'Inactive' }}</td>

                        <td>
                            <a href="{{ route('admin.career-view.update', $job->id) }}" 
                            class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            
                            <form action="{{ route('admin.career-view.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mb-2 me-2 btn btn-danger" onclick="return confirm('Are you sure you want to delete this career?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $jobs->links('pagination::bootstrap-5') }}
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function deleteCareer(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "This career will be deleted permanently!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "/admin/career-view/" + id,
                    type: "DELETE",

                    success: function(response) {
                        Swal.fire("Deleted!", response.message, "success");

                        $("#row-" + id).remove();
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire("Error", "Unable to delete item.", "error");
                    }
                });
            }
        });
    }
</script>



