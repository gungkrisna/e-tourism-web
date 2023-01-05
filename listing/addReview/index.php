<?php
include '../../src/conn.php';
include '../../src/Review.php';
include '../../src/ReviewPhoto.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login");
    exit;
}

$stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Review
$newReview = new Review($conn);
$newReview->idBisnis = $_POST['id_bisnis'];
$newReview->idPengguna = $user['id_pengguna'];
$newReview->rating = $_POST['rating'];
$newReview->judul = $_POST['judul'];
$newReview->komentar = $_POST['komentar'];
$newReview->status = "publik";

$result = $newReview->create($newReview);

//Photos

if (isset($_FILES['file'])) {
    $foto = $_FILES['file'];
    $target_dir = "../../assets/images/reviews/";
    $photo = new ReviewPhoto($conn);
    
    for ($i = 0; $i < count($foto['name']); $i++) {
        
            $file_name = uniqid() . "-" . basename($foto["name"][$i]);
            $target_file = $target_dir . $file_name;
    
            if (file_exists($target_file)) {
                $file_name = uniqid() . "-" . basename($foto["name"][$i]);
                $target_file = $target_dir . $file_name;
            }
    
            if (move_uploaded_file($foto["tmp_name"][$i], $target_file)) {
                $photo->create($result, $file_name);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
    }
}
