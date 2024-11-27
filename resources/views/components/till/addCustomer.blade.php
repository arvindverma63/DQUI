<!-- Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerLabel">Customer Management</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Customer Selection Section -->
                    <div class="col-md-6 border-end">
                        <h5>Select Customer</h5>

                        <!-- Search Bar -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search customers..." id="customerSearch">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                        </div>

                        <!-- Customer List -->
                        <div class="list-group" id="customerList" style="max-height: 300px; overflow-y: auto;">
                            <!-- Dynamically populated customer items -->
                        </div>
                    </div>

                    <script src="{{asset('assets/js/till/customer.js')}}"></script>



                    <!-- Add Customer Form Section -->
                    <div class="col-md-6">
                        <h5>Add New Customer</h5>
                        <form id="customerForm" method="post" action="{{ route('addCustomer') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="customerName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customerEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="customerPhone" name="phoneNumber"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="customerAddress" class="form-label">Address</label>
                                <textarea class="form-control" id="customerAddress" name="address" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="customerForm" class="btn btn-primary text-white">Save changes</button>
            </div>
        </div>
    </div>
</div>
