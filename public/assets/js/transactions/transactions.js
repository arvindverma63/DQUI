document.addEventListener('DOMContentLoaded', function () {
    // Fetch initial transaction data
    fetch('/transaction')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch transaction details.');
            }
            return response.json();
        })
        .then(data => {
            if (data.token && data.restaurantId && data.baseUrl) {
                getTransaction(data.token, data.restaurantId, data.baseUrl);
            } else {
                throw new Error('Invalid data from /transaction endpoint.');
            }
        })
        .catch(error => {
            console.error('Error fetching transaction data:', error);
        });

    function getTransaction(token, restaurantId, baseUrl) {
        fetch(`${baseUrl}/transactions/${restaurantId}`, {
            method: "GET",
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`, // Fixed header typo
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch transactions.');
                }
                return response.json();
            })
            .then(data => {
                populateTransactionTable(data); // Populate table and initialize DataTable
            })
            .catch(error => {
                console.error('Error fetching transactions:', error);
            });
    }

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        return new Date(dateString).toLocaleString('en-US', options);
    }

    function populateTransactionTable(data) {
        // Initialize or retrieve the DataTable instance
        let table = $('#stocks-table').DataTable({
            destroy: true, // Ensure it doesn't reinitialize multiple times
            responsive: true,
            paging: true,
            searching: true,
            ordering: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'btn btn-success text-white',
                    title: 'Transactions Data'
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export to PDF',
                    className: 'btn btn-danger text-white',
                    title: 'Transactions Data',
                    orientation: 'landscape', // Adjust orientation for better layout
                    pageSize: 'A4'
                }
            ]
        });

        // Clear existing data in the DataTable
        table.clear();

        // Add rows dynamically using DataTable's API
        data.forEach(transaction => {
            const itemsStr = encodeURIComponent(JSON.stringify(transaction.items));
            table.row.add([
                `<span class="open-modal" data-items="${itemsStr}" style="cursor: pointer; text-decoration: underline;">${transaction.id}</span>`,
                transaction.user_id,
                transaction.total,
                transaction.tax,
                transaction.discount,
                formatDate(transaction.created_at) // Format the date
            ]);
        });

        // Redraw the table to reflect the new data
        table.draw();

        // Re-attach event listener dynamically after the table is redrawn
        attachModalListeners();
    }

    function attachModalListeners() {
        // Attach event listener for the modal opening dynamically
        $('#stocks-table tbody').off('click').on('click', '.open-modal', function () {
            const encodedItems = $(this).data('items');
            showItemsModal(encodedItems);
        });
    }

    function showItemsModal(encodedItems) {
        try {
            // Decode and parse the items array
            const parsedString = JSON.parse(decodeURIComponent(encodedItems));

            // Check if items is a stringified array and parse it again
            const items = typeof parsedString === 'string' ? JSON.parse(parsedString) : parsedString;

            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = ''; // Clear previous content

            // Check if items is an array
            if (Array.isArray(items) && items.length > 0) {
                items.forEach(item => {
                    const itemName = item.itemName ? item.itemName : "Unknown Item";
                    const price = item.price ? parseFloat(item.price).toFixed(2) : "0.00";
                    const quantity = item.quantity ? item.quantity : "0";

                    const itemRow = `
                        <p>
                            <strong>${itemName}</strong>:
                            Quantity: ${quantity},
                            Price: ${price}
                        </p>
                    `;
                    modalBody.innerHTML += itemRow;
                });
            } else {
                // Handle non-array or empty items
                modalBody.innerHTML = `<p>No items found.</p>`;
            }

            // Show the modal
            const modal = document.getElementById('item-modal');
            modal.style.display = 'block';
        } catch (error) {
            console.error('Error parsing items:', error);
            // Display error message in modal
            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = `<p>Failed to load items. Please try again later.</p>`;
            const modal = document.getElementById('item-modal');
            modal.style.display = 'block';
        }
    }

    window.closeModal = function () {
        const modal = document.getElementById('item-modal');
        modal.style.display = 'none';
    };
});
