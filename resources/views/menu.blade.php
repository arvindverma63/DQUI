<!DOCTYPE html>
<html lang="en">
@include('partials.head')
<body class="app">
    @include('partials.header')
    @include('components.upload-menu')
    <div class="app-wrapper">
        <div class="app-content pt-3 p-md-3 p-lg-4">
            <div class="container-xl">

                <div class="row g-3 mb-4 align-items-center justify-content-between">
                    <div class="col-auto">
                        <h1 class="app-page-title mb-0">Menu</h1>
                    </div>
                    <div class="col-auto">
                        <div class="page-utilities">
                            <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                                <div class="col-auto">
                                    <input type="text" id="search-menu" class="form-control" placeholder="Search by item name" onkeyup="searchMenu()">
                                </div><!--//col-->
                                <div class="col-auto">
                                    <select class="form-select w-auto" id="filter-date" onchange="filterByDate()">
                                        <option value="all">All</option>
                                        <option value="week">This week</option>
                                        <option value="month">This month</option>
                                        <option value="three_months">Last 3 months</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <a class="btn app-btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                                        <i class="fas fa-plus"></i> Add Menu
                                    </a>
                                </div>
                            </div><!--//row-->
                        </div><!--//table-utilities-->
                    </div><!--//col-auto-->
                </div><!--//row-->

                <div class="row">
                    @include('components.menu-table')
                </div><!--//row-->


            </div><!--//container-fluid-->
        </div><!--//app-content-->

        
    </div><!--//app-wrapper-->

    @include('partials.footer')

    <script>
        // Search function for filtering the menu items by name
        function searchMenu() {
            const input = document.getElementById("search-menu").value.toLowerCase();
            const cards = document.querySelectorAll(".app-card");

            cards.forEach(card => {
                const itemName = card.querySelector(".app-doc-title").textContent.toLowerCase();
                card.style.display = itemName.includes(input) ? "" : "none";
            });
        }

        // Filter by date (dummy logic, replace with actual logic)
        function filterByDate() {
            const filterValue = document.getElementById("filter-date").value;
            const cards = document.querySelectorAll(".app-card");

            cards.forEach((card, index) => {
                if (filterValue === 'all') {
                    card.style.display = "";
                } else if (filterValue === 'week') {
                    card.style.display = index % 2 === 0 ? "" : "none"; // Show only even cards (for demo)
                } else if (filterValue === 'month') {
                    card.style.display = index % 3 === 0 ? "" : "none"; // Show only cards divisible by 3 (for demo)
                } else {
                    card.style.display = "none"; // Hide all cards
                }
            });
        }
    </script>

</body>
</html>
