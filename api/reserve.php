<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = new stdClass();
    $output->success = false;

    try {
        if (empty($_POST['name']) || empty($_POST['phone'])) {
            throw new Exception('Name and phone are required.');
        }
        $stmt = $conn->prepare("INSERT INTO users (name, phone) VALUES (:name, :phone)");
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->execute();

        $id = $conn->lastInsertId();

        // PROCESS 'items' from $_POST safely
        $item_set = trim($_POST['items'], '[]');

        // Fix missing commas between objects
        $fixed = preg_replace('/}({")/', '},$1', $item_set);

        // Wrap it properly into an array
        $jsonString = "[$fixed]";

        // Decode the JSON into PHP array
        $itemsArray = json_decode($jsonString, true);

        // Check decoding
        if (!is_array($itemsArray)) {
            throw new Exception("Failed to decode items JSON.");
        }

        // For debugging: show the decoded array properly
        // echo '<pre>'; print_r($itemsArray); echo '</pre>';

        // Proceed with inserting reservations
        foreach ($itemsArray as $item) {
            $stmt = $conn->prepare("INSERT INTO reservations (user_id, product_id, pickup_date, pickup_time, status_id, tracking_id) VALUES (:user_id, :product_id, :pickup_date, :pickup_time, :status_id, :tracking_id)");
            $stmt->bindParam(':user_id', $id);
            $stmt->bindParam(':product_id', $item['id']);
            $stmt->bindParam(':pickup_date', $_POST['pickupDate']); // corrected field name
            $stmt->bindParam(':pickup_time', $_POST['pickupTime']);
            $stmt->bindValue(':status_id', 'Pending'); // assuming 1 = "Pending"
            $stmt->bindParam(':tracking_id', $_POST['trackingID']);
            $stmt->execute();
        }

        $output->success = true;
    } catch (Exception $e) {
        $output->error = "Error: " . $e->getMessage();
    }

    // Final output
    header('Content-Type: application/json');
    echo json_encode($output);
}
?>