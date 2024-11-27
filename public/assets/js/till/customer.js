let allCustomers = []; // Store all customers to filter from

// Fetch customer data and store in allCustomers
function fetchCustomerData() {
    fetch('/getCustomer')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok, status: ${response.status}`);
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Fetched customer data:', responseData);

            // Access the actual customer data directly
            const customerData = responseData.data && Array.isArray(responseData.data) ? responseData.data : [];
            if (customerData.length === 0) {
                console.warn('No customer data available.');
            }

            allCustomers = customerData; // Save data to allCustomers array
            renderCustomerList(allCustomers); // Initial render of the customer list

            // Store the fetched customers in local storage
            localStorage.setItem('customers', JSON.stringify(allCustomers));
        })
        .catch(error => {
            console.error('Error fetching customer data:', error);
            const customerList = document.getElementById('customerList');
            if (customerList) {
                customerList.innerHTML = '<p>Error loading customers. Please try again later.</p>';
            }
        });
}

// Render customer list based on filtered data
function renderCustomerList(customers) {
    let customerListHtml = '';

    if (customers.length === 0) {
        customerListHtml = '<p class="text-center text-muted">No customers available.</p>';
    } else {
        customers.forEach(e => {
            customerListHtml += `
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-id="${e.id}" data-name="${e.name}">
                    <span>${e.name}</span>
                    <span class="badge bg-primary rounded-pill">ID: ${e.id}</span>
                </a>
            `;
        });
    }

    const customerList = document.getElementById('customerList');
    if (customerList) {
        customerList.innerHTML = customerListHtml;
    } else {
        console.warn('Element with ID "customerList" not found.');
    }
}

// Search function to filter customers
function searchCustomers() {
    const searchInput = document.getElementById('customerSearch');
    if (!searchInput) {
        console.warn('Search input element not found.');
        return;
    }

    const searchQuery = searchInput.value.toLowerCase();
    const filteredCustomers = allCustomers.filter(customer =>
        customer.name && customer.name.toLowerCase().includes(searchQuery)
    );

    renderCustomerList(filteredCustomers);
}

// Add event listeners for search input and button
const searchInput = document.getElementById('customerSearch');
if (searchInput) {
    searchInput.addEventListener('input', searchCustomers);
} else {
    console.warn('Search input element with ID "customerSearch" not found.');
}

const searchButton = document.getElementById('searchButton');
if (searchButton) {
    searchButton.addEventListener('click', searchCustomers);
} else {
    console.warn('Search button element with ID "searchButton" not found.');
}

// Event delegation to handle clicks on dynamically generated customer items
const customerListElement = document.getElementById('customerList');
if (customerListElement) {
    customerListElement.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default behavior of <a> tags
        const target = event.target.closest('.list-group-item');
        if (target) {
            const customerId = target.getAttribute('data-id'); // Get customer ID from data attribute
            const customerName = target.getAttribute('data-name'); // Get customer name from data attribute
            addData(customerId, customerName);
        }
    });
} else {
    console.warn('Customer list container with ID "customerList" not found.');
}

// Function to add customer data (e.g., for tracking)
function addData(id, name) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('name', name);

    fetch('/addData', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Network response was not ok, status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Data successfully added:', data);

        const custNameElement = document.getElementById('custName');
        if (custNameElement) {
            custNameElement.innerText = data.data.name || 'Unknown Customer'; // Update or set default
        } else {
            console.warn('Element with ID "custName" not found.');
        }

        // Store the current customer in local storage
        localStorage.setItem('currentCustomer', JSON.stringify({ id, name }));
    })
    .catch(error => {
        console.error('Error adding data for customer:', error);
    });
}

// On page load, fetch customer data and render customer list
document.addEventListener('DOMContentLoaded', function() {
    fetchCustomerData();

    // Load previously selected customer (if any) from local storage
    const storedCustomer = localStorage.getItem('currentCustomer');
    if (storedCustomer) {
        const { id, name } = JSON.parse(storedCustomer);
        const custNameElement = document.getElementById('custName');
        if (custNameElement) {
            custNameElement.innerText = name;
        }
    }
});
