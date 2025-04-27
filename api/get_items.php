<?php 
require_once 'config.php';
$sql = "SELECT * FROM products";
$products = $conn->query($sql);
?>