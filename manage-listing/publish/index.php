<?php
include '../../src/conn.php';
include '../../src/pengguna.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';
include '../../src/BusinessPhoto.php';
include '../../src/FAQ.php';
include '../../src/Service.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} else {
    header('Location: ../../login');
}

$photos = new BusinessPhoto($conn);
$services = new Service($conn);
$faqs = new FAQ($conn);
$pengguna = new Pengguna($conn);

// Business
$business_service = new BusinessService($conn);
$current_business = $business_service->getBusinessByUserId($user['id_pengguna']) ?? null;

$business = new Business($current_business->idBisnis ?? null, $user['id_pengguna'], $_POST['nama'], $_POST['deskripsi'], $_POST['telepon'], $_POST['email'], $_POST['website'], $_POST['alamat'], $_POST['desa'], $_POST['lat'], $_POST['lng'], 'pending');

$id_business = $current_business->idBisnis ?? $business_service->createBusiness($business);

if (isset($current_business)) {
    $business_service->updateBusiness($business);

    foreach($services->readAvailable($id_business) as $service) {
        $services->delete($service['id_layanan_bisnis']);
    }
    
    foreach($services->readUnavailable($id_business) as $service) {
        $services->delete($service['id_layanan_bisnis']);
    }

    foreach($faqs->read($id_business) as $faq) {
        $faqs->delete($faq['id_faq_bisnis']);
    }
}

$current_business ? $business_service->updateCategoryByBusinessId($_POST['kategori'], $id_business) : $business_service->setCategoryByBusinessId($_POST['kategori'], $id_business);

//Photos
$foto = $_FILES['file'];
$target_dir = "../../assets/images/listings/";

if(count($foto) > 0) {
    for ($i = 0; $i < count($foto['name']); $i++) {
        
            $file_name = uniqid() . "-" . basename($foto["name"][$i]);
            $target_file = $target_dir . $file_name;
    
            if (file_exists($target_file)) {
                $file_name = uniqid() . "-" . basename($foto["name"][$i]);
                $target_file = $target_dir . $file_name;
            }
    
            if (move_uploaded_file($foto["tmp_name"][$i], $target_file)) {
                $photos->create($id_business, $file_name);
            }
    }
}

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
foreach ($_POST['pertanyaan'] as $key => $pertanyaan) {
    $faq = new FAQ($conn);
    $faq->id_bisnis = $id_business;
    $faq->pertanyaan = $pertanyaan;
    $faq->jawaban = $_POST['jawaban'][$key];
    $faqs->create($faq);
}

if($user['level'] !== 'bisnis') {
    $pengguna->update($user['id_pengguna'], null, null, null, null, null, null, null, null, 'bisnis');
}

header('Location: ../../listing/?id=' . $id_business);