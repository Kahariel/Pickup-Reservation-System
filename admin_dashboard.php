<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- Link to external CSS file -->
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="javascript:void(0)" onclick="showSection('reservations')">Reservations</a></li>
            <li><a href="javascript:void(0)" onclick="showSection('add-item')">Add Item</a></li>
        </ul>
        <button id="logoutBtn">Logout</button>
    </div>

    <!-- Main content area -->
    <div class="dashboard-container">
        <!-- Reservations Section -->
        <div id="reservations" class="section">
            <h2>Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Pickup Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        include_once 'api/get_reservations.php';
                        foreach ($reservations as $reservation) {
                            echo "
                                <tr>
                                    <td>" . $reservation['id'] . "</td>
                                    <td>" . $reservation['customer_name'] . "</td>                                              
                                    <td>" . $reservation['pickup_date'] . "</td>
                                    <td>" . $reservation['status_id'] . "</td>
                                    " . ($reservation['status_id'] == 'Pending' ? "
                                    <td>
                                    <button class='confirm-btn' onclick='confirmReservation(" . $reservation['id'] . ")'>Confirm</button>
                                    <button class='cancel-btn' onclick='cancelReservation(" . $reservation['id'] . ")'>Cancel</button>
                                    </td>" 
                                    : 
                                    "<td><button class='cancel-btn' onclick='cancelReservation(" . $reservation['id'] . ")'>Cancel</button></td>") . "
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Add Item Section -->
        <div id="add-item" class="section" style="display:none;">
            <h3>Add New Item</h3>
            <div class="add-item-container">
                <form method="post" id="addProduct">
                    <input type="text" id="itemName" placeholder="Enter item name" name="itemName" required>
                    <input type="number" id="itemQuantity" placeholder="Enter quantity" name="itemQuantity" required>
                    <input type="number" id="itemPrice" placeholder="Enter price" name="price" required>
                    <input type="file" id="itemImage" name="itemImage" accept="image/*" required>
                    <button type="button" onclick="addItem()">Add Item</button>
                </form>
            </div>

            <!-- Items List Table -->
            <table id="itemsTable">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include_once 'api/get_items.php';
                    foreach ($products as $product) {
                        echo "
                                <tr>
                                    <td>" . ucfirst($product['name']) . "</td>
                                    <td>" . $product['price'] . "</td>
                                    <td>" . $product['quantity'] . "</td>
                                    <td><img src='http://localhost/Pickup Reservation System/" . substr($product['img_path'], 2) . "' height='100'></td>
                                    <td>
                                        <button class='cancel-btn' onclick='deleteProduct(" . $product['id'] . ")'>Delete</button>
                                    </td>
                                </tr>
                            ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="admin.js"></script> <!-- Link to external JS file -->
</body>

</html>