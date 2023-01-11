<?php
include '../../src/conn.php';
include '../../src/Business.php';
include '../../src/BusinessService.php';
include '../../src/BusinessPhoto.php';
include '../../src/Review.php';
include '../../src/Pengguna.php';
include '../../src/Article.php';
include '../../src/Place.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} else {
    header('Location: ../../login');
}

if ($user['level'] != 'admin') {
    header('Location: ../');
}

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$reviews = new Review($conn);
$place = new Place($conn);

$pengguna = new Pengguna($conn);
$monthlyRegisters = $pengguna->getTotalUsersPerMonth();

$statDataPengguna = [];
$statDataPenggunaBln = [];

foreach ($monthlyRegisters as $register) {
    $statDataPengguna[] = $register['TotalUsers'];
    $statDataPenggunaBln[] = sprintf("'%s %s'", $register['Month'], $register['Year']);
}


$artikel = new Article($conn);
$monthlyArticles = $artikel->getTotalArticlePerMonth();

$statArtikel = [];
$statArtikelBln = [];

foreach ($monthlyArticles as $ma) {
    $statArtikel[] = $ma['TotalArtikel'];
    $statArtikelBln[] = sprintf("'%s %s'", $ma['Month'], $ma['Year']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="E-Tourism Admin Dashboard">
    <meta name="keywords" content="e-tourism, admin, dashboard">
    <meta name="author" content="gk">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title>Admin Dashboard - E-Tourism</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="../assets/plugins/pace/pace.css" rel="stylesheet">

    <!-- Listing CSS -->
    <link rel="stylesheet" href="../../styles/plugins.css" />
    <link rel="stylesheet" href="../../styles/main.css" />

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
                <a href="index.html" class="logo-icon"><span class="logo-text hidden-on-mobile">Logo</span></a>
                <div class="sidebar-user-switcher user-activity-online">
                    <a href="#">
                        <img src="../assets/images/avatars/avatar.png">
                        <span class="activity-indicator"></span>
                        <span class="user-info-text">gk<br><span class="user-state-info">Administrator</span></span>
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
                    <li>
                        <a href=""><i class="material-icons-two-tone">article</i>Artikel<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="./new-article/">Buat Artikel</a>
                            </li>
                            <li>
                                <a href="./manage-articles/">Kelola Artikel</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-title">
                        Moderasi
                    </li>
                    <li>
                        <a href="./moderasi-bisnis/"><i class="material-icons-two-tone">store</i>Bisnis</a>
                    </li>
                    <li>
                        <a href="./moderasi-ulasan/"><i class="material-icons-two-tone">reviews</i>Ulasan</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="app-container">
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
                                            <a class="dropdown-item" href="../../">
                                                <h5 class="dropdown-item-title">
                                                    Halaman Utama
                                                    <span class="hidden-helper-text">Buka di tab baru<i class="material-icons">keyboard_arrow_right</i></span>
                                                </h5>
                                                <span class="dropdown-item-description">Akses halaman utama web E-Tourism.</span>
                                            </a>
                                            <a class="dropdown-item" href="../../blog/">
                                                <h5 class="dropdown-item-title">
                                                    Blog
                                                    <span class="hidden-helper-text">Buka di tab baru<i class="material-icons">keyboard_arrow_right</i></span>
                                                </h5>
                                                <span class="dropdown-item-description">Akses halaman blog E-Tourism.</span>
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
                                    <h1>Dashboard</h1>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-primary">
                                                <i class="material-icons-outlined">person</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Pengguna</span>
                                                <span class="widget-stats-amount"><?= $pengguna->count() ?></span>
                                                <span class="widget-stats-info">+<?= $statDataPengguna[count($statDataPengguna) - 1] ?> pengguna baru bulan ini</span>
                                            </div>
                                            <?
                                            if (count($statDataPengguna) > 1) {
                                                $registersChange = abs(round((($statDataPengguna[count($statDataPengguna) - 2] - $statDataPengguna[count($statDataPengguna) - 1]) / $statDataPengguna[count($statDataPengguna) - 1]) * 100, 1));
                                            ?>
                                                <div class="widget-stats-indicator widget-stats-indicator-<?= (abs($registersChange) == $registersChange) ? 'positive' : 'negative' ?> align-self-start">
                                                    <i class="material-icons"><?= (abs($registersChange) == $registersChange) ? 'keyboard_arrow_up' : 'keyboard_arrow_down' ?> </i> <?= $registersChange ?>%
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-warning">
                                                <i class="material-icons-outlined">store</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Listing Aktif</span>
                                                <span class="widget-stats-amount"><?= $business_service->countBusinessesByStatus()['disetujui'] ?? '0' ?></span>
                                                <span class="widget-stats-info"><?= $business_service->countBusinessesByStatus()['pending'] ?? 'Tidak ada' ?> listing perlu persetujuan</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card widget widget-stats">
                                    <div class="card-body">
                                        <div class="widget-stats-container d-flex">
                                            <div class="widget-stats-icon widget-stats-icon-purple">
                                                <i class="material-icons-outlined">article</i>
                                            </div>
                                            <div class="widget-stats-content flex-fill">
                                                <span class="widget-stats-title">Artikel</span>
                                                <span class="widget-stats-amount"><?= $artikel->count() ?></span>
                                                <span class="widget-stats-info">+<?= $statArtikel[count($statArtikel) - 1] ?? 'Tidak ada' ?> artikel baru bulan ini</span>
                                            </div>
                                            <?
                                            if (count($statArtikel) > 1) {
                                                $registersChange = abs(round((($statArtikel[count($statArtikel) - 2] - $statArtikel[count($statArtikel) - 1]) / $statArtikel[count($statArtikel) - 1]) * 100, 1));
                                            ?>
                                                <div class="widget-stats-indicator widget-stats-indicator-<?= (abs($registersChange) == $registersChange) ? 'positive' : 'negative' ?> align-self-start">
                                                    <i class="material-icons"><?= (abs($registersChange) == $registersChange) ? 'keyboard_arrow_up' : 'keyboard_arrow_down' ?> </i> <?= $registersChange ?>%
                                                </div>
                                            <? } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card widget widget-stats-large">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="widget-stats-large-chart-container">
                                                <div class="card-header">
                                                    <h5 class="card-title">Statistik</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div id="statistikPengguna"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                        $popular_businesses = $business_service->getMostPopularBusinesses();
                        if (count($popular_businesses) > 0) :
                        ?>
                            <div class="row">

                                <div class="section-description">
                                    <h1>Listing Terpopuler<span class="badge rounded-pill badge-danger">TOP 3</span></h1>
                                </div>
                                <?
                                $count = 0;
                                foreach ($popular_businesses as $business) :
                                    if ($business['status'] === 'disetujui') {
                                        $count++;
                                        $category = $business_service->getCategoryByBusinessId($business['id_bisnis']);

                                        switch ($business_service->getCategoryByBusinessId($business['id_bisnis'])['id_kategori']) {
                                            case 1:
                                                $categoryBadgeAccent = 'blue';
                                                break;
                                            case 2:
                                                $categoryBadgeAccent = 'red';
                                                break;
                                            case 3:
                                                $categoryBadgeAccent = 'black';
                                                break;
                                        }
                                ?>
                                        <div class="col-xl-4">
                                            <article class="rlr-product-card rlr-product-card--v3" itemscope itemtype="https://schema.org/Product">
                                                <figure class="rlr-product-card__image-wrapper">
                                                    <span class="rlr-badge rlr-badge-- rlr-badge--accent-<?= $categoryBadgeAccent ?> rlr-product-card__badge"> <?= $category['nama'] ?> </span>
                                                    <div class="rlr-product-detail-header__button-wrapper">
                                                        <button type="button" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button--light rlr-wishlist-button rlr-js-action-wishlist" aria-label="Save to Wishlist">
                                                            <i class="rlr-icon-font flaticon-heart-1"> </i>
                                                        </button>
                                                        <span class="rlr-product-detail-header__helptext rlr-js-helptext"></span>
                                                    </div>
                                                    <a href="../../listing/?id=<?= $business['id_bisnis'] ?>">
                                                        <div class="swiper rlr-js-product-multi-image-swiper">
                                                            <div class="swiper-wrapper">
                                                                <? foreach ($photos->read($business['id_bisnis']) as $photo) : ?>
                                                                    <div class="swiper-slide">
                                                                        <img itemprop="image" style="height: 200px; object-fit:cover" data-sizes="auto" data-src="../../assets/images/listings/<?= $photo['filename'] ?>" data-srcset="../../assets/images/listings/<?= $photo['filename'] ?>" class="lazyload" alt="product-image" />
                                                                    </div>
                                                                <? endforeach; ?>
                                                            </div>
                                                            <button type="button" class="btn rlr-button splide__arrow splide__arrow--prev" aria-label="prev button">
                                                                <i class="rlr-icon-font flaticon-left-chevron"> </i>
                                                            </button>
                                                            <button type="button" class="btn rlr-button splide__arrow splide__arrow--next" aria-label="next button">
                                                                <i class="rlr-icon-font flaticon-chevron"> </i>
                                                            </button>
                                                        </div>
                                                    </a>
                                                </figure>
                                                <div class="rlr-product-card__detail-wrapper rlr-js-detail-wrapper">
                                                    <!-- Product card header -->
                                                    <header class="rlr-product-card__header">
                                                        <div>
                                                            <a href="../../listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-title">
                                                                <h2 class="rlr-product-card__title" itemprop="name"><?= $business['nama'] ?></h2>
                                                            </a>
                                                            <div>
                                                                <a href="../../listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-cat">
                                                                    <span class="rlr-product-card__sub-title"><?= $business['alamat'] ?></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </header>
                                                    <!-- Product card body -->
                                                    <div class="rlr-product-card__details">
                                                        <div class="rlr-product-card__prices" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                                            <span class="rlr-product-card__from"><?= $place->getKecamatanNameById($business['id_kecamatan']) ?></span>
                                                            <div class="rlr-icon-text rlr-product-card__icon-text"><span class=""><?= $place->getKabupatenNameById($business['id_kabupaten']) ?></span></div>
                                                        </div>
                                                        <div class="rlr-product-card__ratings" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                                            <div class="rlr-review-stars" itemprop="ratingValue" itemscope itemtype="https://schema.org/Product">
                                                                <?
                                                                $stars = round($reviews->getAverageRatingById($business['id_bisnis']));
                                                                for ($i = 0; $i < $stars; $i++) {
                                                                    echo '<i class="rlr-icon-font flaticon-star-1"></i>';
                                                                }
                                                                if ($stars < 5) {
                                                                    for ($i = 0; $i < 5 - $stars; $i++) {
                                                                        echo '<i class="rlr-icon-font flaticon-star"></i>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <span class="rlr-product-card__rating-text" itemprop="reviewCount"><?= round($reviews->getAverageRatingById($business['id_bisnis']), 1) ?> (<?= $reviews->getTotalReviewsById($business['id_bisnis'])  ?>)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                    <?
                                    }
                                    if ($count == 3) break;
                                endforeach; ?>
                                        </div>
                            </div>
                    </div>
                <?
                        endif ?>
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
    <script src="../../vendors/navx/js/navigation.min.js" defer></script>
    <script src="../../js/old/main.js" defer></script>
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
                    name: "Pengguna",
                    // Use the JavaScript variable to populate the data field
                    data: [<?= implode(',', $statDataPengguna); ?>]
                }],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'Jumlah Pengguna dalam Bulan',
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
                    categories: [<?= implode(',', $statDataPenggunaBln); ?>],
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
                document.querySelector("#statistikPengguna"),
                stats
            );

            rankStats.render();
        });
    </script>
</body>

</html>