<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
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

$id_report = $_POST['id'];

$report = new Report($conn);

if($report->deleteReportByIdUlasan($id_report)){
    echo "Seluruh laporan berhasil dihapus";
} else {
    echo "Laporan gagal dihapus";
}
?>