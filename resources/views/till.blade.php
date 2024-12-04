<!DOCTYPE html>
<html lang="en">
@include('partials.head')

<body class="app bg-light">
    @include('partials.header')
    @include('components.upload-menu')
    @include('components.till.addCustomer')
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">

                <!-- Full Background Card Layout -->
                <div class="card bg-white shadow p-4 rounded-3">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <!-- Search/Barcode Section -->
                            <!-- Search/Barcode Section -->
                            <div
                                class="col-12 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                                <input type="text" class="form-control mb-2 mb-md-0 w-100 w-md-25 me-md-2"
                                    placeholder="Search" id="menu-search">
                                <select class="form-select mb-2 mb-md-0 w-100 w-md-25 me-md-2">
                                    <option value="{{$tableNumber}}" id="tableNumber" selected>Table Number {{$tableNumber}}</option>
                                    <!-- Add other options here -->
                                </select>
                                <div class="d-flex flex-column flex-md-row align-items-center w-100 w-md-auto">
                                    <span class="me-md-3 fw-bold" id="custName">Select Customer</span>
                                        <button class="btn btn-success btn-sm px-3" style="font-weight: bold;" data-bs-toggle="modal" data-bs-target="#addCustomerModal">+</button>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <!-- Product Section -->
                           <div class="col-md-8" id="product-col">
    <h5 class="mb-3 fw-bold text-secondary">Products</h5>
    <div class="row" id="product-container">

    </div>
</div>
<style>
    #product-col {
        max-height: 450px;
        overflow-y: auto;
        display: block; /* Ensure block display */
    }

    /* Webkit Scrollbar styling */
    #product-col::-webkit-scrollbar {
        width: 2px; /* Scrollbar width */
    }

    #product-col::-webkit-scrollbar-thumb {
        background-color: #888; /* Color of the scrollbar thumb */
        border-radius: 4px; /* Rounded corners for the scrollbar thumb */
    }

    #product-col::-webkit-scrollbar-thumb:hover {
        background-color: #555; /* Hover color for the scrollbar thumb */
    }

    #product-col::-webkit-scrollbar-track {
        background: #f1f1f1; /* Track color */
        border-radius: 4px; /* Rounded corners for the track */
    }
</style>


                            <!-- Cart Section -->
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0 text-white">Cart</h6>
                                    </div>
                                    <div class="card-body p-3 overflow-auto cart-items" style="max-height: 300px;" id="itemList">
                                        <!-- here we store item -->

                                    </div>
                                    <div class="card-footer bg-light p-3">
                                        <div class="d-flex justify-content-between mb-2" id="totalItem">

                                        </div>
                                        <hr>
                                        <div class="row mb-2 gx-2" id="additional">

                                        </div>
                                        @include('components.till.tax')
                                        @include('components.till.addDiscount')
                                        <!-- Add Discount Button -->
                                        <button class="btn btn-success btn-sm text-white mb-2" data-bs-toggle="modal" data-bs-target="#taxModal">Tax</button>
                                        <button class="btn btn-success btn-sm text-white mb-2" data-bs-toggle="modal" data-bs-target="#discountModal">Discount</button>
                                        <div class="d-flex justify-content-between fw-bold text-dark" id="totalPay">
                                            <span>Total Payable:</span>
                                            <span>₹00.00</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Section -->
                        <div class="row mt-4">
                            <div
                                class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center bg-warning p-3 rounded">
                                <span class="fs-4 mb-2 mb-md-0 fw-bold text-white">₹00.00</span>
                                <span class="fs-6 mb-2 mb-md-0 text-white">{{ date('Y-m-d') }}</span>
                                <div class="d-flex">
                                    @include('components.till.payment-type')
                                    <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#paymentModal">Pay Now</button>
                                    <a href="{{route('clear.session')}}" class="btn btn-danger">Clear</a>
                                    <script src="{{asset('assets/js/till/payment.js')}}"></script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!--//container-fluid-->
        </div><!--//app-content-->


    </div><!--//app-wrapper-->
    <script src="{{asset('assets/js/till/cart.js')}}"></script>
    @include('partials.footer')

</body>

</html>
