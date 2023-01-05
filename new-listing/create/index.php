<?php
include '../../src/conn.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';
include '../../src/BusinessPhoto.php';
include '../../src/FAQ.php';
include '../../src/Service.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login");
    exit;
}

$stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Business
$business = new Business(null, $user['id_pengguna'], $_POST['nama'], $_POST['deskripsi'], $_POST['telepon'], $_POST['email'], $_POST['website'], $_POST['alamat'], $_POST['desa'], $_POST['lat'], $_POST['lng'], 'pending');
$business_service = new BusinessService($conn);
$id_business = $business_service->createBusiness($business);
$business_service->setCategoryByBusinessId($_POST['kategori'], $id_business);


//Photos
$foto = $_FILES['file'];
$target_dir = "../../assets/images/listings/";
$photo = new BusinessPhoto($conn);

for ($i = 0; $i < count($foto['name']); $i++) {
    
        $file_name = uniqid() . "-" . basename($foto["name"][$i]);
        $target_file = $target_dir . $file_name;

        if (file_exists($target_file)) {
            $file_name = uniqid() . "-" . basename($foto["name"][$i]);
            $target_file = $target_dir . $file_name;
        }

        if (move_uploaded_file($foto["tmp_name"][$i], $target_file)) {
            $photo->create($id_business, $file_name);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
}

// Services
foreach ($_POST['layanan-disediakan'] as $service_name) {
    $service = new Service($conn);
    $service->id_bisnis = $id_business;
    $service->layanan = $service_name;
    $service->disediakan = 1;
    $service->create($service);
}

foreach ($_POST['layanan-tidak-disediakan'] as $service_name) {
    $service = new Service($conn);
    $service->id_bisnis = $id_business;
    $service->layanan = $service_name;
    $service->disediakan = 0;
    $service->create($service);
}

// FAQs
$faqs = new FAQ($conn);
foreach ($_POST['pertanyaan'] as $key => $pertanyaan) {
    $faq = new FAQ($conn);
    $faq->id_bisnis = $id_business;
    $faq->pertanyaan = $pertanyaan;
    $faq->jawaban = $_POST['jawaban'][$key];
    $faqs->create($faq);
}
