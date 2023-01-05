<?php

include '../conn.php';
include '../Place.php';

$place = new Place($conn);

// Get the list of Provinsi
$provinsiList = $place->getProvinsi();

// Build the options for the Provinsi dropdown
$options = '';
foreach ($provinsiList as $provinsi) {
  $options .= '<option value="' . $provinsi['id_provinsi'] . '">' . $provinsi['nama'] . '</option>';
}

// Return the options as the response to the AJAX request
echo $options;

