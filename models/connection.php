<?php

function getConnection() {
    $host = 'localhost';
    $dbname = 'shop';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Kết nối CSDL thành công!";
    } catch(PDOException $e) {
        echo "Kết nối CSDL thất bại: " . $e->getMessage();
        exit;
    }

    return $conn;
}
