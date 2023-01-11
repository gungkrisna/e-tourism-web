<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Review.php';
include '../../../src/ReviewPhoto.php';
include '../../../src/Report.php';


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

$id_review = $_POST['id'];

$reviews = new Review($conn);
$reviewphotos = new ReviewPhoto($conn);
$report = new Report($conn);

$reviews->deleteBusinessReply($id_review);
$reviewphotos->deletePhotoByIdUlasan($id_review);
$report->deleteReportByIdUlasan($id_review);
if($reviews->delete($id_review)){
    echo "Ulasan berhasil dihapus";
} else {
    echo "Ulasan gagal dihapus";
}
?>