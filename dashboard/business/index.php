<?php
include '../../src/conn.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';
include '../../src/Review.php';
include '../../src/Wishlist.php';

$business_service = new BusinessService($conn);

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} else {
    header('Location: ../../login');
}

if ($user['level'] != 'bisnis') {
    header('Location: ../../');
}

$business = $business_service->getBusinessByUserId($user['id_pengguna']);

if (!$business) {
    header('Location: ../../manage-listing');
}

$reviews = new Review($conn);
$monthlyReviews = $reviews->getTotalReviewsPerMonth($business->idBisnis);

$data = [];
$categories = [];

foreach ($monthlyReviews as $review) {
    $data[] = $review['TotalReviews'];
    $categories[] = sprintf("'%s %s'", $review['Month'], $review['Year']);
}

$wishlists = new Wishlist($conn);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="E-Tourism Business Suite Dashboard">
    <meta name="keywords" content="e-tourism, business, dashboard">
    <meta name="author" content="gk">

    <!-- Title -->
    <title>Business Suite Dashboard - E-Tourism</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="../assets/plugins/pace/pace.css" rel="stylesheet">


    <!-- Theme Styles -->
    <link href="../assets/css/main.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon.ico" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon.ico" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="app align-content-stretch d-flex flex-wrap">
        <div class="app-sidebar">
            <div class="logo">
                <a href="index.html" class="logo-icon"><span class="logo-text">Logo</span></a>
                <div class="sidebar-user-switcher user-activity-online">
                    <a href="#">
                        <? if ($user && !is_null($user['avatar'])) : ?>
                            <img src="../../assets/images/avatar/<?= $user['avatar'] ?>" style="height: 36px; width: 36px;" alt="account avatar" />
                        <? endif; ?>
                        <span class="user-info-text"><?= $user['nama'] ?><br><span class="user-state-info">Akun Bisnis</span></span>
                    </a>
                </div>
            </div>
            <div class="app-menu">
                <ul class="accordion-menu">
                    <li class="sidebar-title">
                        Dashboard
                    </li>
                    <li class="active-page">
                        <a href="index.html" class="active"><i class="material-icons-two-tone">dashboard</i>Dashboard</a>
                    </li>
                    <li class="sidebar-title">
                        Kelola Bisnis
                    </li>
                    <li>
                        <a href="./../../manage-listing/?mode=edit"><i class="material-icons-two-tone">corporate_fare</i>Edit Listing</a>
                    </li>
                    <li>
                        <a href="./../../listing/?id=<?= $business->idBisnis ?>#showReviewModal"><i class="material-icons-two-tone">reviews</i>Lihat Ulasan<span class="badge rounded-pill badge-primary float-end"><?= $reviews->getTotalReviewsById($business->idBisnis) ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="app-container">
            <div class="search">
                <form>
                    <input class="form-control" type="text" placeholder="Type here..." aria-label="Search">
                </form>
                <a href="#" class="toggle-search"><i class="material-icons">close</i></a>
            </div>
            <div class="app-header">
                <nav class="navbar navbar-light navbar-expand-lg">
                    <div class="container-fluid">
                        <div class="navbar-nav" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link hide-sidebar-toggle-button" href="#"><i class="material-icons">first_page</i></a>
                                </li>
                                <li class="nav-item dropdown hidden-on-mobile">
                                    <a class="nav-link dropdown-toggle" href="#" id="exploreDropdownLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="material-icons-outlined">explore</i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-lg large-items-menu" aria-labelledby="exploreDropdownLink">
                                        <li>
                                            <h6 class="dropdown-header">Shortcut</h6>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="../../listing/?id=<?= $business->idBisnis ?>">
                                                <h5 class="dropdown-item-title">
                                                    Bisnis Saya
                                                    <span class="hidden-helper-text">Buka di tab baru<i class="material-icons">keyboard_arrow_right</i></span>
                                                </h5>
                                                <span class="dropdown-item-description">Akses halaman listing <?= $business->nama ?> milik Anda.</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                        </div>
                        <div class="d-flex">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="../../logout"><i class="material-icons">logout</i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="app-content">
                <div class="content-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="page-description">
                                    <h1><?= $business->nama ?></h1>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">star</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Rating</span>
                                                <span class="widget-stats-amount"><?= round($reviews->getAverageRatingById($business->idBisnis), 1) ?></span>
                                                <span class="widget-stats-info"><?= count($reviews->read($business->idBisnis, null, null, '1,2', null, 'Rating terendah')) ?> ulasan dengan rating < 3</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-success">
                                                <i class="material-icons-outlined">reviews</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Ulasan</span>
                                                <span class="widget-stats-amount"><?= $reviews->getTotalReviewsById($business->idBisnis) ?></span>
                                                <span class="widget-stats-info">+<?= $data[count($data) - 1] ?? 0 ?> ulasan bulan ini</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-danger">
                                                <i class="material-icons-outlined">favorite</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Wishlist</span>
                                                <span class="widget-stats-amount"><?= $wishlists->countByBusinessId($business->idBisnis) ?></span>
                                                <span class="widget-stats-info">+<?= count(array_filter($wishlists->getTotalWishlistsPerMonth($business->idBisnis), function ($row) {
                                                                                        return $row['Month'] == date('n');
                                                                                    })); ?> wishlist bulan ini</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card widget widget-stats-large">
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <div class="widget-stats-large-chart-container">
                                                <div class="card-header">
                                                    <h5 class="card-title">Statistik</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div id="statistikUlasan"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="widget-stats-large-info-container">
                                                <div class="card-header">
                                                    <h5 class="card-title">Ulasan Negatif</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p class="card-description">Tindak lanjuti ulasan negatif berikut untuk meningkatkan kepercayaan pelanggan terhadap bisnis Anda.</p>
                                                    <ul class="list-group list-group-flush ">
                                                        <? foreach ($reviews->read($business->idBisnis, 0, 5, '1,2,3', null, 'Rating terendah') as $review) : ?>
                                                            <li onclick="window.location.href = './../../listing/?id=<?= $business->idBisnis ?>#showReviewModal'" class="list-group-item" style="cursor: pointer"><?= $review['judul'] ?><span class="float-end text-black"><?= $review['rating'] ?><i class="material-icons align-middle ms-2">keyboard_arrow_right</i></span></li>
                                                        <? endforeach ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="../assets/plugins/pace/pace.min.js"></script>
    <script src="../assets/plugins/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/js/main.min.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script src="../assets/js/pages/dashboard.js"></script>
    <script src="../assets/js/chart.js"></script>
    <script>
        $(document).ready(function() {

            "use strict";
            // Retrieve the JSON object and assign it to a JavaScript variable


            // Define the chart options
            var stats = {
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    }
                },
                series: [{
                    name: "Ulasan",
                    // Use the JavaScript variable to populate the data field
                    data: [<?= implode(',', $data); ?>]
                }],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'Ulasan Pengguna dalam Bulan',
                    align: 'center'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                    borderColor: 'rgba(94, 96, 110, .5)',
                    strokeDashArray: 4
                },
                xaxis: {
                    // Use the JavaScript variable to populate the categories field
                    categories: [<?= implode(',', $categories); ?>],
                    labels: {
                        style: {
                            colors: 'rgba(94, 96, 110, .5)'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0)
                        }
                    }
                }
            }
            // Initialize the chart
            var rankStats = new ApexCharts(
                document.querySelector("#statistikUlasan"),
                stats
            );

            rankStats.render();
        });
    </script>
</body>

</html>