<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/BusinessService.php';
include '../../../src/Business.php';
include '../../../src/BusinessPhoto.php';
include '../../../src/Review.php';
include '../../../src/ReviewPhoto.php';
include '../../../src/Report.php';
include '../../../src/Service.php';
include '../../../src/FAQ.php';
include '../../../src/Wishlist.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $pengguna = new Pengguna($conn);
    $user = $pengguna->read($_SESSION['user_id']);
} else {
    header('Location: ../../../login');
}

if ($user['level'] != 'admin') {
    header('Location: ../../');
}

$id_bisnis = $_POST['id'];

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$reviews = new Review($conn);
$reviewphotos = new ReviewPhoto($conn);
$report = new Report($conn);
$faqs = new FAQ($conn);
$services = new Service($conn);
$wishlist = new Wishlist($conn);

$faqs->deleteFAQByBusinessId($id_bisnis);
$photos->delete(null, $id_bisnis);

$reviewphotos->deletePhotoByIdUlasan($id_bisnis);
$report->deleteReportByIdUlasan($id_bisnis);
$wishlist->deleteWishlistByBusinessId($id_bisnis);

foreach ($reviews->read($id_bisnis, null, null, null, null, null) as $review) {
    $reviewphotos->deletePhotoByIdUlasan($review['id_ulasan']);
    $report->deleteReportByIdUlasan($review['id_ulasan']);
    $reviews->delete($review['id_ulasan']);
}

$services->deleteServicesByBusinessId($id_bisnis);

$business_service->removeRejectedBusinessListing($id_bisnis);
$business_service->deleteBusinessFromCategoryId($id_bisnis);

$id_pemilik = $business_service->getBusinessById($id_bisnis)->idPengguna;
if($pengguna->read($id_pemilik)['level'] == 'bisnis') {
    $pengguna->update($id_pemilik, null, null, null, null, null, null, null, null, 'pengguna');
}

if($business_service->deleteBusiness($id_bisnis)){
    echo "Bisnis berhasil dihapus";
} else {
   echo "Bisnis gagal dihapus";
}
?>