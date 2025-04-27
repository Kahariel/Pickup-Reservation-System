<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $output = new stdClass();
    $output->itemName = $_POST['itemName'];
    $output->itemQuantity = $_POST['itemQuantity'];
    $output->itemPrice = $_POST['itemPrice'];
    $output->success = false;

    $baseDir = '../assets/item_images/';

    if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['itemImage']['tmp_name'];
        $originalName = basename($_FILES['itemImage']['name']);

        // You can rename the file to avoid conflicts
        $newFileName = uniqid('item_', true) . '_' . $originalName;
        $destination = $baseDir . $newFileName;

        // Move file to destination
        if (move_uploaded_file($tmpName, $destination)) {
            $output->itemImagePath = $destination; // Relative path or you can adjust to be URL if needed
            $output->message = "File uploaded successfully.";
            $output->success = true;
        } else {
            $output->error = "Failed to move uploaded file.";
        }
    } else {
        $output->error = "No file uploaded or upload error.";
    }

    $stmt = $conn->prepare("INSERT INTO products (name, quantity, price, img_path) VALUES (:name, :quantity, :price, :img_path)");
    $stmt->bindParam(':name', $output->itemName);
    $stmt->bindParam(':quantity', $output->itemQuantity);
    $stmt->bindParam(':price', $output->itemPrice);
    $stmt->bindParam(':img_path', $output->itemImagePath);
    $stmt->execute();

    echo json_encode($output);
}
