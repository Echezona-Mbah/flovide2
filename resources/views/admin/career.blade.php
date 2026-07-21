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
                                    <i class="fa-solid fa-briefcase icon-gradient bg-ripe-malin"></i>
                                </div>
                                  <div>
                                        Add New Career  
                                        <div class="page-title-subheading">
                                            View Add New Career  
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 
        
                    
                    
   
            


            

                       <div class="main-card mb-3 card">
    <div class="card-header">
        <h5>Add New Career</h5>
    </div>
                                              <div id="toast-container"></div>

    <div class="card-body">
        <form action="{{ route('admin.career.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Job Title</label>
                <input type="text" class="form-control" name="title" placeholder="Product Manager (SME Business Banking)">
            </div>

            <div class="mb-3">
                <label>Job Type</label>
                <select class="form-control" name="job_type">
                    <option>Full-Time</option>
                    <option>Part-Time</option>
                    <option>Remote</option>
                    <option>Hybrid</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Location</label>
                <input type="text" class="form-control" name="location" placeholder="Remote">
            </div>

            <div class="mb-3">
                <label>Job Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Define the strategy and roadmap for our SME banking platform..."></textarea>
            </div>

            <div class="mb-3">
                <label>Key Requirements</label>
                <textarea class="form-control" name="requirements" rows="5" placeholder="3+ years of product management experience..."></textarea>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select class="form-control" name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Job</button>
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

