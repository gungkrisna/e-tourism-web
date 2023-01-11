<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Article.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $pengguna = new Pengguna($conn);
    $user = $pengguna->read($_SESSION['user_id']);
} else {
    header('Location: ../../../login');
}

$artikel = new Article($conn);

  $judul = $_POST['judul'];
  $subtitle = $_POST['subtitle'];
  $banner = $_POST['banner'];
  $article = $_POST['article'];
  $status = $_POST['status'] ?? 'draft';

$id_artikel = $artikel->create($user['id_pengguna'], $judul, $subtitle, $banner, $article, $status);

$redirect_url = '../../../article/?id=' . $id_artikel;
echo $redirect_url;
exit;