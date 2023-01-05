<?php
include 'src/conn.php';
include 'src/Business.php';
include 'src/BusinessService.php';
include 'src/BusinessPhoto.php';
include 'src/Review.php';
include 'src/Place.php';

session_start();

if (isset($_SESSION['user_id'])) {
  $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
  $stmt->execute([$_SESSION['user_id']]);
  $user = $stmt->fetch();
}

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$reviews = new Review($conn);
$place = new Place($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discover Your Next Trip - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="./assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="./styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="./styles/main.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="./styles/custom.css" />
  <script src="vendors/jquery.min.js"></script>
</head>

<body class="rlr-body">
  <!-- Header -->
  <header>
    <nav id="navigation" class="navigation rlr-navigation default-nav fixed-top">
      <!-- Logo -->
      <div class="navigation-header">
        <div class="navigation-brand-text">
          <div class="rlr-logo rlr-logo__navbar-brand rlr-logo--default">
            <a href="./index.html">
              <img src="./assets/svg/logoipsum-287.svg" alt="#" class="" />
            </a>
          </div>
        </div>
        <div class="navigation-button-toggler">
          <span class="rlr-sVGIcon"> <i class="rlr-icon-font rlr-icon-font--megamenu flaticon-menu pe-3"> </i> </span>
        </div>
      </div>
      <div class="navigation-body rlr-navigation__body container">
        <div class="navigation-body-header rlr-navigation__body-header">
          <div class="navigation-brand-text">
            <div class="rlr-logo rlr-logo__navbar-brand rlr-logo--default">
              <a href="./index.html">
                <img src="./assets/svg/logoipsum-287.svg" alt="#" class="" />
              </a>
            </div>
          </div>
          <span class="rlr-sVGIcon navigation-body-close-button"> <i class="rlr-icon-font rlr-icon-font--megamenu flaticon-close"> </i> </span>
        </div>

        <!-- Main menu -->
        <ul class="navigation-menu rlr-navigation__menu rlr-navigation__menu--main-links">
          <li class="navigation-item is-active">
            <a class="navigation-link" href="./">Home</a>
          </li>
          <!-- Mega menu -->
          <li class="navigation-item has-submenu">
            <a class="navigation-link" href="#">Destinasi</a>
            <ul class="navigation-megamenu navigation-submenu navigation-megamenu-half">
              <li class="navigation-megamenu-container">
                <ul class="navigation-tabs">
                  <li class="rlr-navigation__tabbed-list">
                    <ul class="navigation-tabs-nav">
                      <li class="navigation-tabs-nav-item is-active"><a href="#">Badung</a></li>
                      <li class="navigation-tabs-nav-item"><a href="#">Gianyar</a></li>
                      <li class="navigation-tabs-nav-item"><a href="#">Klungkung</a></li>
                      <li class="navigation-tabs-nav-item"><a href="#">Buleleng</a></li>
                      <li class="navigation-tabs-nav-item"><a href="#">Denpasar</a></li>
                      <li class="navigation-tabs-nav-item"><a href="#">Tabanan</a></li>
                      <li class="navigation-tabs-nav-item"><a href="./search/">View All</a></li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane is-active">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Ubud</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li class="navigation-tabs-pane">
                    <ul class="navigation-row">
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                      <li class="navigation-col">
                        <ul class="navigation-list">
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Kuta</a></span>
                            </div>
                          </li>
                          <li class="rlr-icon-text">
                            <div class="rlr-icon-text__text-wrapper">
                              <span class="rlr-icon-text__title"><a href="./search">
                                  Abiansemal</a></span>
                            </div>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="navigation-item">
            <a class="navigation-link" href="./home-page.html">Kategori</a>
            <ul class="navigation-dropdown">
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="./search">Akomodasi</a>
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="./search">Makanan & Minuman</a>
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="./search">Objek Wisata</a>
              </li>
            </ul>
          </li>
          <li class="navigation-item">
            <a class="navigation-link" href="./blog"> Blog </a>
          </li>
        </ul>
        <!-- User actions menu -->
        <ul class="navigation-menu rlr-navigation__menu align-to-right">
          <!-- Add your listing -->
          <li class="d-lg-none d-xxl-block navigation-item">
            <a class="navigation-link rlr-navigation__link--so" target="_blank" href="./new-listing/">Daftarkan
              Bisnis</a>
          </li>
          <!-- User account dropdown -->
          <li class="navigation-item">
            <a class="navigation-link" href="#"> <?= isset($_SESSION['user_id']) ? $user['nama'] : 'Guest' ?> <img class="ui right spaced rlr-avatar rlr-avatar__media--rounded" style="height: 32px; width: 32px;" src="https://static.wikia.nocookie.net/inconsistently-heinous/images/e/e0/Saul_2009.jpg" alt="account avatar" /> </a>

            <ul class="navigation-dropdown">

              <?php if (isset($_SESSION['user_id'])) : ?>
                <li class="navigation-dropdown-item">
                  <a class="navigation-dropdown-link" href="./profile">Akun saya</a>
                </li>
                <li class="navigation-dropdown-item">
                  <a class="navigation-dropdown-link" href="./wishlist">Wishlist</a>
                </li>
                <li class="navigation-dropdown-item">
                  <hr class="dropdown-divider rlr-dropdown__divider" />
                </li>
                <li class="navigation-dropdown-item">
                  <a class="navigation-dropdown-link" href="./logout/">Keluar</a>
                </li>
              <? else : ?>
                <li class="navigation-dropdown-item">
                  <a class="navigation-dropdown-link" href="./login/">Login</a>
                </li>
              <? endif; ?>
            </ul>

          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Main Content -->
  <main id="rlr-main" class="rlr-main--fixed-top">
    <!-- Hero Banner -->
    <aside class="rlr-hero--half-mast">
      <div class="container">
        <div id="rlr_banner_slider" class="splide rlr-banner-splide rlr-banner-splide--v3">
          <div class="splide__track rlr-banner-splide__track">
            <ul class="splide__list">
              <!-- Banner slide 1 -->
              <li class="splide__slide rlr-banner-splide__slide">
                <div class="rlr-banner-splide__image-wrapper">
                  <img class="rlr-banner-splide__banner-img lazyload" data-sizes="auto" data-src="./assets/images/banner/banner-1.jpeg" src="./assets/images/banner/banner-1.jpeg" alt="#" />
                </div>
                <article class="rlr-banner-splide__content-wrapper">
                  <header class="rlr-banner-splide__header">
                    <h2 class="rlr-banner-splide__slogan">The next mountain to climb</h2>
                    <span class="rlr-banner-splide__sub-title">Batur Mountain</span>
                  </header>
                  <div class="rlr-banner-splide__content-desc">
                    <div class="rlr-banner-splide__temperature">
                      <span>10° C</span>
                      <div class="rlr-banner-splide__arrows">
                        <button class="rlr-banner-splide__arrow rlr-banner-splide__arrow--prev rlr-banner-js-arrow-prev" aria-label="prev button">
                          <span> <i class="rlr-icon-font flaticon-left"> </i> </span>
                        </button>
                        <button class="rlr-banner-splide__arrow rlr-banner-splide__arrow--next rlr-banner-js-arrow-next" aria-label="next button">
                          <span> <i class="rlr-icon-font flaticon-right"> </i> </span>
                        </button>
                      </div>
                    </div>
                    <div class="rlr-banner-splide__payment-option animate__animated animate__fadeInUp">
                      <span class="rlr-svg-font">
                        <svg height="64" width="64" fill="#000000" version="1.1" id="XMLID_65_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-10.32 -10.32 44.64 44.64" xml:space="preserve" stroke="#000000" stroke-width="0.00024000000000000003">
                          <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-10.32" y="-10.32" width="44.64" height="44.64" rx="22.32" fill="#ffffff" strokewidth="0"></rect>
                          </g>
                          <g id="SVGRepo_iconCarrier">
                            <g id="article">
                              <g>
                                <path d="M20.5,22H4c-0.2,0-0.3,0-0.5,0C1.6,22,0,20.4,0,18.5V6h5V2h19v16.5C24,20.4,22.4,22,20.5,22z M6.7,20h13.8 c0.8,0,1.5-0.7,1.5-1.5V4H7v14.5C7,19,6.9,19.5,6.7,20z M2,8v10.5C2,19.3,2.7,20,3.5,20S5,19.3,5,18.5V8H2z">
                                </path>
                              </g>
                              <g>
                                <rect x="15" y="6" width="5" height="6"></rect>
                              </g>
                              <g>
                                <rect x="9" y="6" width="4" height="2"></rect>
                              </g>
                              <g>
                                <rect x="9" y="10" width="4" height="2"></rect>
                              </g>
                              <g>
                                <rect x="9" y="14" width="11" height="2"></rect>
                              </g>
                            </g>
                          </g>
                        </svg> </span>
                      <div class="rlr-banner-splide__content-desc-right">
                        <span class="rlr-banner-splide__payment-desc">Experience the Thrill of Climbing Mount Batur: The...</span>
                        <a href="./blog" class="btn rlr-button rlr-banner-splide__book-now" href="/product-detail-page" tabindex="-1"> 3 min read </a>
                      </div>
                    </div>
                  </div>
                </article>
              </li>
              <!-- Banner slide 2 -->
              <li class="splide__slide rlr-banner-splide__slide">
                <div class="rlr-banner-splide__image-wrapper">
                  <img class="rlr-banner-splide__banner-img lazyload" data-sizes="auto" data-src="./assets/images/banner/banner-2.jpeg" src="./assets/images/banner/banner-2.jpeg" alt="#" />
                </div>
                <article class="rlr-banner-splide__content-wrapper">
                  <header class="rlr-banner-splide__header">
                    <h2 class="rlr-banner-splide__slogan">Escape to the Beach Paradise</h2>
                    <span class="rlr-banner-splide__sub-title">Nusa Penida</span>
                  </header>
                  <div class="rlr-banner-splide__content-desc">
                    <div class="rlr-banner-splide__temperature">
                      <span>27° C</span>
                      <div class="rlr-banner-splide__arrows">
                        <button class="rlr-banner-splide__arrow rlr-banner-splide__arrow--prev rlr-banner-js-arrow-prev" aria-label="prev button">
                          <span> <i class="rlr-icon-font flaticon-left"> </i> </span>
                        </button>
                        <button class="rlr-banner-splide__arrow rlr-banner-splide__arrow--next rlr-banner-js-arrow-next" aria-label="next button">
                          <span> <i class="rlr-icon-font flaticon-right"> </i> </span>
                        </button>
                      </div>
                    </div>
                    <div class="rlr-banner-splide__payment-option animate__animated animate__fadeInUp">
                      <span class="rlr-svg-font">
                        <svg height="64" width="64" fill="#000000" version="1.1" id="XMLID_65_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-10.32 -10.32 44.64 44.64" xml:space="preserve" stroke="#000000" stroke-width="0.00024000000000000003">
                          <g id="SVGRepo_bgCarrier" stroke-width="0">
                            <rect x="-10.32" y="-10.32" width="44.64" height="44.64" rx="22.32" fill="#ffffff" strokewidth="0"></rect>
                          </g>
                          <g id="SVGRepo_iconCarrier">
                            <g id="article">
                              <g>
                                <path d="M20.5,22H4c-0.2,0-0.3,0-0.5,0C1.6,22,0,20.4,0,18.5V6h5V2h19v16.5C24,20.4,22.4,22,20.5,22z M6.7,20h13.8 c0.8,0,1.5-0.7,1.5-1.5V4H7v14.5C7,19,6.9,19.5,6.7,20z M2,8v10.5C2,19.3,2.7,20,3.5,20S5,19.3,5,18.5V8H2z">
                                </path>
                              </g>
                              <g>
                                <rect x="15" y="6" width="5" height="6"></rect>
                              </g>
                              <g>
                                <rect x="9" y="6" width="4" height="2"></rect>
                              </g>
                              <g>
                                <rect x="9" y="10" width="4" height="2"></rect>
                              </g>
                              <g>
                                <rect x="9" y="14" width="11" height="2"></rect>
                              </g>
                            </g>
                          </g>
                        </svg> </span>
                      <div class="rlr-banner-splide__content-desc-right">
                        <span class="rlr-banner-splide__payment-desc">Find Your Haven at the Beach: The Perfect
                          Escape</span>
                        <a href="./blog" class="btn rlr-button rlr-banner-splide__book-now" href="/product-detail-page" tabindex="-1"> 4 mins read </a>
                      </div>
                    </div>
                  </div>
                </article>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </aside>
    <div class="container">
      <!-- Search banner -->
      <form action="search/" id="searchDestination" class="rlr-banner-search rlr-banner-search--hero-half-mast">
        <div class="rlr-banner-search__input-wrapper">
          <!-- Destination -->
          <div class="rlr-banner-input-group rlr-banner-input-group rlr-banner-input-group--home-search rlr-js-autocomplete-demo rlr-banner-search__banner-input rlr-js-search-layout-wrapper">
            <label class="rlr-banner-input-group__label" for="destinationInput">
              <mark>Lokasi</mark>
            </label>
            <div class="rlr-banner-input-group__input-wrapper">
              <input id="destinationInput" type="text" autocomplete="off" class="rlr-banner-input-group__input" placeholder="Pilih Destinasi" />
              <i class="rlr-icon-font flaticon-map-marker"> </i>
              <ul id="destinationResults" class="rlr-banner-input-group--location-dropdown rlr-autocomplete" style="display:none">
                <? foreach ($place->getAllPlaces() as $p) : ?>
                  <li class="rlr-autocomplete__item rlr-js-autocomplete__item" id="<?= $p['id'] ?>" data-id='<?= $p['type_of_place'] ?>'>
                    <div class="rlr-autocomplete__item-wrapper"><span class="rlr-svg-icon"><svg width="18" height="21" viewBox="0 0 18 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M15.363 2.60149C14.1044 1.35931 12.5008 0.513379 10.7551 0.170666C9.00943 -0.172046 7.19997 0.0038532 5.55555 0.676122C3.91113 1.34839 2.50562 2.48684 1.51674 3.9475C0.527864 5.40816 3.51041e-05 7.12544 0 8.88217C0 13.6816 4.59772 17.6733 7.06819 19.818C7.41139 20.1186 7.70789 20.3732 7.94448 20.5907C8.23132 20.8538 8.6084 21 8.99998 21C9.39157 21 9.76865 20.8538 10.0555 20.5907C10.2921 20.3732 10.5886 20.1156 10.9318 19.818C13.4022 17.6733 18 13.6816 18 8.88217C18.003 7.71519 17.7716 6.5592 17.3189 5.48112C16.8663 4.40304 16.2015 3.42428 15.363 2.60149ZM10.1073 18.891C9.75696 19.1916 9.45336 19.4582 9.20357 19.6927C9.14772 19.742 9.07542 19.7693 9.00049 19.7693C8.92556 19.7693 8.85326 19.742 8.79741 19.6927C8.54762 19.4622 8.24504 19.1996 7.89371 18.891C5.57149 16.8746 1.24894 13.1214 1.24894 8.87816C1.24894 6.84868 2.06578 4.90232 3.51976 3.46727C4.97375 2.03221 6.94577 1.226 9.00202 1.226C11.0583 1.226 13.0303 2.03221 14.4843 3.46727C15.9383 4.90232 16.7551 6.84868 16.7551 8.87816C16.752 13.1244 12.4295 16.8746 10.1073 18.891Z" fill="black"></path>
                          <path d="M9 5C8.20888 5 7.43552 5.2346 6.77772 5.67412C6.11992 6.11365 5.60723 6.73836 5.30448 7.46927C5.00173 8.20017 4.92252 9.00444 5.07686 9.78036C5.2312 10.5563 5.61216 11.269 6.17157 11.8284C6.73098 12.3878 7.44372 12.7688 8.21964 12.9231C8.99556 13.0775 9.79983 12.9983 10.5307 12.6955C11.2616 12.3928 11.8864 11.8801 12.3259 11.2223C12.7654 10.5645 13 9.79112 13 9C12.9989 7.93947 12.5771 6.92268 11.8272 6.17277C11.0773 5.42286 10.0605 5.00108 9 5ZM9 11.7424C8.4576 11.7424 7.92737 11.5816 7.47638 11.2803C7.02539 10.9789 6.67388 10.5506 6.46631 10.0495C6.25874 9.54837 6.20443 8.99696 6.31025 8.46497C6.41607 7.93299 6.67726 7.44433 7.0608 7.0608C7.44434 6.67726 7.93299 6.41607 8.46498 6.31025C8.99696 6.20443 9.54837 6.25874 10.0495 6.46631C10.5506 6.67388 10.9789 7.02538 11.2803 7.47638C11.5816 7.92737 11.7424 8.45759 11.7424 9C11.7416 9.72709 11.4524 10.4242 10.9383 10.9383C10.4242 11.4524 9.72709 11.7416 9 11.7424Z" fill="black"></path>
                        </svg> </span>
                      <div class="rlr-autocomplete__text-wrapper"><span class="rlr-autocomplete__text"><?= $p['nama'] ?></span><span class="rlr-autocomplete__sub-text"><?= $p['nama'] ?>, Indonesia</span></div>
                    </div>
                  </li>
                <? endforeach ?>
              </ul>
            </div>
          </div>
          <!-- Category -->
          <div class="rlr-banner-input-group rlr-js-autocomplete-activity-demo rlr-banner-search__banner-input rlr-js-search-layout-wrapper">
            <label class="rlr-banner-input-group__label" for="rlr-banner-input-group-activity">
              <mark>Kategori</mark>
            </label>
            <div class="rlr-banner-input-group__input-wrapper">
              <input id="categoryInput" name="kategori" type="text" autocomplete="off" class="rlr-banner-input-group__input activity_autocomplete" placeholder="Akomodasi, makanan & minuman, objek wisata" />
              <i class="rlr-icon-font flaticon-outline-down"> </i>
              <ul id="categoryResults" class="rlr-banner-input-group--activity-dropdown rlr-autocomplete" style="display:none">
                <? foreach ($business_service->getAllBusinessCategory() as $c) : ?>
                  <li class="rlr-autocomplete__item rlr-js-autocomplete__item" id="<?= $c['id_kategori'] ?>"><?= $c['nama'] ?></li>
                <? endforeach ?>
              </ul>

            </div>
          </div>
        </div>
        <button class="rlr-banner-search__submit-button" aria-label="banner submit">
          <i class="rlr-icon-font flaticon-search"> </i>
        </button>
      </form>
    </div>
    <!-- Product Carousel -->
    <section class="rlr-section rlr-section__mb">
      <div class="container">
        <!-- Swiper -->
        <div class="rlr-carousel__items">
          <div class="swiper rlr-js-product-card-swiper">

            <!-- <div class="swiper rlr-js-category-card-swiper"> -->
            <!-- Carousel header -->
            <div class="rlr-section-header">
              <!-- Section heading -->
              <div class="rlr-section__title">
                <h2 class="rlr-section__title--main">Populer</h2>
                <span class="rlr-section__title--sub">Tempat terpopuler di Bali yang dapat Anda kunjungi</span>
              </div>
              <div class="button-row">
                <button type="button" class="btn rlr-button button button--previous rlr-button--carousel" aria-label="Previous">
                  <i class="rlr-icon-font flaticon-left-chevron"> </i>
                </button>
                <div class="button-group button-group--cells">
                  <button class="button is-selected">1</button>
                  <button class="button">2</button>
                </div>
                <button type="button" class="btn rlr-button button button--next rlr-button--carousel" aria-label="Next">
                  <i class="rlr-icon-font flaticon-chevron"> </i>
                </button>
              </div>
            </div>
            <div class="swiper-wrapper">
              <?
              $count = 0;
              foreach ($business_service->getMostPopularBusinesses() as $business) :
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
                <div class="swiper-slide" data-aos="fade-up" data-aos-duration="700" data-aos-once="true">
                  <article class="rlr-product-card rlr-product-card--v3" itemscope itemtype="https://schema.org/Product">
                    <figure class="rlr-product-card__image-wrapper">
                      <span class="rlr-badge rlr-badge-- rlr-badge--accent-<?= $categoryBadgeAccent ?> rlr-product-card__badge"> <?= $category['nama'] ?> </span>
                      <div class="rlr-product-detail-header__button-wrapper">
                        <button type="button" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button--light rlr-wishlist-button rlr-js-action-wishlist" aria-label="Save to Wishlist">
                          <i class="rlr-icon-font flaticon-heart-1"> </i>
                        </button>
                        <span class="rlr-product-detail-header__helptext rlr-js-helptext"></span>
                      </div>
                      <a href="./listing/?id=<?= $business['id_bisnis'] ?>">
                        <div class="swiper rlr-js-product-multi-image-swiper">
                          <div class="swiper-wrapper">
                            <? foreach ($photos->read($business['id_bisnis']) as $photo) : ?>
                                <div class="swiper-slide">
                                    <img itemprop="image" data-sizes="auto" data-src="./assets/images/listings/<?= $photo['filename'] ?>" data-srcset="./assets/images/listings/<?= $photo['filename'] ?>" class="lazyload" alt="product-image" />
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
                          <a href="./listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-title">
                            <h2 class="rlr-product-card__title" itemprop="name"><?= $business['nama'] ?></h2>
                          </a>
                          <div>
                            <a href="./listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-cat">
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
                if ($count == 3) break;
              endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Destination Masonary Grid -->
    <section class="rlr-section rlr-section__mb">
      <div class="container">
        <!-- Section heading -->
        <div class="rlr-section__title">
          <h2 class="rlr-section__title--main">Destinasi Pilihan</h2>
          <span class="rlr-section__title--sub">Beristirahatlah dan segarkan diri di destinasi santai berikut ini</span>
        </div>
        <div class="rlr-masonary-grid__container">
          <div class="rlr-masonary-grid__one">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/01.jpg" data-srcset="./assets/images/dest/01@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 200+ </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Ubud</h2>
                <p class="rlr-destination-card__info--sub">366 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
          <div class="rlr-masonary-grid__two">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/02.jpg" data-srcset="./assets/images/dest/02@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 320 Tujuan Wisata </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Canggu</h2>
                <p class="rlr-destination-card__info--sub">577 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
          <div class="rlr-masonary-grid__three">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/03.jpg" data-srcset="./assets/images/dest/03@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 58 Tujuan Wisata </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Penida Island</h2>
                <p class="rlr-destination-card__info--sub">327 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
          <div class="rlr-masonary-grid__four">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/04.jpg" data-srcset="./assets/images/dest/04@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 406 Tujuan Wisata </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Kuta</h2>
                <p class="rlr-destination-card__info--sub">271 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
          <div class="rlr-masonary-grid__five">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/05.jpg" data-srcset="./assets/images/dest/05@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 37<br>Tujuan Wisata </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Jimbaran</h2>
                <p class="rlr-destination-card__info--sub">780 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
          <div class="rlr-masonary-grid__six">
            <!-- Destination card -->
            <a class="rlr-destination-card" href="./search">
              <img data-sizes="auto" data-src="./assets/images/dest/06.jpg" data-srcset="./assets/images/dest/06@2x.jpg 2x" class="rlr-destination-card__img lazyload" alt="..." />
              <span class="rlr-badge rlr-badge--left rlr-badge-- rlr-badge--abs rlr-badge--abs-dest"> 74 Tujuan Wisata </span>
              <div class="rlr-destination-card__info rlr-destination-card__info--left rlr-destination-card__info--bottom">
                <h2 class="rlr-destination-card__info--main">Nusa Lembongan</h2>
                <p class="rlr-destination-card__info--sub">872 KM dari Tukad Badung</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>
    <!-- Logo Carousel -->
    <section id="features" class="rlr-section rlr-section__mb landding__plugin">
      <div class="container">
        <div class="rlr-logos-slider">
          <div class="rlr-logos-slider__items">
            <div class="slide-track">
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/trivago.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/tripadvisor.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/expedia.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/tailormade.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/all-inclusive.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/radisson.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/disneyland.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/national-geographic.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/lonelyplanet.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/tailormade.png" width="187px" height="64px" alt="partner logo" />
              </div>
              <div class="slide">
                <img data-sizes="auto" class="lazyload" data-src="./assets/images/logos/trivago.png" width="187px" height="64px" alt="partner logo" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- Footer -->
  <footer class="rlr-footer rlr-section rlr-section__mt">
    <div class="container">
      <!-- Footer menu -->
      <div class="rlr-footer__row justify-content-between">
        <nav class="rlr-footer__menu__col">
          <div class="navigation-brand-text">
            <div class="rlr-logo rlr-logo__navbar-brand rlr-logo--default mb-3">
              <a href="./index.html">
                <img src="./assets/svg/logoipsum-287.svg" alt="#" class="" style="width: 200px;" />
              </a>
            </div>
          </div>
          <!-- Footer menu col -->
          <h4>E-Tourism</h4>
          <p>World Tourism Organization<br>Calle Poeta Joan Maragall 42<br>28020 Madrid, Spain<br>info@unwto.org</p>
        </nav>

        <div class="d-flex rlr-footer__menu">
          <nav class="rlr-footer__menu__col">
            <!-- Footer menu col -->
            <h4>Destinasi</h4>
            <ul>
              <li><a href="./search/">Ubud</a></li>
              <li><a href="./search/">Canggu</a></li>
              <li><a href="./search/">Jimbaran</a></li>
              <li><a href="./search/">Nusa Penida</a></li>
              <li><a href="./search/">Cari Destinasi</a></li>
            </ul>
          </nav>
          <nav class="rlr-footer__menu__col">
            <!-- Footer menu col -->
            <h4>Kategori</h4>
            <ul>
              <li><a href="./search">Akomodasi</a></li>
              <li><a href="./search/">Makanan & Minuman</a></li>
              <li><a href="./search/">Objek Wisata</a></li>
            </ul>
          </nav>
          <nav class="rlr-footer__menu__col">
            <!-- Footer menu col -->
            <h4>Lainnya</h4>
            <ul>
              <li><a href="./blog/">Blog</a></li>
              <li><a href="./new-listing/">Daftarkan bisnis</a></li>
              <li><a href="./contact/">Hubungi kami</a></li>
            </ul>
          </nav>
        </div>

      </div>
      <!-- Footer bottom -->
      <div class="rlr-footer__legal">
        <div class="rlr-footer__legal__row rlr-footer__legal__row--top">
        </div>
        <!-- Footer copyright -->
        <div class="rlr-footer__legal__row rlr-footer__legal__row--bottom">
          <div class="rlr-footer__legal__row__col">
            <span>2023 © E-Tourism</span>
          </div>
          <!-- Footer social links -->
          <div class="rlr-footer__legal__row__col">
            <a href="https://twitter.com">Twitter</a>
            <span class="separate">/</span>
            <a href="https://facebook.com">Facebook</a>
            <span class="separate">/</span>
            <a href="https://instagram.com">Instagram</a>
          </div>
        </div>
      </div>
  </footer>
  <!-- Scripts -->
  <script src="./vendors/navx/js/navigation.min.js" defer></script>
  <script src="./js/old/main.js" defer></script>
  <script src="./js/landing.js" defer></script>
</body>

</html>