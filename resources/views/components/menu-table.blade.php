@if(empty($menuItems['data']['menus']))
    <p class="text-center">No menu items available.</p>
@else
    <!-- Table structure for DataTable -->
    <table id="menuTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menuItems['data']['menus'] as $menuItem)
                <tr>
                    <td>
                        <img src="{{ $menuItem['itemImage'] }}" alt="{{ $menuItem['itemName'] }}" style="width: 50px; height: auto;">
                    </td>
                    <td>{{ $menuItem['itemName'] }}</td>
                    <td>₹{{ $menuItem['price'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($menuItem['created_at'])->format('d-m-Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($menuItem['updated_at'])->format('d-m-Y H:i') }}</td>
                    <td>
                        <!-- Edit Menu Button -->
                        <a href="#" class="btn btn-sm btn-primary text-white" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menuItem['id'] }}">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <!-- Delete Menu Form -->
                        <form action="{{ url('/menu/'.$menuItem['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirm('Are you sure you want to delete this menu item?');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                        <!-- Edit Stock Button -->
                        <a href="#" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal" data-bs-target="#editStockModal{{ $menuItem['id'] }}">
                            <i class="fas fa-box"></i> Edit Stock
                        </a>
                    </td>
                </tr>

                <!-- Edit Menu Modal -->
                <div class="modal fade" id="editMenuModal{{ $menuItem['id'] }}" tabindex="-1" aria-labelledby="editMenuLabel{{ $menuItem['id'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMenuLabel{{ $menuItem['id'] }}">Edit Menu Item: {{ $menuItem['itemName'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/menu', $menuItem['id']) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <!-- Item Name Field -->
                                    <div class="mb-3">
                                        <label for="itemName" class="form-label">Item Name</label>
                                        <input type="text" class="form-control" id="itemName" name="itemName" value="{{ $menuItem['itemName'] }}" required>
                                    </div>

                                    <!-- Category Dropdown -->
                                    <div class="mb-3">
                                        <label for="categoryId" class="form-label">Category</label>
                                        <select class="form-select" id="categorySelect{{ $menuItem['id'] }}" name="categoryId" required>
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>

                                    <!-- Price Field -->
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $menuItem['price'] }}" required>
                                    </div>


                                    <!-- Item Image Field -->
                                    <div class="mb-3">
                                        <label for="itemImage" class="form-label">Item Image</label>
                                        <input type="file" class="form-control" id="itemImage" name="itemImage" accept="image/jpeg, image/png, image/jpg, image/gif">
                                        @if($menuItem['itemImage'])
                                            <div class="mt-2">
                                                <img src="{{ $menuItem['itemImage'] }}" alt="Current Image" style="width: 100px; height: auto;">
                                                <p class="text-muted">Current image</p>
                                            </div>
                                        @endif
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

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Function to fetch and populate categories dynamically when the modal opens
                        const editMenuModal = document.getElementById('editMenuModal{{ $menuItem['id'] }}');
                        editMenuModal.addEventListener('show.bs.modal', function () {
                            const categorySelect = document.getElementById('categorySelect{{ $menuItem['id'] }}');

                            fetch('/getAllCategories')
                                .then(response => response.json())
                                .then(data => {
                                    const categories = data.category;
                                    categorySelect.innerHTML = '<option value="">Select a category</option>';
                                    categories.forEach(category => {
                                        const option = document.createElement('option');
                                        option.value = category.id;
                                        option.textContent = category.categoryName;

                                        // Pre-select the current category if it matches
                                        if (category.id == {{ $menuItem['categoryId'] }}) {
                                            option.selected = true;
                                        }

                                        categorySelect.appendChild(option);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error loading categories:', error);
                                });
                        });
                    });
                </script>

  <!-- Edit Stock Modal -->
  <div class="modal fade" id="editStockModal{{ $menuItem['id'] }}" tabindex="-1" aria-labelledby="editStockLabel{{ $menuItem['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStockLabel{{ $menuItem['id'] }}">Edit Stock for: {{ $menuItem['itemName'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStockForm{{ $menuItem['id'] }}" action="{{ url('updateMenuStock/' . $menuItem['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Stock Items</label>
                        <div id="stockItemsContainer{{ $menuItem['id'] }}">
                            <!-- Existing stock items from database -->
                            @foreach($menuItem['stockItems'] as $index => $stockItem)
                                <div class="row g-2 mb-2 stock-item-row">
                                    <div class="col">
                                        <select class="form-select" name="stockItems[{{ $index }}][stockId]" required>
                                            <option value="" disabled>Select Stock Item</option>
                                            @foreach($menuItems['data']['inventoryOptions'] as $inventoryItem)
                                                <option value="{{ $inventoryItem['id'] }}" {{ $stockItem['stockId'] == $inventoryItem['id'] ? 'selected' : '' }}>
                                                    {{ $inventoryItem['itemName'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input type="number" step="0.001" class="form-control" name="stockItems[{{ $index }}][quantity]" value="{{ $stockItem['quantity'] }}" placeholder="Quantity" required>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger btn-sm remove-stock-item" onclick="removeStockItemRow(this)">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" onclick="addStockItemRow({{ $menuItem['id'] }})">Add Stock Item</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

            @endforeach
        </tbody>
    </table>
@endif
<!-- Template for Dynamic Stock Row - Place Outside of the Conditional Statements -->
<template id="stock-item-template">
    <div class="row g-2 mb-2 stock-item-row">
        <div class="col">
            <select class="form-select" required>
                <option value="" disabled selected>Select Stock Item</option>
                @foreach($menuItems['data']['inventoryOptions'] as $inventoryItem)
                    <option value="{{ $inventoryItem['id'] }}">{{ $inventoryItem['itemName'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <input type="number" step="0.001" class="form-control" placeholder="Quantity" required>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-danger btn-sm remove-stock-item" onclick="removeStockItemRow(this)">&times;</button>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable

        // Function to dynamically add stock item rows
        window.addStockItemRow = function(menuId) {
            const container = document.getElementById(`stockItemsContainer${menuId}`);
            const template = document.getElementById('stock-item-template').content.cloneNode(true);

            // Update `name` attributes dynamically based on the row count
            const index = container.querySelectorAll('.stock-item-row').length;
            template.querySelector('select').setAttribute('name', `stockItems[${index}][stockId]`);
            template.querySelector('input').setAttribute('name', `stockItems[${index}][quantity]`);

            container.appendChild(template);
        };

        // Function to remove a stock item row
        window.removeStockItemRow = function(button) {
            button.closest('.stock-item-row').remove();
        };
    });
</script>
