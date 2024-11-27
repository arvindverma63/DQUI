<!-- Modal -->
<div class="modal fade" id="stock" tabindex="-1" aria-labelledby="addStock" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStock">Add Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Stock Form -->
                <form id="stockForm" method="POST" action="{{ route('add.stock') }}">
                    @csrf <!-- Add this if using Laravel Blade templates -->
                    <div class="mb-3">
                        <label for="itemName" class="form-label small">Item Name</label>
                        <input type="text" class="form-control form-control-sm" id="itemName" name="itemName" required placeholder="Enter item name">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label small">Quantity</label>
                        <input type="number" step="0.001" class="form-control form-control-sm" id="quantity" name="quantity" required placeholder="Enter quantity">
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label small">Unit</label>
                        <input type="text" class="form-control form-control-sm" id="unit" name="unit" required placeholder="Enter unit (e.g., kg, pcs)">
                    </div>
                    <div class="mb-3">
                        <label for="supplier" class="form-label small">Supplier</label>
                        <select class="form-select form-control-sm" id="supplier" name="supplierId" required>
                            <option selected disabled>Choose supplier</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" form="stockForm">Save Stock</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to fetch suppliers and populate the dropdown
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/get/suppliers')
            .then(response => response.json())
            .then(data => {
                const supplierSelect = document.getElementById('supplier');
                supplierSelect.innerHTML = ''; // Clear existing options
                supplierSelect.innerHTML += `<option selected disabled>Choose supplier</option>`;

                // Loop through the suppliers and add options
                data.suppliers.forEach(supplier => {
                    supplierSelect.innerHTML += `<option value="${supplier.id}">${supplier.supplierName}</option>`;
                });
            })
            .catch(error => console.error('Error fetching suppliers:', error));
    });
</script>
