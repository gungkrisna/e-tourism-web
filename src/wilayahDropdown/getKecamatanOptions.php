<?php

include '../conn.php';
include '../Place.php';

$place = new Place($conn);

// Get the ID of the selected Kabupaten
$idKabupaten = isset($_GET['idKabupaten']) ? (int) $_GET['idKabupaten'] : 0;

// Get the list of Kecamatan for the selected Kabupaten
$kecamatanList = $place->getKecamatanByKabupaten($idKabupaten);

// Build the options for the Kecamatan dropdown
$options = '';
foreach ($kecamatanList as $kecamatan) {
  $options .= '<option value="' . $kecamatan['id_kecamatan'] . '">' . $kecamatan['nama'] . '</option>';
}

// Return the options as the response to the AJAX request
echo $options;
