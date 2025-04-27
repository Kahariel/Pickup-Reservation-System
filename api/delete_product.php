<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $output = new stdClass();
  $output->success = false;

  try {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $_POST['productId']);
    $stmt->execute();
    $output->success = true;
  } catch (Exception $e) {
    $output->error = "Error: " . $e->getMessage();
  }

  echo json_encode($output);
}
