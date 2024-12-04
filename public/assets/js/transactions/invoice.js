window.getInvoice = async function (id) {
    try {
        // Fetch authentication data
        const authResponse = await fetch('/getAuth');
        if (!authResponse.ok) throw new Error("Failed to fetch auth data");
        const authData = await authResponse.json();
        const token = authData.token;
        const restaurantId = authData.restaurantId;
        const baseUrl = authData.app_url;

        // Fetch transaction data
        const transactionResponse = await fetch(baseUrl + '/transactionById/' + id, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        if (!transactionResponse.ok) throw new Error("Failed to fetch transaction data");

        const transactions = await transactionResponse.json();

        transactions.forEach(transactionData => {
            document.getElementById('invoice-body').innerHTML = `
                <div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; font-family: Arial, sans-serif; color: #555;">
                    <!-- Invoice Header -->
                    <div style="border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="max-width: 200px;">
                            <h3>DQ</h3>
                        </div>
                        <div style="text-align: right;">
                            <h4 style="margin: 0;">Invoice #: ${transactionData.id}</h4>
                            <p style="margin: 0;">Created: ${transactionData.created_at}</p>
                        </div>
                    </div>

                    <!-- Sender and Receiver Information -->
                    <div style="border: 2px solid #eee; padding: 20px; margin-bottom: 20px; display: flex; justify-content: space-between;">
                        <div style="width: 48%;">
                            <strong>${transactionData.restaurantId}</strong><br>
                            ${transactionData.restaurantAddress}
                        </div>
                        <div style="width: 48%; text-align: right;">
                            <strong>Acme Corp.</strong><br> <b>Customer</b>
                            ${transactionData.userName}  <br><b>Table Number:</b> ${transactionData.tableNumber}<br>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div style="border: 2px solid #eee; padding: 20px; margin-bottom: 20px; display: flex; justify-content: space-between;">
                        <div style="width: 48%;">
                            <strong>Payment Method</strong><br>
                            ${transactionData.payment_type}
                        </div>
                        <div style="width: 48%; text-align: right;">
                            <strong>${transactionData.payment_type} #</strong><br>
                            ${transactionData.total}
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div style="border: 2px solid #eee; padding: 20px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <div><strong>Item</strong></div>
                            <div><strong>Price</strong></div>
                            <div><strong>Quantity</strong></div>
                            <div><strong>Total</strong></div>
                        </div>
                        ${transactionData.items.map(item => `
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee;">
                                <div>${item.itemName}</div>
                                <div>${item.price}</div>
                                <div>${item.quantity}</div>
                                <div>₹${(item.price * item.quantity).toFixed(2)}</div>
                            </div>
                        `).join('')}
                    </div>

                    <!-- Total Amount -->
                    <div style="border: 2px solid #eee; padding: 20px; text-align: right;">
                        <strong>Discount:</strong>₹ ${transactionData.discount}<br>
                        <strong>Discount:</strong>₹ ${transactionData.tax}<br>
                        <strong>Total:</strong>₹ ${transactionData.total}
                    </div>

                    <!-- Print Button -->
                    <div style="text-align: center; margin-top: 30px;">
                        <button onclick="window.print()" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; font-size: 16px; cursor: pointer;">Print Invoice</button>
                    </div>
                </div>
            `;
        });

        console.log(transactions); // Process or display transaction data
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while fetching the invoice. Please try again.');
    }
};
