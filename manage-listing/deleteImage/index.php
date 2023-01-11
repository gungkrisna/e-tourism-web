<?php
include '../../src/conn.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';
include '../../src/BusinessPhoto.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} else {
    header('Location: ../../login');
}

$business_service = new BusinessService($conn);
$business = $business_service->getBusinessByUserId($user['id_pengguna']);

$idFotoBisnis = $_POST['idFotoBisnis'];
$photos = new BusinessPhoto($conn);

$filename = $photos->getPhotoById($idFotoBisnis, $business->idBisnis)['filename'];

$photos->delete($idFotoBisnis, $business->idBisnis);

$file = '../../assets/images/listings/' . $filename;

if (file_exists($file)) {
    unlink($file);
}
