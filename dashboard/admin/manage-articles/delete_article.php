<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Article.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $pengguna = new Pengguna($conn);
    $user = $pengguna->read($_SESSION['user_id']);
} else {
    header('Location: ../../login');
}

if ($user['level'] != 'admin') {
    header('Location: ../');
}

$id_artikel = $_POST['id'];

$artikel = new Article($conn);

if($artikel->delete($id_artikel)){
    echo "Artikel berhasil dihapus";
} else {
    echo "Artikel gagal dihapus";
}
?>