<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Fix SQL query with proper JOIN placement
        $stmt = $conn->prepare("SELECT reservations.tracking_id, reservations.status_id, reservations.pickup_time, reservations.pickup_date, users.name AS customer_name, 
        products.name AS product_name
                                FROM reservations
                                JOIN users ON reservations.user_id = users.id JOIN products ON reservations.product_id = products.id
                                WHERE reservations.tracking_id = :tracking_id");
        
        $stmt->bindParam(':tracking_id', $_POST['trackingID']);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If no data is found, return an empty array or a message
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    } catch (Exception $e) {
        // Proper error handling and returning a JSON response
        $errorData = ['error' => 'Error: ' . $e->getMessage()];
        echo json_encode($errorData);
    }
} else {
    // If the request method is not POST
    echo json_encode(['error' => 'Invalid request method']);
}
?>
