<!-- Modal -->
<div class="modal fade" id="uploadCategory" tabindex="-1" aria-labelledby="uploadCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Use larger modal size for better layout -->
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="uploadCategoryLabel">Upload Category</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('addCategory') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Category Name input -->
                    <div class="mb-4">
                        <label class="form-label" for="categoryName">Category Name</label>
                        <input type="text" id="categoryName" class="form-control" name="categoryName" placeholder="Enter Category Name" required />
                    </div>

                    <!-- Image input -->
                    <div class="mb-4">
                        <label class="form-label" for="categoryImage">Upload Category Image</label>
                        <input type="file" id="categoryImage" class="form-control" name="categoryImage" required />
                    </div>

                    <!-- Submit button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary text-white">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert for Success and Error messages -->
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ $errors->first() }}',
        });
    @endif
</script>
