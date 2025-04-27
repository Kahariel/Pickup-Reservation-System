<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = new stdClass();
    $output->success = false;

    try {
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = :id");
        $stmt->bindParam(':id', $_POST['orderId']);
        $stmt->execute();
        $output->success = true;
    } catch (Exception $e) {
        $output->error = "Error: " . $e->getMessage();
    }

    echo json_encode($output);
}