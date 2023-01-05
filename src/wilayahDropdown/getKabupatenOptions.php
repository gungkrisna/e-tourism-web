<?php

include '../conn.php';
include '../Place.php';

$place = new Place($conn);

// Get the ID of the selected Provinsi
$idProvinsi = isset($_GET['idProvinsi']) ? (int) $_GET['idProvinsi'] : 0;

// Get the list of Kabupaten for the selected Provinsi
$kabupatenList = $place->getKabupatenByProvinsi($idProvinsi);

// Build the options for the Kabupaten dropdown
$options = '';
foreach ($kabupatenList as $kabupaten) {
  $options .= '<option value="' . $kabupaten['id_kabupaten'] . '">' . $kabupaten['nama'] . '</option>';
}

// Return the options as the response to the AJAX request
echo $options;
