<div class="row g-2 justify-content-start justify-content-md-end align-items-center">
    <div class="col-auto">
        <form class="docs-search-form row gx-1 align-items-center" id="searchForm">
            <div class="col-auto">
                <input type="text" id="search-docs" name="search" class="form-control search-docs" placeholder="Search by name" onkeyup="searchCategories()">
            </div>
            <div class="col-auto">
                <button type="button" class="btn app-btn-secondary" onclick="searchCategories()">Search</button>
            </div>
        </form>
    </div>

    <div class="col-auto">
        <select class="form-select w-auto" id="filter-type" onchange="filterCategories()">
            <option value="all">All</option>
                                        <option value="week">This week</option>
                                        <option value="month">This month</option>
                                        <option value="three_months">Last 3 months</option>
        </select>
    </div>

    <div class="col-auto">
        <a class="btn app-btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#uploadCategory">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-upload me-2" fill="currentColor">
                <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/>
            </svg>Upload File
        </a>
    </div>
</div>

<!-- Categories List -->
<div class="row">
    @foreach ($categories as $c)
        <div class="col-6 col-md-4 col-xl-3 col-xxl-2 mt-4">
            <div class="app-card app-card-doc shadow-sm h-100">
                <div class="app-card-thumb-holder p-3">
                    <div class="app-card-thumb">
                        <img class="thumb-image" src="{{ $c['categoryImage'] }}" alt="{{ $c['categoryName'] }}">
                    </div>
                    <a class="app-card-link-mask" href="#file-link"></a>
                </div>
                <div class="app-card-body p-3 has-card-actions">
                    <h4 class="app-doc-title truncate mb-0"><a href="#file-link">{{ $c['categoryName'] }}</a></h4>
                    <div class="app-doc-meta">
                        <ul class="list-unstyled mb-0">
                            <li><span class="text-muted">Created:</span> {{ \Carbon\Carbon::parse($c['created_at'])->format('d-m-Y H:i') }}</li>
                            <li><span class="text-muted">Updated:</span> {{ \Carbon\Carbon::parse($c['updated_at'])->format('d-m-Y H:i') }}</li>
                        </ul>
                    </div>
                    <div class="app-card-actions">
                        <div class="dropdown">
                            <div class="dropdown-toggle no-toggle-arrow" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </div>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $c['id'] }}">
                                        <i class="fas fa-edit me-2"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ url('/category/'.$c['id']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editCategoryModal{{ $c['id'] }}" tabindex="-1" aria-labelledby="editCategoryLabel{{ $c['id'] }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryLabel{{ $c['id'] }}">Edit Category: {{ $c['categoryName'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ url('/category/'.$c['id']) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" value="{{ $c['categoryName'] }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoryImage" class="form-label">Category Image</label>
                                <input type="file" class="form-control" id="categoryImage" name="categoryImage">
                            </div>
                            <div class="mb-3">
                                <img src="{{ $c['categoryImage'] }}" alt="{{ $c['categoryName'] }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>


<script>
    // Search Categories by Name
function searchCategories() {
    let input = document.getElementById('search-docs').value.toLowerCase();
    let cards = document.querySelectorAll('.app-card');

    cards.forEach(card => {
        let categoryName = card.querySelector('.app-doc-title').innerText.toLowerCase();
        if (categoryName.includes(input)) {
            card.parentElement.style.display = "";
        } else {
            card.parentElement.style.display = "none";
        }
    });
}

// Filter Categories by Time
function filterCategories() {
    let filterValue = document.getElementById('filter-type').value;
    let currentDate = new Date();
    let cards = document.querySelectorAll('.app-card');

    cards.forEach(card => {
        let createdDate = new Date(card.querySelector('.app-doc-meta li:nth-child(1) span').innerText);
        let diffInDays = (currentDate - createdDate) / (1000 * 3600 * 24); // Calculate difference in days

        if (filterValue === 'all') {
            card.parentElement.style.display = "";
        } else if (filterValue === 'week' && diffInDays <= 7) {
            card.parentElement.style.display = "";
        } else if (filterValue === 'month' && diffInDays <= 30) {
            card.parentElement.style.display = "";
        } else if (filterValue === 'three_months' && diffInDays <= 90) {
            card.parentElement.style.display = "";
        } else {
            card.parentElement.style.display = "none";
        }
    });
}

</script>
