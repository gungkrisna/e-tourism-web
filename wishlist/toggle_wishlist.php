<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Wishlist.php';

$pengguna = new Pengguna($conn);
$wishlist = new Wishlist($conn);

$id_bisnis = $_POST['id_bisnis'];
$id_pengguna = $_POST['id_pengguna'];

$wishlist->isWishlist($id_pengguna, $id_bisnis) ? $wishlist->delete($id_pengguna, $id_bisnis) : $wishlist->add($id_pengguna, $id_bisnis);