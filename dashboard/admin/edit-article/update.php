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

  $id_artikel = $_POST['id_artikel'];
  $judul = $_POST['judul'];
  $subtitle = $_POST['subtitle'];
  $banner = $_POST['banner'];
  $article = $_POST['article'];
  $status = $_POST['status'] ?? 'draft';

$update_artikel = $artikel->update($id_artikel, $user['id_pengguna'], $judul, $subtitle, $banner, $article, $status);

$redirect_url = '../../../article/?id=' . $id_artikel;
echo $redirect_url;
exit;