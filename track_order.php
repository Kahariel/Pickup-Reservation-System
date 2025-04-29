<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="track-container">
        <h2>Track Your Order</h2>
        <form method="POST">
            <input type="text" name="trackingID" id="trackingInput" placeholder="Enter Tracking ID (e.g., AA-966558)">
            <button onclick="trackOrder()">Track</button>
        </form>
        <div id="orderStatus"></div>
    </div>

    <script>function trackOrder() {
    event.preventDefault(); // Prevent form submission

    const trackingID = document.getElementById("trackingInput").value.trim();
    const orderStatusDiv = document.getElementById("orderStatus");

    if (!trackingID) {
        orderStatusDiv.innerHTML = "<p class='error'>Please enter a Tracking ID!</p>";
        return;
    }

    const formData = new FormData();
    formData.append('trackingID', trackingID);

    fetch('api/track_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Your default orders
        const orders = {
        };

        // Check if data is an array (multiple rows) and append
        data.forEach(element => {
            if (!orders[element.tracking_id]) {
                orders[element.tracking_id] = [];
            }
            orders[element.tracking_id].push({
                name: element.customer_name,
                status: element.status_id,
                dateReserve: element.pickup_date,
                timePickup: element.pickup_time,
                productName: element.product_name
            });
        });

        // Display orders for the tracking ID
        if (orders[trackingID] && orders[trackingID].length > 0) {
            const orderDetailsHTML = orders[trackingID].map(order => `
                <div class="order-details">
                    <p><strong>Name:</strong> ${order.name}</p>
                    <p><strong>Status:</strong> ${order.status}</p>
                    <p><strong>Date Reserved:</strong> ${order.dateReserve}</p>
                    <p><strong>Pickup Time:</strong> ${order.timePickup}</p>
                    <p><strong>Pickup Time:</strong> ${order.productName}</p>
                </div>
            `).join('');

            orderStatusDiv.innerHTML = orderDetailsHTML;
        } else {
            orderStatusDiv.innerHTML = "<p class='error'>Tracking ID not found. Please check and try again.</p>";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        orderStatusDiv.innerHTML = "<p class='error'>An error occurred while tracking your order. Please try again later.</p>";
    });
}

    </script>

</body>

</html>