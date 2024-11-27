@foreach ($suppliers as $supplier)
<tr>
    <td class="cell">#{{ $supplier['id'] }}</td>
    <td class="cell">{{ $supplier['supplierName'] }}</td>
    <td class="cell">{{ $supplier['email'] }}</td>
    <td class="cell">{{ $supplier['phoneNumber'] }}</td>
    <td class="cell">{{ $supplier['rawItem'] }}</td>
    <td class="cell">
        <!-- Delete Supplier Action -->
        <a href="{{ route('delete.supplier', $supplier['id']) }}" onclick="return confirm('Are you sure you want to delete this supplier?')">
            <i class="fa-solid fa-trash" style="cursor: pointer;"></i>
        </a>

        <!-- Edit Supplier Modal Trigger -->
        <i class="fa-regular fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#edit-supplier-{{ $supplier['id'] }}" style="cursor: pointer;"></i>
    </td>
</tr>

<!-- Modal for each supplier -->
<div class="modal fade" id="edit-supplier-{{ $supplier['id'] }}" tabindex="-1" aria-labelledby="edit-supplierLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-supplierLabel">Edit Supplier - {{ $supplier['supplierName'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Supplier Form -->
                <form id="supplierForm-{{ $supplier['id'] }}" method="POST" action="{{ route('update.supplier', $supplier['id']) }}">
                    @csrf <!-- Add this if using Laravel Blade templates -->
                    @method('PUT') <!-- Ensure PUT method is used for updating -->

                    <div class="mb-3">
                        <label for="supplierName-{{ $supplier['id'] }}" class="form-label small">Supplier Name</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $supplier['supplierName'] }}" id="supplierName-{{ $supplier['id'] }}" name="supplierName" required placeholder="Enter supplier name">
                    </div>
                    <div class="mb-3">
                        <label for="email-{{ $supplier['id'] }}" class="form-label small">Email</label>
                        <input type="email" class="form-control form-control-sm" value="{{ $supplier['email'] }}" id="email-{{ $supplier['id'] }}" name="email" required placeholder="Enter supplier email">
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber-{{ $supplier['id'] }}" class="form-label small">Phone Number</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $supplier['phoneNumber'] }}" id="phoneNumber-{{ $supplier['id'] }}" name="phoneNumber" required placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label for="rawItem-{{ $supplier['id'] }}" class="form-label small">Raw Item</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $supplier['rawItem'] }}" id="rawItem-{{ $supplier['id'] }}" name="rawItem" required placeholder="Enter raw item">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" form="supplierForm-{{ $supplier['id'] }}">Save Supplier</button>
            </div>
        </div>
    </div>
</div>
@endforeach
