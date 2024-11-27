let menus = [];

// Fetch and render menus
fetch('/get/menu/data')
    .then(response => response.json())
    .then(data => {
        if (data && data[0]?.data?.menus?.length > 0) {
            menus = data[0].data.menus; // Store the menus globally
            renderMenus(menus); // Render the initial menu list
        } else {
            document.getElementById('product-container').innerHTML = `<p class="text-center text-muted">No menu items available.</p>`;
        }
    })
    .catch(error => {
        console.error('Error fetching menu data:', error);
        document.getElementById('product-container').innerHTML = `<p class="text-center text-danger">Failed to load menu items.</p>`;
    });

// Add search functionality
document.getElementById('menu-search').addEventListener('input', function () {
    const query = this.value.toLowerCase().trim();

    // Filter menus based on the search query
    const filteredMenus = menus.filter(menu =>
        menu.itemName && menu.itemName.toLowerCase().includes(query)
    );

    renderMenus(filteredMenus); // Render the filtered menu list
});

// Function to render menus based on the filtered data
function renderMenus(filteredMenus) {
    const productContainer = document.getElementById('product-container');
    productContainer.innerHTML = ''; // Clear existing content

    if (filteredMenus.length > 0) {
        filteredMenus.forEach(menu => {
            const productCard = `
    <div class="menu-item d-flex justify-content-between align-items-center py-2 border-bottom" data-id="${menu.id}" style="cursor: pointer;">
        <div class="menu-details">
            <h6 class="mb-1 fw-bold text-dark">${menu.itemName || 'Unknown'} (${menu.stock || 0})</h6>
            <p class="text-muted small mb-0">Price: ₹${menu.price || 'N/A'}</p>
        </div>
        <div>
            <button class="btn btn-primary btn-sm text-white">Add</button>
        </div>
    </div>`;

            productContainer.insertAdjacentHTML('beforeend', productCard);
        });

        // Add click event listener to menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function () {
                const menuId = this.getAttribute('data-id');
                const menuDetails = menus.find(menu => menu.id == menuId);
                console.log(menuDetails);

                // Send the clicked item's data to the server
                sendData(menuDetails);
            });
        });
    } else {
        productContainer.innerHTML = `<p class="text-center text-muted">No menu items match your search.</p>`;
    }
}


