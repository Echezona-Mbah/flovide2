@include('admin.head')

<script src="https://unpkg.com/feather-icons"></script>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')

        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')

            <div class="app-main__outer">
                <div class="app-main__inner">

                    {{-- Page Title --}}
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fa-solid fa-blog"></i>
                                </div>
                                <div>
                                    Blog posts
                                    <div class="page-title-subheading">
                                        View, publish and manage all blog posts.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="main-card card" style="border-radius:12px; border:0.5px solid #e2e8f0;">
                        <div class="card-body p-4">

                            {{-- Header --}}
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <p class="mb-0 fw-bold" style="font-size:16px; color:#1e293b;">Blog posts</p>
                                    <p class="mb-0" style="font-size:13px; color:#94a3b8;">
                                        {{ $posts->total() }} posts total &mdash;
                                        {{ $publishedCount }} published,
                                        {{ $draftCount }} drafts
                                    </p>
                                </div>
                                <a href="{{ route('admin.blog') }}"
                                   style="display:inline-flex; align-items:center; gap:6px; background:#1e293b; color:#fff; border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:500; text-decoration:none;">
                                    <i data-feather="plus" style="width:15px; height:15px;"></i> New post
                                </a>
                            </div>

                            {{-- Filters + Search --}}
                            <div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
                                <a href="{{ route('admin.blog.view') }}"
                                   style="padding:5px 14px; border-radius:20px; font-size:12px; text-decoration:none;
                                          {{ !request('status') ? 'background:#1e293b; color:#fff; border:0.5px solid #1e293b;' : 'background:transparent; color:#64748b; border:0.5px solid #cbd5e1;' }}">
                                    All <span style="opacity:0.6;">{{ $posts->total() }}</span>
                                </a>
                                <a href="{{ route('admin.blog.view', ['status' => 'published']) }}"
                                   style="padding:5px 14px; border-radius:20px; font-size:12px; text-decoration:none;
                                          {{ request('status') === 'published' ? 'background:#1e293b; color:#fff; border:0.5px solid #1e293b;' : 'background:transparent; color:#64748b; border:0.5px solid #cbd5e1;' }}">
                                    Published <span style="opacity:0.6;">{{ $publishedCount }}</span>
                                </a>
                                <a href="{{ route('admin.blog.view', ['status' => 'draft']) }}"
                                   style="padding:5px 14px; border-radius:20px; font-size:12px; text-decoration:none;
                                          {{ request('status') === 'draft' ? 'background:#1e293b; color:#fff; border:0.5px solid #1e293b;' : 'background:transparent; color:#64748b; border:0.5px solid #cbd5e1;' }}">
                                    Drafts <span style="opacity:0.6;">{{ $draftCount }}</span>
                                </a>
                                <a href="{{ route('admin.blog.view', ['status' => 'scheduled']) }}"
                                   style="padding:5px 14px; border-radius:20px; font-size:12px; text-decoration:none;
                                          {{ request('status') === 'scheduled' ? 'background:#1e293b; color:#fff; border:0.5px solid #1e293b;' : 'background:transparent; color:#64748b; border:0.5px solid #cbd5e1;' }}">
                                    Scheduled <span style="opacity:0.6;">{{ $scheduledCount }}</span>
                                </a>

                                {{-- Search --}}
                                <form action="{{ route('admin.blog.view') }}" method="GET" class="ms-auto">
                                    @if(request('status'))
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    @endif
                                    <div style="position:relative;">
                                        <i data-feather="search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); width:15px; height:15px; color:#94a3b8; pointer-events:none;"></i>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                               placeholder="Search posts..."
                                               style="padding:7px 12px 7px 34px; font-size:13px; border:0.5px solid #cbd5e1; border-radius:8px; background:#f8fafc; color:#1e293b; width:200px;">
                                    </div>
                                </form>
                            </div>

                            {{-- Table --}}
                            <div style="border:0.5px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                                <table class="table mb-0" style="table-layout:fixed; width:100%;">
                                    <thead style="background:#f8fafc;">
                                        <tr>
                                            <th style="width:32%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Post</th>
                                            <th style="width:13%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Category</th>
                                            <th style="width:13%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Author</th>
                                            <th style="width:12%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Date</th>
                                            <th style="width:11%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Status</th>
                                            <th style="width:19%; padding:10px 14px; font-size:11px; font-weight:500; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; border-bottom:0.5px solid #e2e8f0;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($posts as $post)
                                        <tr style="border-bottom:0.5px solid #e2e8f0;">
                                            <td style="padding:13px 14px; vertical-align:middle;">
                                                <p class="mb-0" style="font-weight:500; font-size:13px; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                    {{ $post->title }}
                                                </p>
                                                <p class="mb-0" style="font-size:12px; color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                    {{ $post->slug }}
                                                </p>
                                            </td>
                                            <td style="padding:13px 14px; font-size:12px; color:#64748b; vertical-align:middle;">
                                                {{ $post->category->name ?? '—' }}
                                            </td>
                                            <td style="padding:13px 14px; font-size:12px; color:#64748b; vertical-align:middle;">
                                                {{ $post->author->name ?? '—' }}
                                            </td>
                                            <td style="padding:13px 14px; font-size:12px; color:#64748b; vertical-align:middle;">
                                                {{ $post->created_at->format('M d, Y') }}
                                            </td>
                                            <td style="padding:13px 14px; vertical-align:middle;">
                                                @if($post->status === 'published')
                                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:500; background:#EAF3DE; color:#3B6D11;">
                                                        <span style="width:6px; height:6px; border-radius:50%; background:#639922; display:inline-block;"></span>
                                                        Published
                                                    </span>
                                                @elseif($post->status === 'draft')
                                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:500; background:#FAEEDA; color:#854F0B;">
                                                        <span style="width:6px; height:6px; border-radius:50%; background:#BA7517; display:inline-block;"></span>
                                                        Draft
                                                    </span>
                                                @elseif($post->status === 'scheduled')
                                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:500; background:#E6F1FB; color:#185FA5;">
                                                        <span style="width:6px; height:6px; border-radius:50%; background:#378ADD; display:inline-block;"></span>
                                                        Scheduled
                                                    </span>
                                                @endif
                                            </td>
                                            <td style="padding:13px 14px; vertical-align:middle;">
                                                <div class="d-flex align-items-center gap-1">

                                                    {{-- Publish btn — drafts only --}}
                                                    @if($post->status === 'draft')
                                                        <form action="{{ route('admin.blog.publish', $post->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    style="display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; border:0.5px solid #3B6D11; color:#3B6D11; background:#EAF3DE; white-space:nowrap;">
                                                                <i data-feather="send" style="width:13px; height:13px;"></i> Publish
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Edit --}}
                                                    <!-- <a href=""
                                                       style="display:inline-flex; align-items:center; gap:5px; padding:5px 10px; border-radius:6px; font-size:12px; font-weight:500; border:0.5px solid #cbd5e1; color:#1e293b; background:transparent; text-decoration:none; white-space:nowrap;">
                                                        <i data-feather="edit-2" style="width:13px; height:13px;"></i>
                                                        @if($post->status === 'published') Edit @endif
                                                    </a> -->

                                                    {{-- Delete --}}
                                                    <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn-delete"
                                                                style="display:inline-flex; align-items:center; justify-content:center; padding:5px 9px; border-radius:6px; font-size:12px; cursor:pointer; border:0.5px solid #A32D2D; color:#A32D2D; background:#FCEBEB;">
                                                            <i data-feather="trash-2" style="width:13px; height:13px;"></i>
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" style="padding:40px; text-align:center; color:#94a3b8; font-size:13px;">
                                                <i data-feather="inbox" style="width:32px; height:32px; display:block; margin:0 auto 10px; color:#cbd5e1;"></i>
                                                No blog posts found.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($posts->hasPages())
                            <div class="d-flex align-items-center justify-content-between mt-4">
                                <p class="mb-0" style="font-size:12px; color:#94a3b8;">
                                    Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} posts
                                </p>
                                {{ $posts->links() }}
                            </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('admin.footer')
    </div>

    <script src="https://unpkg.com/feather-icons"></script>
    <script>feather.replace();</script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const form = this.closest('.delete-form');

                Swal.fire({
                    title: 'Delete this post?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#A32D2D',
                    cancelButtonColor: '#e2e8f0',
                    customClass: {
                        cancelButton: 'swal-cancel-btn',
                        confirmButton: 'swal-confirm-btn'
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <style>
        .swal-cancel-btn { color: #1e293b !important; }
        .swal-confirm-btn { 
            color: #fff !important; 
            background-color: #510d0dff !important;
            border-color: #510d0dff !important;
        }
    </style>
</body>
</html>