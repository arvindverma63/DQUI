<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="app bg-light">
    @include('partials.header')

    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">
                <div class="tab-content" id="orders-table-tab-content">
                    <div class="tab-pane fade show active" id="orders-all" role="tabpanel"
                        aria-labelledby="orders-all-tab">
                        <div class="app-card app-card-orders-table shadow-sm mb-5">
                            <div class="app-card-body" style="padding: 20px;">
                                <div class="table-responsive">
                                    <table class="table app-table-hover table-bordered mb-0 text-left"
                                        id="stocks-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User ID</th>
                                                <th>Total</th>
                                                <th>Tax</th>
                                                <th>Discount</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody id="transaction-tbody">
                                        </tbody>
                                    </table>
                                    <div id="item-modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background: white; padding: 20px; border: 1px solid #ccc; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                                        <div id="modal-body">
                                            <!-- Items will be dynamically inserted here -->
                                        </div>
                                        <button onclick="closeModal()">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Include the script after the DOM -->
    <script src="{{ asset('assets/js/transactions/transactions.js') }}"></script>
</body>
</html>