function sendData(data) {
    // Validate the data object
    if (!data || !data.id || !data.itemName || !data.price) {
        console.error('Invalid data object passed to sendData:', data);
        return;
    }

    const formData = new FormData();
    formData.append('itemId', data.id);
    formData.append('itemName', data.itemName);
    formData.append('price', data.price);

    fetch('/addData', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF protection for Laravel
        },
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with status ${response.status}`);
            }
            return response.json();
        })
        .then(responseData => {
            console.log('Data successfully sent to the server:', responseData);

            // Validate response structure
            if (!responseData || !responseData.data || !responseData.data.items) {
                console.error('Invalid server response:', responseData);
                return;
            }

            const itemsObject = responseData.data.items || {};
            const items = Object.values(itemsObject);

            if (items.length === 0) {
                console.warn('No items found in the server response.');
                return;
            }

            // Find the updated item
            const item = items.find(item => item.itemId == data.id); // Loose equality to handle type differences
            if (!item) {
                console.warn(`Item with ID ${data.id} not found in the server response.`);
                return;
            }

            const { itemId, itemName, quantity, price } = item;

            // Update existing cart item or add a new one
            const existingCartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            if (existingCartItem) {
                existingCartItem.querySelector('.item-quantity').textContent = `Quantity: ${quantity}`;
                existingCartItem.querySelector('.item-subtotal').textContent = `Subtotal: ₹${(price * quantity).toFixed(2)}`;
            } else {
                const cartContainer = document.querySelector('.cart-items');
                const cartItemHTML = `
                <div class="cart-item d-flex justify-content-between align-items-center py-2 border-bottom" data-id="${itemId}">
                    <div class="item-info">
                        <h6 class="mb-1">${itemName}
                            <i class="fa-solid fa-circle-xmark" style="color: #ff0000; cursor: pointer;" onclick="removeItem(${itemId})"></i>
                        </h6>
                        <small class="text-muted item-quantity">Quantity: ${quantity}</small>
                    </div>
                    <div class="item-pricing text-end">
                        <p class="mb-1">₹${parseFloat(price).toFixed(2)}</p>
                        <small class="text-muted item-subtotal">Subtotal: ₹${(price * quantity).toFixed(2)}</small>
                    </div>
                </div>`;
                cartContainer.insertAdjacentHTML('beforeend', cartItemHTML);
            }

            updateCartTotals(); // Update totals after adding the item
        })
        .catch(error => {
            console.error('Error sending data to the server:', error.message || error);
            alert('An error occurred while updating the cart. Please try again.');
        });
}

// Function to update cart totals
function updateCartTotals() {
    const items = document.querySelectorAll('.cart-item');
    let totalQuantity = 0;
    let totalPrice = 0;

    items.forEach(item => {
        const quantity = parseInt(item.querySelector('.item-quantity').textContent.replace('Quantity: ', ''), 10);
        const subtotal = parseFloat(item.querySelector('.item-subtotal').textContent.replace('Subtotal: ₹', ''));
        totalQuantity += quantity;
        totalPrice += subtotal;
    });

    const totalQuantityElement = document.getElementById('cartTotalQuantity');
    const totalPriceElement = document.getElementById('cartTotalPrice');

    if (totalQuantityElement) {
        totalQuantityElement.textContent = `Total Items: ${totalQuantity}`;
    }

    if (totalPriceElement) {
        totalPriceElement.textContent = `Total: ₹${totalPrice.toFixed(2)}`;
    }
}
// Fetch and render menus (already defined above)
// ...

// Function to remove an item from the cart
function removeItem(itemId) {
    // Confirm removal with the user (optional)
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    // Prepare the form data for removal
    const formData = new FormData();
    formData.append('itemId', itemId);

    fetch('/removeItem', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF protection for Laravel
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log('Item removed successfully:', data);

            // Remove the item from the DOM
            const itemElement = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            } else {
                console.warn(`Item with ID ${itemId} not found in the DOM.`);
            }

            // Update cart totals after removing the item
            updateCartTotals();
        })
        .catch(error => {
            console.error('Error removing item:', error);
        });
}
// Global variables to store tax, discount, total price, and total payable price
let globalTaxValue = 0;
let globalDiscountValue = 0;
let globalTotalPrice = 0;
let globalTotalPayable = 0;

// Function to update cart totals
function updateCartTotals() {
    const items = document.querySelectorAll('.cart-item');
    let totalQuantity = 0;
    let totalPrice = 0;

    items.forEach(item => {
        const quantity = parseInt(item.querySelector('.item-quantity').textContent.replace('Quantity: ', ''), 10);
        const subtotal = parseFloat(item.querySelector('.item-subtotal').textContent.replace('Subtotal: ₹', ''));
        totalQuantity += quantity;
        totalPrice += subtotal;
    });

    globalTotalPrice = totalPrice;
    globalTotalPayable = totalPrice; // Default to subtotal if no tax/discount is provided

    const totalQuantityElement = document.getElementById('cartTotalQuantity');
    const totalPriceElement = document.getElementById('cartTotalPrice');

    if (totalQuantityElement) {
        totalQuantityElement.textContent = `Total Items: ${totalQuantity}`;
    }

    if (totalPriceElement) {
        totalPriceElement.textContent = `Total: ₹${globalTotalPrice.toFixed(2)}`;
    }

    updateTotalPayable(globalTotalPrice, totalQuantity);
}

// Function to remove an item from the cart
function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    const formData = new FormData();
    formData.append('itemId', itemId);

    fetch('/removeItem', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log('Item removed successfully:', data);

            const itemElement = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            } else {
                console.warn(`Item with ID ${itemId} not found in the DOM.`);
            }

            updateCartTotals();
        })
        .catch(error => {
            console.error('Error removing item:', error);
        });
}

// Update total payable
function updateTotalPayable(totalPrice, count) {
    globalTotalPayable = totalPrice;
    document.getElementById('totalPay').innerHTML = `<span>Total Payable:</span> <span>₹${globalTotalPayable.toFixed(2)}</span>`;
    document.getElementById('totalItem').innerHTML = `<span>Total Items: ${count}</span> <span>₹${totalPrice.toFixed(2)}</span>`;
}

// Add tax and discount input event listeners once
document.getElementById('taxInput').addEventListener('input', applyTaxAndDiscount);
document.getElementById('discountInput').addEventListener('input', applyTaxAndDiscount);

// Function to apply tax and discount together
function applyTaxAndDiscount() {
    const taxPercentage = parseFloat(document.getElementById('taxInput').value);
    const discountPercentage = parseFloat(document.getElementById('discountInput').value);
    
    let newTotalPayable = globalTotalPrice;

    if (!isNaN(taxPercentage) && taxPercentage > 0) {
        globalTaxValue = globalTotalPrice * (taxPercentage / 100);
        newTotalPayable += globalTaxValue;
    } else {
        globalTaxValue = 0;
    }

    if (!isNaN(discountPercentage) && discountPercentage > 0) {
        globalDiscountValue = globalTotalPrice * (discountPercentage / 100);
        newTotalPayable -= globalDiscountValue;
    } else {
        globalDiscountValue = 0;
    }

    globalTotalPayable = newTotalPayable < 0 ? 0 : newTotalPayable;

    document.getElementById('additional').innerHTML = `
        <div class="col-6">Discount: <span>₹${globalDiscountValue.toFixed(2)}</span></div>
        <div class="col-6 text-end">GST: <span>₹${globalTaxValue.toFixed(2)}</span></div>`;
    document.getElementById('totalPay').innerHTML = `<span>Total Payable:</span> <span>₹${globalTotalPayable.toFixed(2)}</span>`;
}