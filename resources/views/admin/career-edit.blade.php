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
                                       Edit Career
                                        <div class="page-title-subheading">
                                            View Edit Career
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 
        
                    
                    
   
            


            

           <div class="main-card mb-3 card">
    <div class="card-header">
        <h5>Edit Career</h5>
    </div>

    <div id="toast-container"></div>

    <div class="card-body">
        <form action="{{ route('admin.career-view.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Job Title</label>
                <input type="text" class="form-control" name="title" 
                       value="{{ $job->title }}" 
                       placeholder="Product Manager (SME Business Banking)">
            </div>

            <div class="mb-3">
                <label>Job Type</label>
                <select class="form-control" name="job_type">
                    <option {{ $job->job_type == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                    <option {{ $job->job_type == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                    <option {{ $job->job_type == 'Remote' ? 'selected' : '' }}>Remote</option>
                    <option {{ $job->job_type == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Location</label>
                <input type="text" class="form-control" name="location" 
                       value="{{ $job->location }}" 
                       placeholder="Remote">
            </div>

            <div class="mb-3">
                <label>Job Description</label>
                <textarea class="form-control" name="description" rows="4"
                    placeholder="Define the strategy...">{{ $job->description }}</textarea>
            </div>

            <div class="mb-3">
                <label>Key Requirements</label>
                <textarea class="form-control" name="requirements" rows="5"
                    placeholder="3+ years...">{{ $job->requirements }}</textarea>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="1" {{ $job->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $job->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Job</button>
        </form>
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

