<div class="container-xl">
    <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col-auto">
            <h1 class="app-page-title mb-0">Orders</h1>
        </div>
        <div class="col-auto">
            <div class="page-utilities">
                <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                    <div class="col-auto">
                        <input type="text" id="search-orders" class="form-control search-orders" placeholder="Search" onkeyup="filterOrders()">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content" id="orders-table-tab-content">
        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
            <div class="app-card app-card-orders-table shadow-sm mb-5">
                <div class="app-card-body">
                    <div class="table-responsive" style="padding: 20px;">
                        <table class="table app-table-hover mb-0 text-left" id="orders-table">
                            <thead>
                                <tr>
                                    <th class="cell">Order ID</th>
                                    <th class="cell">Items</th>
                                    <th class="cell">Customer</th>
                                    <th class="cell">Table Number</th>
                                    <th class="cell">Date</th>
                                    <th class="cell">Status</th>
                                    <th class="cell">Total</th>
                                    <th class="cell">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orders-tbody">
                                @foreach ($orders as $order)
                                <tr>
                                    <td class="cell">{{ $order['order_id'] }}</td>
                                    <td class="cell">
                                        @foreach ($order['order_details'] as $item)
                                            <div>{{ $item['item_name'] ?? 'Item Name N/A' }} (x{{ $item['quantity'] }})</div>
                                        @endforeach
                                    </td>
                                    <td class="cell">{{ $order['user']['name'] ?? 'N/A' }}</td>
                                    <td class="cell">{{ $order['table_number'] }}</td>
                                    <td class="cell">{{ \Carbon\Carbon::parse($order['created_at'])->format('d M Y') }}</td>
                                    <td class="cell">
                                        <span class="badge {{ $order['status'] == 'processing' ? 'bg-warning' : ($order['status'] == 'complete' ? 'bg-success' : 'bg-danger') }}">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </td>
                                    <td class="cell">₹{{ number_format($order['total'], 2) }}</td>
                                    <td class="cell">
                                        <!-- Button triggers offcanvas for order details -->
                                        <button class="btn-sm app-btn-secondary" data-bs-toggle="offcanvas" data-bs-target="#orderDetailsCanvas{{ $order['order_id'] }}">
                                            View
                                        </button>
                                    </td>
                                </tr>

                                <!-- Off-canvas for this order -->
                                <div class="offcanvas offcanvas-end" tabindex="-1" id="orderDetailsCanvas{{ $order['order_id'] }}" aria-labelledby="orderDetailsCanvasLabel{{ $order['order_id'] }}">
                                    <div class="offcanvas-header">
                                        <h5 class="offcanvas-title" id="orderDetailsCanvasLabel{{ $order['order_id'] }}">Order Details - #{{ $order['order_id'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <p><strong>Customer:</strong> {{ $order['user']['name'] ?? 'N/A' }}</p>
                                        <p><strong>Table Number:</strong> {{ $order['table_number'] }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($order['status']) }}</p>
                                        <p><strong>Total:</strong> ₹{{ number_format($order['total'], 2) }}</p>
                                        <p><strong>Items:</strong></p>
                                        <ul>
                                            @foreach ($order['order_details'] as $item)
                                            <li>{{ $item['item_name'] ?? 'Item Name N/A' }} (x{{ $item['quantity'] }})</li>
                                            @endforeach
                                        </ul>
                                        <div class="mt-3">
                                            <form action="{{ route('updateOrderStatus', $order['order_id']) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="complete">
                                                <button type="submit" class="btn btn-success text-white">Mark as Complete</button>
                                            </form>
                                            <form action="{{ route('updateOrderStatus', $order['order_id']) }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="status" value="reject">
                                                <button type="submit" class="btn btn-danger text-white">Reject Order</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav class="app-pagination" id="pagination">
                <!-- Pagination links will go here -->
            </nav>
        </div>
    </div>
</div>
