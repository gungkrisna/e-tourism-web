<?php
include '../../src/conn.php';
include '../../src/Report.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login");
    exit;
}

$stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Review
$report = new Report($conn);

$report->create($user['id_pengguna'], $_POST['id_ulasan'], $_POST['report'], $_POST['description']);

header('Location: ' . $_SERVER['HTTP_REFERER']);