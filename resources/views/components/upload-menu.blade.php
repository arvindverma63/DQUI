<!-- Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMenuLabel">Add New Menu Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('menu.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Item Name -->
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Enter item name" required>
                    </div>

                    <!-- Category ID -->
                    <div class="mb-3">
                        <label for="categoryId" class="form-label">Category</label>
                        <select class="form-control" id="categoryId" name="categoryId" required>
                            <option value="">Select a category</option>
                        </select>
                    </div>

                    <!-- Item Image -->
                    <div class="mb-3">
                        <label for="itemImage" class="form-label">Item Image</label>
                        <input type="file" class="form-control" id="itemImage" name="itemImage" accept="image/*">
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter item price" required>
                    </div>

                    <!-- Stock Items Section -->
                    <div class="mb-3" id="stock-items-section">
                        <label for="stockId" class="form-label">Stock Items</label>
                        <div class="input-group stock-item mb-3">
                            <select class="form-control stock-select" name="stockItems[0][stockId]" required>
                                <option value="">Select a stock item</option>
                            </select>
                            <input type="number" class="form-control stock-quantity" name="stockItems[0][quantity]" placeholder="Quantity" step="0.001" min="0.001" required>
                            <button type="button" class="btn btn-success add-stock-btn">+</button>
                            <div class="invalid-feedback">Quantity must be greater than zero and not exceed available stock.</div>
                        </div>
                    </div>

                    <!-- Hidden Restaurant ID -->
                    <input type="hidden" name="restaurantId" value="{{ Session::get('restaurant_id') }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Menu Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fetch categories and stock items to populate selects -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('categoryId');
        const stockItemsSection = document.getElementById('stock-items-section');

        // Fetch categories
        fetch('/getAllCategories')
            .then(response => response.json())
            .then(data => {
                const categories = data.category;
                categorySelect.innerHTML = '<option value="">Select a category</option>';
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.categoryName;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching categories:', error));

        // Fetch stock items for each stock dropdown
        function populateStockSelect(stockSelect) {
            fetch('/getAllStocks')
                .then(response => response.json())
                .then(data => {
                    const stocks = data.data; // Ensure the correct key is used to access the stock data
                    stockSelect.innerHTML = '<option value="">Select a stock item</option>';
                    stocks.forEach(stock => {
                        const option = document.createElement('option');
                        option.value = stock.id;
                        option.dataset.availableQuantity = stock.quantity; // Store available quantity in a data attribute
                        option.textContent = `${stock.itemName} (${stock.quantity} ${stock.unit})`;
                        stockSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching stocks:', error));
        }

        // Populate the initial stock select
        populateStockSelect(document.querySelector('.stock-select'));

        // Add more stock items dynamically
        stockItemsSection.addEventListener('click', function(event) {
            if (event.target.classList.contains('add-stock-btn')) {
                event.preventDefault();
                const stockItemCount = stockItemsSection.querySelectorAll('.stock-item').length;
                const newStockItem = document.createElement('div');
                newStockItem.classList.add('stock-item', 'mb-3');
                newStockItem.innerHTML = `
                    <label for="stockId" class="form-label">Select Stock Item</label>
                    <div class="input-group">
                        <select class="form-control stock-select" name="stockItems[${stockItemCount}][stockId]" required>
                            <option value="">Select a stock item</option>
                        </select>
                        <input type="number" class="form-control stock-quantity" name="stockItems[${stockItemCount}][quantity]" placeholder="Quantity" step="0.001" min="0.001" required>
                        <button type="button" class="btn btn-danger remove-stock-btn">-</button>
                    </div>
                    <div class="invalid-feedback">Quantity must be greater than zero and not exceed available stock.</div>
                `;
                stockItemsSection.appendChild(newStockItem);
                populateStockSelect(newStockItem.querySelector('.stock-select'));
            } else if (event.target.classList.contains('remove-stock-btn')) {
                event.preventDefault();
                event.target.closest('.stock-item').remove();
            }
        });

        // Validate stock quantity on change
        stockItemsSection.addEventListener('input', function(event) {
            if (event.target.classList.contains('stock-quantity')) {
                const quantityInput = event.target;
                const stockSelect = quantityInput.closest('.stock-item').querySelector('.stock-select');
                const selectedOption = stockSelect.options[stockSelect.selectedIndex];
                const availableQuantity = parseFloat(selectedOption.dataset.availableQuantity);

                // Get the entered quantity
                const enteredQuantity = parseFloat(quantityInput.value);

                // Validate quantity
                if (enteredQuantity <= 0 || enteredQuantity > availableQuantity) {
                    quantityInput.classList.add('is-invalid');
                    quantityInput.setCustomValidity('Invalid quantity');
                } else {
                    quantityInput.classList.remove('is-invalid');
                    quantityInput.setCustomValidity('');
                }
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
