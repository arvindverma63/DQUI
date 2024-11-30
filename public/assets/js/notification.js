{/* <div class="item p-3">
<div class="row gx-2 justify-content-between align-items-center">
    <div class="col-auto">
        <div class="app-icon-holder">
            <i class="fa-light fa-utensils"></i>
        </div>
    </div>
    <div class="col">
        <div class="info">
            <div class="desc">You have a new invoice. Proin venenatis interdum
                est.</div>
            <div class="meta"> 1 day ago</div>
        </div>
    </div>
</div>
<a class="link-mask" href="notifications.html"></a>
</div> */}

function playSound(type = 'success') {
    const audio = new Audio(type === 'error' ? '/sounds/error.mp3' : 'assets/js/sweetalert2/preview.mp3');
    audio.play().catch((err) => {
        console.error('Audio play failed:', err);
    });
}

function showToast(message, type = 'success') {
    // Play the sound for success or error
    playSound(type);

    // Create a toast element dynamically
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.classList.add('toast', 'align-items-center', 'text-white', type === 'error' ? 'bg-danger' : 'bg-success');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    toastContainer.appendChild(toast);

    // Initialize and show the toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
}

let restaurantId;
let token;
let appUrl;
function getNotification() {
    // Fetch the authenticated user's restaurant ID
    fetch('/getAuth')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch authentication details');
            }
            return response.json();
        })
        .then(data => {
             restaurantId = data.restaurantId;
             token = data.token; // Ensure token is returned from /getAuth or fetched globally
             appUrl = data.app_url;

            // Fetch notifications for the restaurant
            return fetch(`${appUrl}/orders/notification/${restaurantId}`, {
                headers: {
                    'Content-Type': 'application/json', // JSON content type
                    'Authorization': `Bearer ${token}`,
                },
            });
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }
            return response.json();
        })
        .then(result => {
            console.log('Notifications:', result);


            // Prepare data to update notification status
            const requestData = {
                restaurantId: restaurantId,  // Ensure the restaurantId is available
            };

            const updatePromises = result.map(element => {

                if(result !=null){
                    showToast('New Order From TableNo'+element.tableNumber);
                }
                return fetch(`${appUrl}/orders/status/notification/${element.id}`, {
                    method: "PUT",
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify(requestData) // Send the request body as JSON
                })
                .then(response => {
                    if (response.ok) {
                        console.log(`Notification ${element.id} status updated successfully!`);
                    } else {
                        console.error(`Failed to update notification ${element.id} status`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });

            // Wait for all the PUT requests to finish
            return Promise.all(updatePromises);
        })
        .then(() => {
            console.log('All notification statuses updated!');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to fetch notifications', 'error');
        });
}

// Poll the server every 5 seconds
setInterval(getNotification, 500000);
