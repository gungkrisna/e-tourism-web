<?php
include '../src/conn.php';
include '../src/Pengguna.php';

session_start();

$pengguna = new Pengguna($conn);

if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
}

$avatar = isset($_POST['avatar']) && $_POST['avatar'] !== '' ? $_POST['avatar'] : null;
$nama = isset($_POST['nama']) && $_POST['nama'] !== '' ? $_POST['nama'] : null;
$username = isset($_POST['username']) && $_POST['username'] !== '' ? $_POST['username'] : null;
$email = isset($_POST['email']) && $_POST['email'] !== '' ? $_POST['email'] : null;
$tanggal_lahir = isset($_POST['tanggal_lahir']) && $_POST['tanggal_lahir'] !== '' ? $_POST['tanggal_lahir'] : null;
$alamat = isset($_POST['alamat']) && $_POST['alamat'] !== '' ? $_POST['alamat'] : null;
$id_desa = isset($_POST['id_desa']) && $_POST['id_desa'] !== '' ? $_POST['id_desa'] : null;

if($pengguna->update($user['id_pengguna'], $nama, $username, $email, null, $avatar, $tanggal_lahir, $alamat, $id_desa, null)){
  echo "Update profil berhasil";
} else {
  echo "Update profil gagal";
}
