<?php

$dsn = 'mysql:host=localhost;dbname=db_etourism';
$username = 'root';
$password = '';

try{
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
