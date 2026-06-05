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
</style>

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
                                    <i class="pe-7s-note2 icon-gradient bg-happy-itmeo"></i>
                                </div>
                                <div>
                                    Edit Blog Post
                                    <div class="page-title-subheading">
                                        Modify post settings, content editor, image assets, classification and search options.
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>

                    <div id="toast-container"></div>

                    <section class="mb-4">
                        <div class="main-card card" style="border-radius: 12px; border: 0.5px solid #e2e8f0;">
                            <div class="card-body p-4">

                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:40px; height:40px; border-radius:8px; background:#f8fafc; border:0.5px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                                            <i class="pe-7s-news-paper" style="font-size:20px; color:#64748b;"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-500" style="font-size:15px; color:#1e293b;">Blog posts</p>
                                            <p class="mb-0" style="font-size:13px; color:#94a3b8;">Manage your published content</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Stats --}}
                                <div class="row g-2 mb-4">
                                    <div class="col-4">
                                        <div style="background:#f8fafc; border-radius:8px; padding:12px; text-align:center;">
                                            <span style="display:block; font-size:22px; font-weight:500; color:#1e293b; line-height:1.2;">
                                                {{ $totalPosts ?? 0 }}
                                            </span>
                                            <span style="display:block; font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-top:2px;">
                                                Total
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div style="background:#f8fafc; border-radius:8px; padding:12px; text-align:center;">
                                            <span style="display:block; font-size:22px; font-weight:500; color:#16a34a; line-height:1.2;">
                                                {{ $publishedPosts ?? 0 }}
                                            </span>
                                            <span style="display:block; font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-top:2px;">
                                                Published
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div style="background:#f8fafc; border-radius:8px; padding:12px; text-align:center;">
                                            <span style="display:block; font-size:22px; font-weight:500; color:#d97706; line-height:1.2;">
                                                {{ $draftPosts ?? 0 }}
                                            </span>
                                            <span style="display:block; font-size:11px; color:#94a3b8; text-transform:uppercase; letter-spacing:0.04em; margin-top:2px;">
                                                Drafts
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Divider --}}
                                <hr style="border:none; border-top:0.5px solid #e2e8f0; margin:0 0 1.25rem;">

                                {{-- Actions --}}
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.blog.view') }}"
                                    style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:7px; background:#1e293b; color:#fff; border:none; border-radius:8px; padding:9px 16px; font-size:13px; font-weight:500; text-decoration:none; transition:opacity 0.15s;"
                                    onmouseover="this.style.opacity='0.85'"
                                    onmouseout="this.style.opacity='1'">
                                        <i class="pe-7s-display2" style="font-size:15px;"></i> View all posts
                                    </a>
                                </div>

                            </div>
                        </div>
                    </section>

                    <!-- Blog Post Form -->
                    <form id="blog-update-form" class="row" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}
                        
                        <div class="col-lg-8 col-md-12">
                            <!-- Title & Body Card -->
                            <div class="main-card mb-4 card">
                                <div class="card-body">
                                    <!-- Blog Title -->
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Post Title</label>
                                        <input type="text" id="post-title" name="title" class="form-control blog-title-input" placeholder="Enter post title here..." required>
                                    </div>

                                    <!-- Slug/Permalink -->
                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Permalink</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light text-muted">https://flovide.com/blog/</span>
                                            <input type="text" id="post-slug" name="slug" class="form-control" placeholder="Slug" required>
                                            <button class="btn btn-outline-secondary" type="button" id="btn-regenerate-slug">
                                                <i class="pe-7s-refresh-2"></i> Regenerate
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Excerpt -->
                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Excerpt / Summary</label>
                                        <textarea name="excerpt" class="form-control" rows="4" placeholder="Provide a short description of this post for list pages and search results..."></textarea>
                                    </div>

                                    <!-- Content Editor -->
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Post Content</label>
                                        <div class="editor-toolbar">
                                            <button type="button" class="editor-btn" title="Bold" onclick="wrapText('**')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/></svg>
                                            </button>
                                            <button type="button" class="editor-btn" title="Italic" onclick="wrapText('*')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                                            </button>
                                            <button type="button" class="editor-btn" title="Header 1" onclick="wrapText('# ', '')">H1</button>
                                            <button type="button" class="editor-btn" title="Header 2" onclick="wrapText('## ', '')">H2</button>
                                            <button type="button" class="editor-btn" title="Quote" onclick="wrapText('> ', '')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            </button>
                                            <button type="button" class="editor-btn" title="Link" onclick="insertLink()">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                            </button>
                                            <button type="button" class="editor-btn" title="Image Code" onclick="wrapText('![alt text](', ')')">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                            </button>
                                        </div>
                                        <textarea id="editor-textarea" rows="20" name="content" class="form-control editor-textarea" placeholder="Start writing your story..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Card -->
                            <div class="main-card mb-4 card">
                                <div class="card-header">
                                    <div class="card-header-title text-capitalize font-weight-bold">
                                        <i class="pe-7s-search icon-gradient bg-plum-plate me-2"></i> SEO Optimization
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Search Result Preview -->
                                    <div class="mb-4">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Search Engine Snippet Preview</label>
                                        <div class="google-preview-card">
                                            <div class="google-preview-url">
                                                <span>https://flovide.com</span>
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                                <span id="preview-url-slug">blog/</span>
                                            </div>
                                            <div class="google-preview-title" id="preview-seo-title"></div>
                                            <div class="google-preview-description" id="preview-seo-desc"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Meta Title</label>
                                        <input type="text" id="meta-title" name="meta_title" class="form-control" placeholder="Recommended length: 50-60 characters">
                                        <span class="char-counter" id="meta-title-counter">0 / 60 characters</span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Meta Description</label>
                                        <textarea id="meta-description" name="meta_description" class="form-control" rows="3" placeholder="Recommended length: 120-160 characters"></textarea>
                                        <span class="char-counter" id="meta-desc-counter">0 / 160 characters</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <!-- Publishing Settings Card -->
                            <div class="main-card mb-4 card">
                                <div class="card-header">
                                    <div class="card-header-title text-capitalize font-weight-bold">
                                        <i class="pe-7s-paper-plane icon-gradient bg-happy-itmeo me-2"></i> Publish Settings
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status</label>
                                        <div class="d-flex align-items-center mb-2">
                                            <span id="status-badge" class="status-indicator status-published"></span>
                                            <select name="status" id="post-status" class="form-control form-control-sm" style="flex: 1;">
                                                <option value="published" selected>Published</option>
                                                <option value="draft">Draft</option>
                                                <option value="scheduled">Scheduled</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Visibility</label>
                                        <select name="visibility" class="form-control form-control-sm">
                                            <option value="public">Public</option>
                                            <option value="private">Private (Admin only)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="scheduled-date-group">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Schedule Date & Time</label>
                                        <input type="datetime-local" name="published_at" class="form-control form-control-sm">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Author</label>
                                        <select name="author_id" class="form-control form-control-sm">
                                            <option value="">No Author</option>
                                            @foreach($authors as $author)
                                                <option value="{{ $author->id }}">{{ $author->name }} ({{ $author->role }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between gap-2 mt-3">
                                        <button type="button" class="btn btn-gradient-secondary btn-sm" style="flex: 1;">
                                            <i class="pe-7s-look me-1"></i> Preview
                                        </button>
                                        <button type="submit" id="btn-submit-post" class="btn btn-gradient-primary btn-sm" style="flex: 1;">
                                            <span class="spinner-border spinner-border-sm me-1" id="submit-spinner" style="display: none;" role="status" aria-hidden="true"></span>
                                            <i class="pe-7s-diskette me-1" id="submit-icon"></i> <span id="submit-text">Update Post</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Image Card -->
                            <div class="main-card mb-4 card">
                                <div class="card-header">
                                    <div class="card-header-title text-capitalize font-weight-bold">
                                        <i class="pe-7s-image icon-gradient bg-grow-early me-2"></i> Featured Image
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="image-dropzone" onclick="document.getElementById('featured-image-input').click();">
                                        <div id="dropzone-prompt">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="1.5" class="mb-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            <p class="mb-0 text-muted font-size-sm font-weight-bold">Drag & drop cover image</p>
                                            <p class="text-muted font-size-xs mb-0">or click to browse local files</p>
                                        </div>
                                        <div class="image-preview-container" id="image-preview">
                                            <button type="button" class="remove-image-btn" id="btn-remove-image" title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" id="featured-image-input" name="featured_image" accept="image/*" style="display: none;">
                                    <input type="hidden" id="remove-featured-image-input" name="remove_featured_image" value="0">
                                    <small class="text-muted d-block mt-2 font-size-xs text-center">Supports PNG, JPG or WebP. Suggested ratio: 16:9.</small>
                                </div>
                            </div>

                            <!-- Categorization Card -->
                            <div class="main-card mb-4 card">
                                <div class="card-header">
                                    <div class="card-header-title text-capitalize font-weight-bold">
                                        <i class="pe-7s-ticket icon-gradient bg-warm-flame me-2"></i> Classification
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Primary Category</label>
                                        <select name="category_id" class="form-control form-control-sm">
                                            <option value="">No Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label font-weight-bold text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tags</label>
                                        <div class="tags-container" id="tags-wrapper">
                                            <!-- tags will be rendered here -->
                                        </div>
                                        <div class="input-group input-group-sm">
                                            <input type="text" id="tag-input-field" class="form-control" placeholder="Add tag & press Enter">
                                            <button class="btn btn-outline-primary" type="button" id="btn-add-tag">Add</button>
                                        </div>
                                        <input type="hidden" id="tags-hidden-input" name="tags" value="{{ $tags->pluck('name')->implode(',') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
    
    @include('admin.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 1. Slug Auto-generation
            const titleInput = document.getElementById('post-title');
            const slugInput = document.getElementById('post-slug');
            const urlSlugPreview = document.getElementById('preview-url-slug');
            const btnRegenerateSlug = document.getElementById('btn-regenerate-slug');

            function generateSlug(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .normalize('NFD') // remove accents
                    .replace(/[\u0300-\u036f]/g, '') // remove accents helper
                    .replace(/\s+/g, '-') // replace spaces with -
                    .replace(/[^\w\-]+/g, '') // remove non-word chars
                    .replace(/\-\-+/g, '-') // replace multiple - with single -
                    .replace(/^-+/, '') // trim - from start
                    .replace(/-+$/, ''); // trim - from end
            }

            titleInput.addEventListener('input', function() {
                // Auto update slug if it was matched to title before
                const calculatedSlug = generateSlug(this.value);
                slugInput.value = calculatedSlug;
                urlSlugPreview.textContent = 'blog/' + (calculatedSlug || 'your-post-title');
                updateGooglePreview();
            });

            slugInput.addEventListener('input', function() {
                this.value = generateSlug(this.value);
                urlSlugPreview.textContent = 'blog/' + (this.value || 'your-post-title');
                updateGooglePreview();
            });

            btnRegenerateSlug.addEventListener('click', function() {
                const calculatedSlug = generateSlug(titleInput.value);
                slugInput.value = calculatedSlug;
                urlSlugPreview.textContent = 'blog/' + (calculatedSlug || 'your-post-title');
                updateGooglePreview();
            });




            // 2. SEO Google Preview and Counter
            const metaTitle = document.getElementById('meta-title');
            const metaDesc = document.getElementById('meta-description');
            const previewTitle = document.getElementById('preview-seo-title');
            const previewDesc = document.getElementById('preview-seo-desc');
            const titleCounter = document.getElementById('meta-title-counter');
            const descCounter = document.getElementById('meta-desc-counter');

            function updateGooglePreview() {
                // Fallbacks
                const t = metaTitle.value.trim() || titleInput.value.trim() || 'Untitled Post';
                const d = metaDesc.value.trim() || 'Please write a meta description to see the search snippet preview here...';
                
                previewTitle.textContent = t;
                previewDesc.textContent = d;

                // Counters
                const tLength = metaTitle.value.length;
                titleCounter.textContent = tLength + ' / 60 characters';
                if (tLength > 60) {
                    titleCounter.style.color = '#dc3545';
                } else if (tLength >= 50) {
                    titleCounter.style.color = '#28a745';
                } else {
                    titleCounter.style.color = '#6c757d';
                }

                const dLength = metaDesc.value.length;
                descCounter.textContent = dLength + ' / 160 characters';
                if (dLength > 160) {
                    descCounter.style.color = '#dc3545';
                } else if (dLength >= 120) {
                    descCounter.style.color = '#28a745';
                } else {
                    descCounter.style.color = '#6c757d';
                }
            }

            metaTitle.addEventListener('input', updateGooglePreview);
            metaDesc.addEventListener('input', updateGooglePreview);

            // Initial SEO update
            updateGooglePreview();




            // 3. Status Badge and Date Input
            const statusSelect = document.getElementById('post-status');
            const statusBadge = document.getElementById('status-badge');
            const dateGroup = document.getElementById('scheduled-date-group');

            statusSelect.addEventListener('change', function() {
                statusBadge.className = 'status-indicator';
                if (this.value === 'published') {
                    statusBadge.classList.add('status-published');
                    dateGroup.style.display = 'none';
                } else if (this.value === 'draft') {
                    statusBadge.classList.add('status-draft');
                    dateGroup.style.display = 'none';
                } else if (this.value === 'scheduled') {
                    statusBadge.classList.add('status-scheduled');
                    dateGroup.style.display = 'block';
                }
            });




            // 4. Image Upload & Preview
            const imageInput = document.getElementById('featured-image-input');
            const dropzonePrompt = document.getElementById('dropzone-prompt');
            const imagePreview = document.getElementById('image-preview');
            const removeImageBtn = document.getElementById('btn-remove-image');
            const removeImageHiddenInput = document.getElementById('remove-featured-image-input');

            imagePreview.style.backgroundImage = 'none';
            imagePreview.style.display = 'none';
            dropzonePrompt.style.display = 'block';
            

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    removeImageHiddenInput.value = '0';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.style.backgroundImage = `url('${e.target.result}')`;
                        dropzonePrompt.style.display = 'none';
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });

            removeImageBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // prevent triggering click on parent dropzone
                imageInput.value = '';
                imagePreview.style.backgroundImage = 'none';
                imagePreview.style.display = 'none';
                dropzonePrompt.style.display = 'block';
                removeImageHiddenInput.value = '1';
            });

            // Drag and drop events
            const dropzone = document.querySelector('.image-dropzone');
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = '#3f51b5';
                    dropzone.style.background = '#f1f3f9';
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropzone.style.borderColor = '#ced4da';
                    dropzone.style.background = '#f8fafc';
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    imageInput.files = files;
                    // Trigger change event to load preview
                    const event = new Event('change');
                    imageInput.dispatchEvent(event);
                }
            });




            // 5. Tags System
            const tagsWrapper = document.getElementById('tags-wrapper');
            const tagInputField = document.getElementById('tag-input-field');
            const btnAddTag = document.getElementById('btn-add-tag');
            const tagsHiddenInput = document.getElementById('tags-hidden-input');

            // Load all tags from hidden input
            let tagsList = tagsHiddenInput.value
                .split(',')
                .map(tag => tag.trim())
                .filter(tag => tag !== '');

            function renderTags() {
                tagsWrapper.innerHTML = '';

                tagsList.forEach((tag, index) => {
                    const badge = document.createElement('div');

                    badge.className = 'tag-badge';

                    badge.innerHTML = `
                        ${tag}
                        <span class="remove-tag" data-index="${index}">
                            &times;
                        </span>
                    `;

                    tagsWrapper.appendChild(badge);
                });

                tagsHiddenInput.value = tagsList.join(',');

                document.querySelectorAll('.remove-tag').forEach(btn => {
                    btn.addEventListener('click', function () {

                        const index = parseInt(this.dataset.index);

                        tagsList.splice(index, 1);

                        renderTags();
                    });
                });
            }

            function addTagFromInput() {

                const value = tagInputField.value.trim();

                if (!value) return;

                if (!tagsList.includes(value)) {
                    tagsList.push(value);
                }

                tagInputField.value = '';

                renderTags();
            }

            btnAddTag.addEventListener('click', addTagFromInput);

            tagInputField.addEventListener('keydown', function(e) {

                if (e.key === 'Enter') {

                    e.preventDefault();

                    addTagFromInput();
                }
            });

            renderTags();




            // 6. Content Editor Formatting Helpers
            window.wrapText = function(before, after = before) {
                const textarea = document.getElementById('editor-textarea');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const text = textarea.value;
                const selected = text.substring(start, end);
                const replacement = before + selected + after;
                
                textarea.value = text.substring(0, start) + replacement + text.substring(end);
                
                // Reset selection range
                textarea.focus();
                textarea.setSelectionRange(start + before.length, start + before.length + selected.length);
            };

            window.insertLink = function() {
                const url = prompt('Enter the link URL:', 'https://');
                if (url) {
                    wrapText('[', `](${url})`);
                }
            };



            // 7. AJAX Submit
            const form = document.getElementById('blog-update-form');
            const submitBtn = document.getElementById('btn-submit-post');
            const submitSpinner = document.getElementById('submit-spinner');
            const submitIcon = document.getElementById('submit-icon');
            const submitText = document.getElementById('submit-text');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                submitBtn.disabled = true;
                submitSpinner.style.display = 'inline-block';
                submitIcon.style.display = 'none';
                submitText.textContent = 'Saving Changes...';

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST', // standard HTML post with PUT override inside formData
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    return response.json().then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || 'An error occurred while saving.');
                        }
                        return data;
                    });
                })
                .then(data => {
                    // Restore button
                    submitBtn.disabled = false;
                    submitSpinner.style.display = 'none';
                    submitIcon.style.display = 'inline-block';
                    submitText.textContent = 'Update Post';
                    
                    // Show toast message
                    showToastMessage(data.message || 'Blog post updated successfully!', 'success');
                    
                    // Update slug preview URL text in snippet
                    const slugInputVal = document.getElementById('post-slug').value;
                    document.getElementById('preview-url-slug').textContent = 'blog/' + slugInputVal;
                    
                    // If a featured image was uploaded or removed, update the view state
                    if (data.featured_image_url) {
                        imagePreview.style.backgroundImage = `url('${data.featured_image_url}')`;
                        dropzonePrompt.style.display = 'none';
                        imagePreview.style.display = 'block';
                        document.getElementById('remove-featured-image-input').value = '0';
                    }
                })
                .catch(error => {
                    // Restore button
                    submitBtn.disabled = false;
                    submitSpinner.style.display = 'none';
                    submitIcon.style.display = 'inline-block';
                    submitText.textContent = 'Update Post';
                    
                    // Show toast error message
                    showToastMessage(error.message || 'An unexpected error occurred.', 'error');
                });
            });

            function showToastMessage(message, type = 'success') {
                const container = document.getElementById('toast-container') || document.body;
                
                // Simple toast element
                const toast = document.createElement('div');
                toast.className = `toast ${type}`;
                toast.innerHTML = `
                    <span>${message}</span>
                    <div class="toast-progress"></div>
                `;
                
                container.appendChild(toast);
                
                // Auto remove toast after 3.8s
                setTimeout(() => {
                    toast.remove();
                }, 3800);
            }
        });
    </script>
</body>
</html>