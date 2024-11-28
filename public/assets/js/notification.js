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
    audio.play();
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
            const restaurantId = data.restaurantId;
            const token = data.token; // Ensure token is returned from /getAuth or fetched globally
            const appUrl = data.app_url;

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
            showToast('Notifications fetched successfully!');
            formData = new FormData();
            formData.append('restaurantId',restaurantId);

            return fetch(`${appUrl}/orders/status/notification/${result.id}`, {
                method: "PUT",
                body: formData,
                headers: {
                    'Content-Type': 'application/json', // JSON content type
                    'Authorization': `Bearer ${result.token}`,
                },
            });
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to fetch notifications', 'error');
        });
}

// Poll the server every 5 seconds
setInterval(getNotification, 5000);
