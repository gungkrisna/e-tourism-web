<?php
include '../../src/conn.php';
include '../../src/Review.php';
include '../../src/Pengguna.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login");
    exit;
}

$pengguna = new Pengguna($conn);

$user = $pengguna->read($_SESSION['user_id']);

$reviews = new Review($conn);
$business_service = new BusinessService($conn);

$id_ulasan = $_POST['id_ulasan'];
$id_bisnis = $_POST['id_bisnis'];
$judul = $_POST['judul'];
$komentar = $_POST['komentar'];

if($user['level'] === 'bisnis' && $reviews->getReviewById($id_ulasan)[0]['id_bisnis'] === $business_service->getBusinessByUserId($user['id_pengguna'])->idBisnis) {
    if($reviews->readBusinessReply($id_ulasan)){
        echo "Gagal! Sudah ada balasan ulasan";
    } else {
        if($reviews->createBusinessReply($id_ulasan, $judul, $komentar)){
            echo "Balasan ulasan berhasil dipublikasikan";
        } else {
            echo "Balasan ulasan gagal dipublikasikan";
        }
    }
} else {
    echo "Balasan ulasan gagal dipublikasikan";
}

header('Location: ' . $_SERVER['HTTP_REFERER'] . '?id=' . $id_ulasan);