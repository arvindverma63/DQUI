<!-- Modal -->
<div class="modal fade" id="supplier" tabindex="-1" aria-labelledby="supplierLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Supplier Form -->
                <form id="supplierForm" method="POST" action="{{route('add.supplier')}}">
                    @csrf <!-- Add this if using Laravel Blade templates -->
                    <div class="mb-3">
                        <label for="supplierName" class="form-label small">Supplier Name</label>
                        <input type="text" class="form-control form-control-sm" id="supplierName" name="supplierName" required placeholder="Enter supplier name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label small">Email</label>
                        <input type="email" class="form-control form-control-sm" id="email" name="email" required placeholder="Enter supplier email">
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label small">Phone Number</label>
                        <input type="text" class="form-control form-control-sm" id="phoneNumber" name="phoneNumber" required placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label for="rawItem" class="form-label small">Raw Item</label>
                        <input type="text" class="form-control form-control-sm" id="rawItem" name="rawItem" required placeholder="Enter raw item">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" form="supplierForm">Save Supplier</button>
            </div>
        </div>
    </div>
</div>
