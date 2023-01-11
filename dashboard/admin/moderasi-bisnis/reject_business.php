<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Business.php';
include '../../../src/BusinessService.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $pengguna = new Pengguna($conn);
    $user = $pengguna->read($_SESSION['user_id']);
} else {
    header('Location: ../../../login');
}

if ($user['level'] != 'admin') {
    header('Location: ../../');
}

$id_bisnis = $_POST['id'];
$alasan = $_POST['alasan'];

$business_service = new BusinessService($conn);
$selected_business = $business_service->getBusinessById($id_bisnis);
$updated_business = new Business($id_bisnis, $selected_business->idPengguna, $selected_business->nama, $selected_business->deskripsi, $selected_business->telepon, $selected_business->email, $selected_business->website, $selected_business->alamat, $selected_business->idDesa, $selected_business->lat, $selected_business->lng, 'ditolak');

$business_service->updateBusiness($updated_business);

if($business_service->rejectBusinessListing($id_bisnis, $alasan)){
    echo "Bisnis berhasil ditangguhkan";
} else {
   echo "Bisnis gagal ditangguhkan";
}
?>