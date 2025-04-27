<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = new stdClass();
    $output->success = false;


    try {
        $stmt = $conn->prepare("INSERT INTO users (name, phone) VALUES (:name, :phone)");
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':phone', $_POST['phone']);
        $stmt->execute();

        $id = $conn->lastInsertId();

        foreach ($_POST['items'] as $item) {

            $stmt = $conn->prepare("INSERT INTO reservations (user_id, product_id, pickup_date, pickup_time, status_id) VALUES (:user_id, :product_id, :pickup_date, :pickup_time, `Pending`)");
            $stmt->bindParam(':user_id', $id);
            $stmt->bindParam(':product_id', $item['id']);

            $stmt->execute();
        }

        
        $output->success = true;
    } catch (Exception $e) {
        $output->error = "Error: " . $e->getMessage();
    }

    echo json_encode($_POST['items'][]);
}