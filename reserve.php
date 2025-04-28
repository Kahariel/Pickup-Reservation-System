<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Hardware</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <div class="logo">A&A Hardware</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="reserve.html" class="active">Reserve</a></li>
                <li><a href="contacts.html">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        <!-- Reservation Section -->
        <div class="reservation-container">
            <section class="reservation-card">
                <h2>Reserve Hardware Items</h2>
                <form id="reserveForm" method="POST">
                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input name="full_name" type="text" id="name" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input name="phone" type="number" id="phone" placeholder="Enter your phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="pickup-date">Pickup Date:</label>
                        <input name="pickup-date" type="date" id="pickup-date" required>
                    </div>

                    <!-- New: Pickup Time -->
                    <div class="form-group">
                        <label for="pickup-time">Pickup Time:</label>
                        <input name="pickup-time" type="time" id="pickup-time" required>
                    </div>

                    <div class="carousel-container">
                        <div class="carousel-track">
                            <!-- Carousel Items -->
                            <?php
                            @include_once 'api/get_items.php';
                            foreach ($products as $product) {
                                echo "
                                        <div class='carousel-item' data-item-name='" . $product['name'] . "' data-item-id='" . $product['id'] . "' data-item-img='http://localhost/Pickup-Reservation-System/" . substr($product['img_path'], 2) . "'>
                                            <img src='http://localhost/Pickup-Reservation-System/" . substr($product['img_path'], 2) . "' style='height: 100px;' />
                                            <h4>" . ucfirst($product['name']) . "</h4>
                                        </div>
                                    ";
                            }
                            ?>
                        </div>

                        <!-- Carousel Buttons -->
                        <button type="button" class="carousel-btn left">&#10094;</button>
                        <button type="button" class="carousel-btn right">&#10095;</button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">Submit Reservation</button>
                </form>
            </section>
        </div>

        <!-- Receipt Section -->
        <div class="receipt-wrapper">
            <section class="receipt-card">
                <h3>Reservation Receipt</h3>
                <ul id="receiptList"></ul>
                <p id="trackNumber" style="display: none;">
                    <strong>Track Number:</strong> <span id="trackValue"></span>
                </p>
            </section>
        </div>
    </main>

    <footer>
        <p>Contact: #0909776538505 | Facebook: Thelma Amoguis</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>