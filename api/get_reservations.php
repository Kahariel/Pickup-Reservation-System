<?php
require_once 'config.php';
$sql = "SELECT reservations.id, reservations.tracking_id, reservations.pickup_date, reservations.pickup_time, reservations.status_id, users.name AS customer_name FROM reservations 
        JOIN users ON reservations.user_id = users.id";
$reservations = $conn->query($sql);
?>