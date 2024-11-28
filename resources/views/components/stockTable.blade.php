@foreach ($stocks as $stock)
<tr>
    <td class="cell">#{{ $stock['id'] }}</td>
    <td class="cell">{{ $stock['itemName'] }}</td>
    <td class="cell">{{ number_format($stock['quantity'], 3) }}</td>
    <td class="cell">{{ $stock['unit'] }}</td>
    <td class="cell">{{ $stock['supplier']['supplierName'] }}</td>
    <td class="cell">
        <!-- Delete Supplier Action -->
        <a href="{{ route('delete.stock', $stock['id']) }}" onclick="return confirm('Are you sure you want to delete this stock?')">
            <i class="fa-solid fa-trash" style="cursor: pointer;"></i>
        </a>

        <!-- Edit Stock Modal Trigger -->
        <i class="fa-regular fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#edit-stock-{{ $stock['id'] }}" style="cursor: pointer;"></i>
    </td>
</tr>

<!-- Modal for each stock -->
<div class="modal fade" id="edit-stock-{{ $stock['id'] }}" tabindex="-1" aria-labelledby="edit-stockLabel-{{ $stock['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-stockLabel-{{ $stock['id'] }}">Edit Stock - {{ $stock['itemName'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Stock Form -->
                <form id="stockForm-{{ $stock['id'] }}" method="POST" action="/updatestock/{{$stock['id']}}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="itemName-{{ $stock['id'] }}" class="form-label small">Item Name</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $stock['itemName'] }}" id="itemName-{{ $stock['id'] }}" name="itemName" required placeholder="Enter item name">
                    </div>
                    <div class="mb-3">
                        <label for="quantity-{{ $stock['id'] }}" class="form-label small">Quantity</label>
                        <input type="number" step="0.001" class="form-control form-control-sm" value="{{ $stock['quantity'] }}" id="quantity-{{ $stock['id'] }}" name="quantity" required placeholder="Enter quantity">
                    </div>
                    <div class="mb-3">
                        <label for="unit-{{ $stock['id'] }}" class="form-label small">Unit</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $stock['unit'] }}" id="unit-{{ $stock['id'] }}" name="unit" required placeholder="Enter unit">
                    </div>
                    <div class="mb-3">
                        <label for="supplier-{{ $stock['id'] }}" class="form-label small">Supplier</label>
                        <select class="form-select" id="supplier-{{ $stock['id'] }}" name="supplierId">
                            <option value="{{$stock['supplier']['id']}}">{{$stock['supplier']['supplierName']}}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" form="stockForm-{{ $stock['id'] }}">Save Stock</button>
            </div>
        </div>
    </div>
</div>
@endforeach

