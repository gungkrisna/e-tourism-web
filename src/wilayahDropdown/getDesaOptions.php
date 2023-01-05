<?php

include '../conn.php';
include '../Place.php';

$place = new Place($conn);

// Get the ID of the selected Kecamatan
$idKecamatan = isset($_GET['idKecamatan']) ? (int) $_GET['idKecamatan'] : 0;

// Get the list of Desa for the selected Kecamatan
$desaList = $place->getDesaByKecamatan($idKecamatan);

// Build the options for the Kabupaten dropdown
$options = '';
foreach ($desaList as $desa) {
  $options .= '<option value="' . $desa['id_desa'] . '">' . $desa['nama'] . '</option>';
}

// Return the options as the response to the AJAX request
echo $options;
