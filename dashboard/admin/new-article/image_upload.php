<?php
$target_dir = "../../../assets/images/article";
$file_name = uniqid() . "-" . basename($_FILES["upload"]["name"]);
$target_file = $target_dir . '/' . $file_name;

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["upload"]["tmp_name"]);
if($check !== false) {
    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" ) {
        if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
            $response = array(
                'uploaded' => true,
                'url' => $target_file,
                'filename' => $file_name
            );
        } else {
            $response = array(
                'uploaded' => false,
                'error' => array(
                    'message' => 'Error uploading file'
                )
            );
        }
    } else {
        $response = array(
            'uploaded' => false,
            'error' => array(
                'message' => 'Invalid file type'
            )
        );
    }
} else {
    $response = array(
        'uploaded' => false,
        'error' => array(
            'message' => 'File is not an image'
        )
    );
}

echo json_encode($response);
