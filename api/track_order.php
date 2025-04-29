<?php
include_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $stmt = $conn->prepare("SELECT reservations.tracking_id, reservations.status_id, reservations.pickup_time, reservations.pickup_date, users.name AS customer_name FROM reservations WHERE tracking_id = :tracking_id JOIN users ON reservations.user_id = users.id");
    $stmt->bindParam(':tracking_id', $_POST['trackingID']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
  } catch (Exception $e) {
    $data = ['error' => 'Error: ' . $e->getMessage()];
  } catch (Exception $e) {
    $output->error = "Error: " . $e->getMessage();
  }
}