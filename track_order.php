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
            "AA-966558": {
                name: "Edelyn",
                status: "Processing",
                dateReserve: "April 10,2025"
            },
            "ORD-789012": {
                name: "Ace Justine",
                status: "Shipped",
                dateReserve: "April 11,2025"
            },
            "ORD-345678": {
                name: "Mohamar",
                status: "Delivered",
                dateReserve: "April 12,2025"
            }
        };

        // Update orders with data from PHP
        data.forEach(element => {
            console.log(element); // For debugging: check the structure of the data
            orders[element.tracking_id] = {
                name: element.customer_name,
                status: element.status_id,
                dateReserve: element.pickup_date,
                timePickup: element.pickup_time // Assuming you want to show pickup time as well
            };
        });

        // NOW you can safely check and display
        if (orders[trackingID]) {
            const order = orders[trackingID];
            orderStatusDiv.innerHTML = `
                <div class="order-details">
                    <p><strong>Name:</strong> ${order.name}</p>
                    <p><strong>Status:</strong> ${order.status}</p>
                    <p><strong>Date Reserved:</strong> ${order.dateReserve}</p>
                    <p><strong>Date Reserved:</strong> ${order.timePickup}</p>
                </div>
            `;
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