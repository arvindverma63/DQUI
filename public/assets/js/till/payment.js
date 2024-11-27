// Event listener for the 'make-payment' button click
document.getElementById('make-payment').addEventListener('click', async function () {
    try {
        // Disable the button to prevent multiple submissions
        this.disabled = true;

        // Step 1: Fetch session data
        const sessionResponse = await fetch('/getData');
        if (!sessionResponse.ok) throw new Error('Failed to fetch session data');
        const sessionData = await sessionResponse.json();

        if (!sessionData.data || !sessionData.data.id || !sessionData.data.items) {
            throw new Error('Invalid session data structure: Missing user ID or items.');
        }

        // Step 2: Fetch transaction setup data
        const setupResponse = await fetch('/transaction');
        if (!setupResponse.ok) throw new Error('Failed to fetch transaction setup data');
        const setupData = await setupResponse.json();

        // Validate setup data
        if (!setupData.token || !setupData.restaurantId || !setupData.baseUrl) {
            throw new Error('Transaction setup data is incomplete: Missing token, restaurantId, or baseUrl.');
        }

        // Step 3: Log fetched data for debugging
        console.log(setupData);
        console.log('Global Total Payable:', globalTotalPayable);

        // Step 4: Extract necessary data
        const token = setupData.token;
        const restaurantId = setupData.restaurantId;
        const baseUrl = setupData.baseUrl;

        // Step 5: Call payment function with fetched data
        await payment(sessionData.data.id, sessionData.data.items, token, restaurantId, baseUrl);

    } catch (error) {
        console.error('Error during payment initiation:', error);
        alert(`An error occurred during payment initiation: ${error.message}`);
    } finally {
        // Re-enable the button after the request is complete
        document.getElementById('make-payment').disabled = false;
    }
});

// Payment function to make the transaction
async function payment(user_id, items, token, restaurantId, baseUrl) {
    try {
        // Step 1: Validate payment type input
        const paymentTypeElement = document.getElementById('type');
        if (!paymentTypeElement) {
            throw new Error('Payment type field is missing. Please select a payment type.');
        }
        const paymentType = paymentTypeElement.value;

        // Step 2: Validate that payment type is non-empty
        if (!paymentType) {
            throw new Error('Please select a payment type to proceed.');
        }

        // Step 3: Validate global variables (tax, discount, total price, total payable)
        if (typeof globalTaxValue === 'undefined' || typeof globalDiscountValue === 'undefined' ||
            typeof globalTotalPrice === 'undefined' || typeof globalTotalPayable === 'undefined') {
            throw new Error('Transaction data is incomplete. Please review your cart.');
        }
        
        // Step 3.1: Set `globalTotalPayable` to `globalTotalPrice` if no valid tax or discount is provided
        if (globalTaxValue === 0 && globalDiscountValue === 0) {
            globalTotalPayable = globalTotalPrice;
        }

        // Step 4: Prepare request payload
        const requestData = {
            user_id: user_id,
            items: items,
            tax: globalTaxValue || 0,
            discount: globalDiscountValue || 0,
            sub_total: globalTotalPrice || 0,
            total: globalTotalPayable || 0,
            type: paymentType,
            restaurantId: restaurantId
        };

        // Step 5: Send transaction request
        const transactionResponse = await fetch(`${baseUrl}/transactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // JSON content type
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(requestData), // Send JSON payload
        });

        // Step 6: Handle transaction response
        if (!transactionResponse.ok) {
            let errorMessage = 'Failed to submit transaction.';
            try {
                const errorData = await transactionResponse.json();
                errorMessage = errorData.message || errorMessage;
            } catch (jsonError) {
                console.warn('Failed to parse error response:', jsonError);
            }
            throw new Error(errorMessage);
        }

        const transactionData = await transactionResponse.json();
        console.log('Transaction successful:', transactionData);
        console.log('Request Data:', requestData);
        alert('Transaction completed successfully!');

        // Step 7: Clear session after successful transaction
        window.location.href = '/clearsession';

    } catch (error) {
        console.error('Error submitting transaction:', error);
        alert(`An error occurred during the transaction: ${error.message}`);
    }
}
