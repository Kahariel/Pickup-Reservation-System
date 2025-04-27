<?php
session_start();
date_default_timezone_set('Asia/Singapore');
try {
    $servername = "localhost";
    $dbname = "prs";
    $username = "root";
    $password = "";

    $conn = new PDO(
        "mysql:host=$servername; dbname=$dbname;",
        $username, 
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
