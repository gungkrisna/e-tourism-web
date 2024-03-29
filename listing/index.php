<?php
include '../src/conn.php';
include '../src/Business.php';
include '../src/BusinessService.php';
include '../src/Pengguna.php';
include '../src/BusinessPhoto.php';
include '../src/Service.php';
include '../src/Review.php';
include '../src/ReviewPhoto.php';
include '../src/FAQ.php';
include '../src/Place.php';
include '../src/Wishlist.php';

session_start();

$pengguna = new Pengguna($conn);
$user = [];
if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
}

if (isset($_GET['id'])) {
  $business_service = new BusinessService($conn);
  $business = $business_service->getBusinessById($_GET['id']);
}

if (!isset($_GET['id']) || empty($business)) {
  header("Location: ../404.html");
}

$business = $business_service->getBusinessById($_GET['id']);

if (is_null($business)) {
  header("Location: ../404.html");
}

if (!isset($user['level']) && $business->status !== 'disetujui') {

  header("Location: ../404.html");
} else if ($business && $business->status !== 'disetujui' && $user['level'] == 'pengguna') {

  header("Location: ../404.html");
} else {
  if (isset($user['level']) && $business->status !== 'disetujui' && $business_service->getBusinessByUserId($user['id_pengguna'])->idBisnis === $business->idBisnis) {
    $isNotActive = 'true';
  }
}


$photos = new BusinessPhoto($conn);
$services = new Service($conn);
$reviews = new Review($conn);
$reviewphotos = new ReviewPhoto($conn);
$faqs = new FAQ($conn);
$place = new Place($conn);
$wishlist = new Wishlist($conn);

$neighbours = $place->getNearestBusinessesByLocation($place->getPlaceById($business->idDesa)['id_kabupaten'], 'kabupaten')
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $business->nama ?> - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="../styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/custom.css" />
  <link rel="stylesheet" href="../styles/upload.css">

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

</head>

<body class="rlr-body">
  <!-- Header -->
  <header>
    <nav id="navigation" class="navigation rlr-navigation default-nav fixed-top">
      <!-- Logo -->
      <div class="navigation-header">
        <div class="navigation-brand-text">
          <div class="rlr-logo rlr-logo__navbar-brand rlr-logo--default">
            <a href="../">
              <img src="../assets/svg/logoipsum-287.svg" alt="#" class="" />
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
              <a href="../">
                <img src="../assets/svg/logoipsum-287.svg" alt="#" class="" />
              </a>
            </div>
          </div>
          <span class="rlr-sVGIcon navigation-body-close-button"> <i class="rlr-icon-font rlr-icon-font--megamenu flaticon-close"> </i> </span>
        </div>

        <!-- Main menu -->
        <ul class="navigation-menu rlr-navigation__menu rlr-navigation__menu--main-links">
          <li class="navigation-item">
            <a class="navigation-link" href="../">Home</a>
          </li>
          <!-- Mega menu -->
          <li class="navigation-item">
            <a class="navigation-link" href="#">Destinasi</a>
            <ul class="navigation-dropdown">
              <?
              $count = 0;
              foreach ($place->getKabupatenByProvinsi('51') as $kabupaten) :
                $count++;
              ?>
                <li class="navigation-dropdown-item <?= $count == 1 ? 'active' : null ?>">
                  <a class="navigation-dropdown-link" href="../search/?kabupaten=<?= $kabupaten['id_kabupaten'] ?>"><?= $kabupaten['nama'] ?></a>
                </li>
              <?
                if ($count == 6) break;
              endforeach; ?>
              <? if ($place->getKabupatenByProvinsi('51') > 6) : ?>
                <li class="navigation-dropdown-item">
                  <a class="navigation-dropdown-link" href="../search/?provinsi='51'">Jelajahi <?= $place->getProvinsiNameById('51') ?></a>
                </li>
              <? endif; ?>
            </ul>
          </li>
          <li class="navigation-item">
            <a class="navigation-link" href="../search/?kategori=1,2,3">Kategori</a>
            <ul class="navigation-dropdown">
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../search/?kategori=1">Akomodasi</a>
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../search/?kategori=2">Makanan & Minuman</a>
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../search/?kategori=3">Objek Wisata</a>
              </li>
            </ul>
          </li>
          <li class="navigation-item">
            <a class="navigation-link" href="../blog"> Blog </a>
          </li>
        </ul>
        <ul class="navigation-menu rlr-navigation__menu align-to-right">
          <li class="d-lg-none d-xxl-block navigation-item">
            <? if ($user['level'] === 'admin') : ?>
              <a class="navigation-link rlr-navigation__link--so" target="_blank" href="../dashboard/admin">Dashboard Admin</a>
            <? elseif ($user['level'] === 'bisnis') : ?>
              <a class="navigation-link rlr-navigation__link--so" target="_blank" href="../dashboard/business">Dashboard Bisnis</a>
            <? else : ?>
              <a class="navigation-link rlr-navigation__link--so" target="_blank" href="../manage-listing/">Daftarkan Bisnis</a>
            <? endif; ?>
          </li>
          <li class="navigation-item">
            <a class="navigation-link" href="#"> <?= isset($_SESSION['user_id']) ? $user['nama'] : 'Guest' ?>
              <? if ($user && !is_null($user['avatar'])) : ?>
                <img class="ui right spaced rlr-avatar rlr-avatar__media--rounded" style="height: 32px; width: 32px;" src="../assets/images/avatar/<?= $user['avatar'] ?>" alt="account avatar" /> </a>
          <? else : ?>
            <div style="align-items: center; display: flex; justify-content: center; background-color: var(--brand); color: #fff; border-radius: 50%; height: 3rem; width: 3rem;">
              <?php
                $initials = "";
                $name_parts = explode(" ",  $user['nama'] ?? 'Guest');
                $i = 0;
                foreach ($name_parts as $part) {
                  if ($i < 2) {
                    $initials .= strtoupper(substr($part, 0, 1));
                  }
                  $i++;
                }
              ?>
              <span><?= $initials ?></span>
            </div>
          <? endif; ?>
          <ul class="navigation-dropdown">

            <?php if (isset($_SESSION['user_id'])) : ?>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../profile">Akun saya</a>
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../wishlist">Wishlist</a>
              </li>
              <li class="navigation-dropdown-item">
                <hr class="dropdown-divider rlr-dropdown__divider" />
              </li>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../logout/">Keluar</a>
              </li>
            <? else : ?>
              <li class="navigation-dropdown-item">
                <a class="navigation-dropdown-link" href="../login/">Login</a>
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
    <!-- Main Content -->
    <div class="container">
      <!-- Media Slider -->
      <aside class="row">
        <!-- Media main image carousel -->
        <div class="col-md-10 rlr-media">
          <div class="splide rlr-media--wrapper rlr-js-media">
            <!-- Arrows -->
            <div class="splide__arrows">
              <button class="rlr-media__arrow splide__arrow splide__arrow--prev">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1.889 14.942 8.47 8.36 1.889 1.778" stroke="var(--white)" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <button class="rlr-media__arrow splide__arrow splide__arrow--next">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1.889 14.942 8.47 8.36 1.889 1.778" stroke="var(--white)" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
            </div>
            <!-- Media main images -->
            <div class="splide__track rlr-media__strack">
              <ul id="image-preview" class="splide__list">
                <? foreach ($photos->read($business->idBisnis) as $photo) : ?>
                  <li class="splide__slide rlr-media__image-view">
                    <img class="lazyload" data-src="../assets/images/listings/<?= $photo['filename'] ?>" alt="media image" />
                  </li>
                <? endforeach; ?>
              </ul>
            </div>
            <!-- Media pagination counter -->
            <div class="rlr-media__custom-pagination rlr-js-custom-pagination">
              <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.2 0C.542 0 0 .558 0 1.235v11.53C0 13.442.542 14 1.2 14h15.6c.658 0 1.2-.558 1.2-1.235V1.235C18 .558 17.458 0 16.8 0H1.2zm0 .824h15.6c.228 0 .4.176.4.411v9.844l-3.506-3.95a.4.4 0 0 0-.588 0l-2.862 3.126L6.1 5.488a.4.4 0 0 0-.362-.135.4.4 0 0 0-.232.129L.8 10.687V1.235C.8 1 .972.823 1.2.823zm9.2 2.058c-.879 0-1.6.743-1.6 1.647 0 .905.721 1.647 1.6 1.647.879 0 1.6-.742 1.6-1.647 0-.904-.721-1.647-1.6-1.647zm0 .824c.447 0 .8.363.8.823 0 .46-.353.824-.8.824a.806.806 0 0 1-.8-.824c0-.46.353-.823.8-.823zm-4.606 2.67 5.912 6.8H1.2a.397.397 0 0 1-.4-.411v-.869l4.994-5.52zm7.6 1.64 3.806 4.285v.464a.397.397 0 0 1-.4.411h-4.019l-2-2.303 2.613-2.856z" fill="#212529"></path>
              </svg>
              <span class="rlr-media__page-counter rlr-js-page"> <?= count($photos->read($business->idBisnis)) ?> </span>
            </div>
          </div>
        </div>
        <!-- Media Thumbnails -->
        <div class="col-md-2 rlr-media">
          <!-- Media sidebar -->
          <div class="splide rlr-media--wrapper rlr-media--sidebar rlr-js-thumbnail-media">
            <!-- Arrows -->
            <div class="splide__arrows">
              <button class="rlr-media__arrow splide__arrow splide__arrow--prev">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1.889 14.942 8.47 8.36 1.889 1.778" stroke="var(--white)" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
              <button class="rlr-media__arrow splide__arrow splide__arrow--next">
                <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1.889 14.942 8.47 8.36 1.889 1.778" stroke="var(--white)" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
              </button>
            </div>
            <!-- Thumbnails -->
            <div class="splide__track rlr-media__strack">
              <ul id="image-preview-thumb" class="splide__list">
                <? foreach ($photos->read($business->idBisnis) as $photo) : ?>
                  <li class="splide__slide rlr-media__image-view">
                    <img class="rlr-media__thumb lazyload" data-src="../assets/images/listings/<?= $photo['filename'] ?>" alt="media image" />
                  </li>
                <? endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
      </aside>
      <!-- Product Detail Sextion -->
      <section class="row rlr-product-detail-section">
        <div class="rlr-product-detail-section__details col-xl-8">
          <!-- Product Detail Header -->
          <div class="rlr-product-detail-header" id="rlr-js-detail-header">
            <div class="rlr-product-detail-header__contents">
              <!-- Breadcrumb -->
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb rlr-breadcrumb__items">
                  <li class="breadcrumb-item rlr-breadcrumb__item"><a href="/">Home</a></li>
                  <li class="breadcrumb-item rlr-breadcrumb__item"><a href="/">Kategori</a></li>
                  <li class="breadcrumb-item rlr-breadcrumb__item active" aria-current="page"><?= $business_service->getCategoryByBusinessId($business->idBisnis)['nama'] ?></li>
                </ol>
              </nav>
              <h1 class="rlr-section__heading--main rlr-product-detail-header__title"><?= $business->nama ?></h1>
              <div class="rlr-review-stars" itemscope itemtype="https://schema.org/Product">
                <div class="rlr-review-stars" itemprop="ratingValue" itemscope itemtype="https://schema.org/Product">
                  <?
                  $stars = round($reviews->getAverageRatingById($business->idBisnis));
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
                <div class="rlr-review-stars__content">
                  <span class="rlr-review-stars__count"><?= $reviews->getTotalReviewsById($business->idBisnis) ?></span>
                  <span> Ulasan</span>
                </div>
              </div>
            </div>
            <div class="rlr-product-detail-header__actions">
              <button type="button" data-bs-toggle="popover-share" data-content-id="rlr-js-share-popover" id="rlr-js-share-button" class="btn rlr-button rlr-button--circle rlr-popover-button" aria-label="share">
                <i class="rlr-icon-font flaticon-share-1"></i>
              </button>
              <div id="rlr-js-share-popover" class="rlr-popover--hide">
                <div class="rlr-share">
                  <h3 class="rlr-share__title">Bagikan</h3>
                  <ul class="rlr-share__items">
                    <li class="rlr-share__list rlr-js--twitter">
                      <a href="http://twitter.com/share?text=Lihat tujuan wisata ini&url=<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>&hashtags=tourism,pariwisata" class="rlr-icon-text rlr-icon-text--anchor rlr-icon-text__block rlr-share__item"> <i class="rlr-icon-font flaticon-twitter"> </i> <span class="rlr-share__title">Twitter </span>
                      </a>
                    </li>
                    <li class="rlr-share__list rlr-js--whatsapp">
                      <a href="whatsapp://send?text=Lihat tujuan wisata ini: <?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" class="rlr-icon-text rlr-icon-text--anchor rlr-icon-text__block rlr-share__item"> <i class="rlr-icon-font flaticon-whatsapp"> </i> <span class="rlr-share__title">Whatsapp </span>
                      </a>
                    </li>
                    <li class="rlr-share__list rlr-js--email">
                      <a href="mailto:?subject=Lihat tujuan wisata ini&amp;body=Kunjungi websitenya <?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" class="rlr-icon-text rlr-icon-text--anchor rlr-icon-text__block rlr-share__item"> <i class="rlr-icon-font flaticon-email-1"> </i> <span class="rlr-share__title">Email </span> </a>
                    </li>
                  </ul>
                  <div class="rlr-copylink">
                    <label class="rlr-copylink__title">Share link</label>
                    <div class="rlr-copylink__wrapper">
                      <input type="text" id="sharable-link" autocomplete="off" class="form-control rlr-copylink__input" value='<?= "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>' />
                      <i id="copySharableLink" class="rlr-icon-font flaticon-copy"> </i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="rlr-product-detail-header__button-wrapper">

                <? if (isset($user['id_pengguna'])) : ?>
                  <button type="button" id="<?= $business->idBisnis ?>" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button rlr-js-action-wishlist  <?= $wishlist->isWishlist($user['id_pengguna'], $business->idBisnis) ? 'is-active' : '' ?>" aria-label="Save to Wishlist">
                    <i class="rlr-icon-font flaticon-heart-1"> </i>
                  </button>
                <? endif; ?>
                <span class="rlr-product-detail-header__helptext rlr-js-helptext"></span>
              </div>
            </div>
          </div>
          <!-- Secondary Menu -->
          <nav class="rlr-product-detail-secondary-menu px-5">
            <ul class="rlr-product-detail-secondary-menu__tabitems" id="rlr-js-secondary-menu">
              <li class="rlr-product-detail-secondary-menu__tabitem js-tabitem is-active" id="rlr-product-sec-overview">
                <span>Tentang</span>
              </li>
              <li class="rlr-product-detail-secondary-menu__tabitem js-tabitem" id="rlr-product-sec-inclusion">
                <span>Informasi Layanan</span>
              </li>
              <li class="rlr-product-detail-secondary-menu__tabitem js-tabitem" id="rlr-product-sec-review">
                <span>Ulasan</span>
              </li>
              <li class="rlr-product-detail-secondary-menu__tabitem js-tabitem" id="rlr-product-sec-faq">
                <span>FAQ</span>
              </li>
            </ul>
          </nav>
          <!-- Overview -->
          <div class="rlr-secondary-menu-desc" data-id="rlr-product-sec-overview">
            <div class="rlr-secondary-menu-desc__icon">
              <svg width="41" height="51" viewBox="0 0 41 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M40.327 13.61H28.296c-.334 0-.558-.221-.558-.55l.002-11.852c0-.329.224-.55.558-.55.334 0 .558.221.558.55l-.002 11.304h11.473c.334 0 .558.22.558.55 0 .33-.224.547-.558.547z" fill="#99A3AD" />
                <path d="M36.54 50.707H4.568C2.005 50.707 0 48.73 0 46.207L.002 5.047c0-2.525 2.005-4.5 4.568-4.5h23.728c.11 0 .334.109.445.109L40.885 12.51c.11.11.11.22.11.439v33.255c.113 2.527-1.892 4.503-4.455 4.503zM4.568 1.756c-1.892 0-3.342 1.428-3.342 3.292v41.158c0 1.867 1.56 3.402 3.453 3.402H36.65c1.894 0 3.453-1.537 3.453-3.402l.002-32.926-11.92-11.524H4.567z" fill="#99A3AD" />
                <path d="M33.309 19.756h-19.27c-.335 0-.558-.22-.558-.55 0-.329.223-.549.557-.549h19.273c.334 0 .558.22.558.55-.002.329-.226.55-.56.55zM33.309 25.133H7.91c-.334 0-.558-.22-.558-.55 0-.328.224-.549.558-.549h25.399c.334 0 .558.22.558.55 0 .331-.224.55-.558.55zM33.309 30.622H7.91c-.334 0-.558-.22-.558-.55 0-.329.224-.55.558-.55h25.399c.334 0 .558.221.558.55 0 .33-.224.55-.558.55zM33.309 36.11H7.91c-.334 0-.558-.22-.558-.55 0-.329.224-.549.558-.549h25.399c.334 0 .558.22.558.55 0 .329-.224.55-.558.55zM33.309 41.487H7.91c-.334 0-.558-.22-.558-.549 0-.33.224-.55.558-.55h25.399c.334 0 .558.22.558.55 0 .33-.224.55-.558.55z" fill="#99A3AD" />
              </svg>
            </div>
            <div class="rlr-secondary-menu-desc__details">
              <div class="rlr-overview-detail">
                <div class="rlr-readmore-desc rlr-overview-detail__description">
                  <p class="rlr-readmore-desc__content rlr-js-desc">

                    <?
                    $admin_message = $business_service->readRejectedBusinessListing($business->idBisnis);
                    if (isset($isNotActive) && $isNotActive) : ?>
                  <div class="pending-message-wrapper mb-5 px-4 py-3" style="width: 100%; border-radius: 12px; border-color: #FF9700; background-color: #FF9700; color: #ffffff;">
                    <h3 class="rlr-section__heading--main mb-2"><?= $business->status == 'pending' ? 'Bisnis belum aktif' : 'Bisnis ditolak' ?></h3>
                    <h5 class="rlr-section__heading--sub"><?= $business->status == 'pending' ? 'Bisnis Anda sedang menunggu persetujuan Admin.' : 'Bisnis ditolak.' ?> <?= $admin_message ? ' Alasan: ' . $admin_message[0]['alasan'] : '' ?></h5>
                  </div>
                <? endif; ?>

                <?= $business->deskripsi ?>
                </p>

                <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Inclusion and Exclusions -->
          <div class="rlr-secondary-menu-desc" data-id="rlr-product-sec-inclusion">
            <!-- Icon -->
            <div class="rlr-secondary-menu-desc__icon">
              <svg width="50" height="56" viewBox="0 0 50 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.0157 0.127686H24.9679C21.6869 0.129864 18.4385 0.787955 15.4081 2.06438C12.3776 3.34081 9.62461 5.21058 7.30612 7.56694C4.98764 9.92329 3.14911 12.7201 1.89553 15.7976C0.641942 18.8752 -0.00215716 22.1732 5.42768e-06 25.5034C0.000252218 25.6667 0.0590139 25.8244 0.165333 25.9469C0.271652 26.0695 0.41827 26.1487 0.577845 26.1696C0.737421 26.1906 0.89906 26.1519 1.03262 26.0609C1.16618 25.9698 1.26255 25.8325 1.30374 25.6746C1.47986 24.998 2.10654 23.0634 2.89022 22.2039C3.99055 21.1594 5.33307 20.6056 6.77668 20.6172C8.20636 20.6136 9.57983 21.182 10.5995 22.1992C11.6258 23.3104 12.1684 24.6663 12.1684 26.1199C12.1684 26.2984 12.2382 26.4695 12.3626 26.5957C12.4869 26.7219 12.6555 26.7928 12.8313 26.7928C13.0071 26.7928 13.1758 26.7219 13.3001 26.5957C13.4244 26.4695 13.4942 26.2984 13.4942 26.1199C13.4942 24.6681 14.035 23.3147 15.058 22.2046C16.1518 21.1661 17.4853 20.6173 18.9187 20.6173C19.6312 20.6115 20.3379 20.7482 20.9984 21.0196C21.6589 21.2911 22.2602 21.6919 22.768 22.1993C23.7943 23.3105 24.3369 24.6665 24.3369 26.1199V44.326C24.3369 45.5699 23.8501 46.7629 22.9835 47.6424C22.1169 48.522 20.9416 49.0162 19.7161 49.0162C18.4905 49.0162 17.3152 48.522 16.4486 47.6424C15.582 46.7629 15.0952 45.5699 15.0952 44.326V42.1116C15.0952 41.9331 15.0253 41.762 14.901 41.6358C14.7767 41.5096 14.6081 41.4387 14.4323 41.4387C14.2565 41.4387 14.0878 41.5096 13.9635 41.6358C13.8392 41.762 13.7694 41.9331 13.7694 42.1116V44.326C13.7694 45.9268 14.3959 47.4621 15.5111 48.594C16.6263 49.7259 18.1389 50.3619 19.7161 50.3619C21.2932 50.3619 22.8058 49.7259 23.921 48.594C25.0362 47.4621 25.6628 45.9268 25.6628 44.326V26.1199C25.6628 24.6679 26.2034 23.3148 27.2265 22.2051C28.3203 21.1667 29.6536 20.6179 31.0872 20.6179H31.1138C32.5435 20.6143 33.917 21.1828 34.9366 22.2C35.9629 23.3111 36.5055 24.6665 36.5055 26.1206C36.5055 26.2991 36.5754 26.4702 36.6997 26.5964C36.824 26.7226 36.9926 26.7935 37.1685 26.7935C37.3443 26.7935 37.5129 26.7226 37.6372 26.5964C37.7615 26.4702 37.8314 26.2991 37.8314 26.1206C37.8314 24.6689 38.372 23.3155 39.3952 22.2052C40.489 21.1667 41.8224 20.618 43.2557 20.618H43.2824C44.7121 20.6144 46.0856 21.1827 47.1053 22.1999C47.8914 23.0567 48.5195 24.9971 48.6963 25.6745C48.7375 25.8324 48.8338 25.9697 48.9674 26.0608C49.101 26.1518 49.2626 26.1905 49.4222 26.1695C49.5818 26.1486 49.7284 26.0694 49.8347 25.9468C49.941 25.8242 49.9998 25.6665 50 25.5032V25.4707C49.9958 18.7479 47.3617 12.3019 42.6767 7.5496C37.9917 2.79732 31.6392 0.127744 25.0157 0.127686ZM48.0616 21.268C48.0558 21.2616 48.0497 21.2553 48.0435 21.2491C46.7765 19.9788 45.0662 19.2682 43.2853 19.272H43.2522C41.4716 19.272 39.8184 19.9522 38.4714 21.239C38.4617 21.2483 38.4522 21.258 38.443 21.2678C37.9254 21.8213 37.4952 22.4528 37.168 23.1398C36.841 22.4529 36.4112 21.8214 35.8938 21.268C35.8878 21.2616 35.8817 21.2552 35.8757 21.2491C34.6086 19.9788 32.8983 19.2682 31.1173 19.272H31.0842C29.3035 19.272 27.6503 19.9522 26.3035 21.239C26.2937 21.2483 26.2843 21.2579 26.2752 21.2677C25.7574 21.8211 25.3272 22.4525 24.9998 23.1395C24.6726 22.4525 24.2425 21.821 23.7248 21.2675C23.7189 21.2611 23.7129 21.2548 23.7067 21.2487C22.4397 19.9784 20.7294 19.2678 18.9485 19.2716H18.9154C17.1347 19.2716 15.4816 19.9517 14.1344 21.2386C14.1247 21.2479 14.1152 21.2575 14.106 21.2674C13.5885 21.8209 13.1586 22.4525 12.8314 23.1395C12.5042 22.4524 12.0741 21.821 11.5564 21.2675C11.5505 21.2611 11.5445 21.2548 11.5383 21.2487C10.2714 19.9785 8.56139 19.2678 6.78066 19.2715H6.74751C4.9668 19.2715 3.3136 19.9516 1.96655 21.2385C1.95683 21.2478 1.94733 21.2574 1.93816 21.2674C1.82613 21.3891 1.72132 21.5174 1.62426 21.6517C2.52389 16.0239 5.36592 10.9042 9.64244 7.20767C13.9189 3.51112 19.3512 1.47861 24.9683 1.47341H24.9998C25.0051 1.47273 25.0105 1.47273 25.0157 1.47341C30.6357 1.47473 36.0719 3.50557 40.3517 7.20259C44.6315 10.8996 47.4758 16.0216 48.3756 21.6523C48.2785 21.518 48.1737 21.3896 48.0616 21.268Z" fill="#99A3AD" />
                <path d="M49.6287 34.0793L39.8939 29.2311C39.8032 29.1859 39.7034 29.1624 39.6023 29.1624C39.5011 29.1624 39.4014 29.1859 39.3106 29.2311L29.5758 34.0655C29.4644 34.1209 29.3705 34.2069 29.3048 34.3137C29.2391 34.4206 29.2043 34.544 29.2043 34.67V43.919C29.2043 44.9461 29.4805 46.0925 30.0031 47.2341C30.6726 48.6962 31.6698 49.9592 32.739 50.6977L39.2287 55.1857C39.3387 55.2618 39.4688 55.3025 39.6019 55.3025C39.7351 55.3025 39.8651 55.2618 39.9751 55.1857L46.465 50.6976C48.447 49.3275 49.9997 46.3499 49.9997 43.9189V34.6834C49.9997 34.5576 49.9649 34.4343 49.8993 34.3275C49.8337 34.2207 49.74 34.1347 49.6287 34.0793ZM48.6738 43.9187C48.6738 45.8829 47.3205 48.4779 45.7186 49.5853L39.6019 53.8151L33.4852 49.5853C32.6078 48.9787 31.777 47.915 31.2054 46.6666C30.7699 45.7153 30.53 44.7394 30.53 43.9191V35.0895L39.6009 30.5845L48.6733 35.1027L48.6738 43.9187Z" fill="#99A3AD" />
                <path d="M35.9457 41.4619C35.8196 41.3385 35.6505 41.2707 35.4754 41.2734C35.3002 41.2761 35.1332 41.349 35.0109 41.4763C34.8885 41.6036 34.8208 41.7749 34.8225 41.9527C34.8242 42.1305 34.8952 42.3004 35.0199 42.4253L37.9605 45.3362C38.0844 45.4587 38.2505 45.5274 38.4234 45.5274C38.5964 45.5274 38.7625 45.4587 38.8863 45.3362L44.1839 40.0917C44.3086 39.9668 44.3796 39.7969 44.3813 39.6191C44.383 39.4413 44.3153 39.27 44.193 39.1427C44.0706 39.0154 43.9036 38.9425 43.7285 38.9398C43.5533 38.9371 43.3842 39.0049 43.2581 39.1284L38.4239 43.9146L35.9457 41.4619Z" fill="#99A3AD" />
              </svg>
            </div>
            <!-- Overview -->
            <div class="rlr-secondary-menu-desc__details">
              <div class="rlr-readmore-desc">
                <p class="rlr-readmore-desc__content rlr-js-desc">
                  Daftar layanan berikut diperoleh dari pengelola listing.
                </p>
                <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
              </div>
              <ul class="list-group list-group-flush rlr-secondary-menu-desc__list-group">
                <!-- Inclusions -->
                <? foreach ($services->readAvailable($business->idBisnis) as $available) : ?>
                  <li class="rlr-icon-text rlr-secondary-menu-desc__list">
                    <i class="rlr-icon-font flaticon-check-rounded"></i>
                    <span class="rlr-icon-text__text"><?= $available['layanan'] ?></span>
                  </li>
                <? endforeach; ?>

                <!-- Exclusion -->
                <? foreach ($services->readUnavailable($business->idBisnis) as $unavailable) : ?>
                  <li class="rlr-icon-text rlr-secondary-menu-desc__list">
                    <i class="rlr-icon-font flaticon-cross-rounded"></i>
                    <span class="rlr-icon-text__text"><?= $unavailable['layanan'] ?></span>
                  </li>
                <? endforeach; ?>
              </ul>
            </div>
          </div>
          <!-- Reviews -->
          <div class="rlr-secondary-menu-desc" data-id="rlr-product-sec-review">
            <!-- Icon -->
            <div class="rlr-secondary-menu-desc__icon">
              <svg width="51" height="52" viewBox="0 0 51 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M51 26.0569C51 11.9289 39.5833 0.47583 25.5 0.47583C11.4167 0.47583 0 11.9289 0 26.0569C0 40.1849 11.4167 51.6379 25.5 51.6379C29.9108 51.6379 34.1604 50.5124 37.9266 48.4C37.9928 48.4321 38.0883 48.4895 38.2244 48.5807C38.2823 48.6195 38.741 48.9376 38.907 49.0479C39.2393 49.2688 39.5554 49.4601 39.9016 49.6434C42.0271 50.7687 44.7903 51.3004 48.7583 51.0338C49.483 50.9851 49.7938 50.0869 49.2549 49.5983C47.8766 48.3484 46.4596 46.762 45.4972 45.3922C45.0172 44.7088 44.6728 44.1109 44.498 43.6599C44.43 43.4842 44.3928 43.3435 44.383 43.2488C48.6135 38.5755 51 32.502 51 26.0569ZM40.6695 48.1838C40.3757 48.0282 40.1053 47.8646 39.8158 47.6722C39.6669 47.5732 39.2115 47.2574 39.1383 47.2083C38.4835 46.7696 38.0509 46.609 37.4761 46.7944C37.3816 46.8249 37.2904 46.8654 37.2024 46.9152C33.6605 48.9194 29.6586 49.9875 25.5 49.9875C12.3253 49.9875 1.64516 39.2734 1.64516 26.0569C1.64516 12.8403 12.3253 2.12622 25.5 2.12622C38.6747 2.12622 49.3548 12.8403 49.3548 26.0569C49.3548 32.125 47.0956 37.837 43.0914 42.2203C41.9783 43.4387 43.8851 46.5383 46.7284 49.4515C44.0844 49.4414 42.173 48.9798 40.6695 48.1838Z" fill="#99A3AD" />
                <path d="M31.9727 35.6478L25.6956 31.2659C25.4132 31.0688 25.0384 31.0688 24.756 31.2659L18.4789 35.6478L20.6934 28.3049C20.793 27.9745 20.6771 27.6169 20.403 27.4084L14.3091 22.7736L21.9548 22.6173C22.2987 22.6103 22.602 22.3892 22.715 22.0632L25.2258 14.8169L27.7366 22.0632C27.8496 22.3892 28.1529 22.6103 28.4969 22.6173L36.1425 22.7736L30.0486 27.4084C29.7745 27.6169 29.6586 27.9745 29.7582 28.3049L31.9727 35.6478ZM25.2258 32.9486L32.9755 38.3584C33.6156 38.8053 34.4585 38.191 34.2327 37.4421L31.4987 28.3766L39.0222 22.6545C39.6437 22.1819 39.3218 21.1879 38.542 21.1719L29.1027 20.979L26.0028 12.0327C25.7467 11.2937 24.7049 11.2937 24.4488 12.0327L21.349 20.979L11.9096 21.1719C11.1299 21.1879 10.8079 22.1819 11.4294 22.6545L18.9529 28.3766L16.219 37.4421C15.9931 38.191 16.836 38.8053 17.4761 38.3584L25.2258 32.9486Z" fill="#99A3AD" />
              </svg>
            </div>
            <div class="rlr-secondary-menu-desc__details">
              <div class="d-lg-flex justify-content-between py-4">
                <div class="d-flex justify-content-start gap-2">
                  <i class="rlr-icon-font flaticon-star-1 m-0" style="font-size: 1.5rem;"> </i>
                  <h1 class="rlr-section__heading--main rlr-product-detail-header__title m-0" style="font-size: 1.5rem;"><?= round($reviews->getAverageRatingById($business->idBisnis), 1) ?> · <?= $reviews->getTotalReviewsById($business->idBisnis) ?> ulasan</h1>
                </div>
                <? if (isset($user['level']) && $user['level'] === 'pengguna') : ?>
                  <button type="button" class="btn btn-add-review rlr-button rlr-button--gray-00 text-black px-4 py-2" style="border: 0.1px solid lightgray; border-radius: 8px;" id="addReviewModalBtn">Tambah
                    ulasan</button>
                <? endif; ?>
              </div>
              <!-- Review -->

              <?
              foreach ($reviews->read($business->idBisnis, 0, 3, null, null, 'Ulasan terbaru') as $review) :
                $pengulas = $pengguna->read($review['id_pengguna']);
              ?>

                <article class="rlr-review-card my-3" itemscope itemtype="https://schema.org/Product">
                  <div class="rlr-review-card__contact">
                    <!--Using in Components -->
                    <div class="rlr-avatar d-flex">
                      <? if ($pengulas && !is_null($pengulas['avatar'])) : ?>
                        <img class="rlr-avatar__media--rounded" src="../assets/images/avatar/<?= $pengulas['avatar'] ?>" itemprop="avatar" alt="avatar icon" />
                      <? else : ?>
                        <div style="align-items: center; display: flex; justify-content: center; background-color: var(--brand); color: #fff; border-radius: 50%; height: 56px; width: 56px;">
                          <?php
                          $initials = "";
                          $name_parts = explode(" ",  $pengulas['nama']);
                          $i = 0;
                          foreach ($name_parts as $part) {
                            if ($i < 2) {
                              $initials .= strtoupper(substr($part, 0, 1));
                            }
                            $i++;
                          }
                          ?>
                          <span><?= $initials ?></span>
                        </div>
                      <? endif; ?> </a>
                      <div class="d-flex flex-column ml-2">
                        <span class="rlr-avatar__name" style="font-weight: 500;" itemprop="name"><?= $pengulas['nama'] ?></span>
                        <span class="rlr-avatar__name" style="font-weight: 300; font-size: 90%" itemprop="date"><?= $review['waktu'] ?></span>
                      </div>
                    </div>
                    <div class="rlr-review-stars" itemprop="ratingValue" itemscope itemtype="https://schema.org/Product">
                      <?
                      for ($i = 0; $i < $review['rating']; $i++) {
                        echo '<i class="rlr-icon-font flaticon-star-1"></i>';
                      }
                      if ($review['rating'] < 5) {
                        for ($i = 0; $i < 5 - $review['rating']; $i++) {
                          echo '<i class="rlr-icon-font flaticon-star"></i>';
                        }
                      }
                      ?>
                    </div>
                  </div>
                  <div class="rlr-review-card__details">
                    <div class="rlr-review-card__title gap-4">
                      <h3 class="rlr-review-card__title-review"><?= $review['judul'] ?></h3>
                      <? if (isset($user['level'])) : ?>
                        <span class="rlr-svg-icon button-report-review" data-id-ulasan="<?= $review['id_ulasan'] ?>">
                          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_iconCarrier">
                              <path d="M6 14.4623H16.1909C17.6066 14.4623 18.472 12.7739 17.7261 11.4671L17.2365 10.6092C16.7547 9.76504 16.7547 8.69728 17.2365 7.85309L17.7261 6.99524C18.472 5.68842 17.6066 4 16.1909 4L6 4L6 14.4623ZM6 14.4623L6 20" stroke="#363853" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                          </svg>
                        </span>
                      <? endif; ?>
                    </div>
                    <div class="rlr-review-card__comments mb-4" itemprop="review description">
                      <div class="rlr-readmore-desc">
                        <p class="rlr-readmore-desc__content rlr-js-desc"><?= $review['komentar'] ?></p>
                        <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
                        <? if ($reviewphotos->read($review['id_ulasan'])) : ?>
                          <div class="rlr-itinerary__media-group">
                            <?
                            $i = 1;
                            foreach ($reviewphotos->read($review['id_ulasan']) as $photo) : ?>
                              <div class="rlr-itinerary__media mb-0">
                                <a data-fslightbox="review-images-main<?= $photo['id_foto_ulasan'] ?>" href="../assets/images/reviews/<?= $photo['filename'] ?>">
                                  <figure class="rlr-lightbox--gallery__figure">
                                    <img style="object-fit: cover;" class="rlr-lightbox--gallery__img" src="../assets/images/reviews/<?= $photo['filename'] ?>" />
                                    <figcaption class="rlr-lightbox--gallery__figcaption">
                                      <span><?= $i ?></span>
                                    </figcaption>
                                  </figure>
                                </a>
                              </div>
                            <?
                              $i++;
                            endforeach; ?>
                          </div>
                        <? endif; ?>
                      </div>
                    </div>
                    <? if (!$reviews->readBusinessReply($review['id_ulasan']) && isset($user['level']) && $user['level'] === "bisnis" && $business_service->getBusinessByUserId($user['id_pengguna'])->idBisnis === $business->idBisnis) : ?>
                      <a class="rlr-readmore-desc__content rlr-js-desc description-url" data-id-ulasan="<?= $review['id_ulasan'] ?>" data-id-bisnis="<?= $business->idBisnis ?>" id="replyReviewModalBtn">Balas ulasan</a>
                    <? endif ?>
                  </div>
                </article>

                <?
                $pemilik = $pengguna->read($business->idPengguna);
                $balasan = $reviews->readBusinessReply($review['id_ulasan']);
                if ($balasan) : ?>
                  <article class="rlr-review-card my-3 ms-5" itemscope itemtype="https://schema.org/Product">
                    <div class="rlr-review-card__contact">
                      <!--Using in Components -->
                      <div class="rlr-avatar d-flex ">
                        <? if (!is_null($pemilik['avatar'])) : ?>
                          <img class="rlr-avatar__media--rounded" src="../assets/images/avatar/<?= $pemilik['avatar'] ?>" itemprop="avatar" alt="avatar icon" />
                        <? else : ?>
                          <div style="align-items: center; display: flex; justify-content: center; background-color: var(--brand); color: #fff; border-radius: 50%; height: 56px; width: 56px;">
                            <?php
                            $initials = "";
                            $name_parts = explode(" ",  $pemilik['nama']);
                            $i = 0;
                            foreach ($name_parts as $part) {
                              if ($i < 2) {
                                $initials .= strtoupper(substr($part, 0, 1));
                              }
                              $i++;
                            }
                            ?>
                            <span><?= $initials ?></span>
                          </div>
                        <? endif; ?> </a>
                        <div class="d-flex flex-column ml-2">
                          <span class="rlr-avatar__name" style="font-weight: 500;" itemprop="name">Balasan dari <?= $pemilik['nama'] ?></span>
                          <span class="rlr-avatar__name" style="font-weight: 300; font-size: 90%" itemprop="date">Pengelola <?= $business->nama ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="rlr-review-card__details">
                      <div class="rlr-review-card__title gap-4">
                        <h3 class="rlr-review-card__title-review"><?= $balasan['judul'] ?></h3>
                      </div>
                      <div class="rlr-review-card__comments mb-4" itemprop="review description">
                        <div class="rlr-readmore-desc">
                          <p class="rlr-readmore-desc__content rlr-js-desc"><?= $balasan['komentar'] ?></p>
                          <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
                        </div>
                      </div>
                    </div>
                  </article>
                <? endif; ?>

              <?
              endforeach;
              ?>
              <div class="rlr-secondary-menu-desc__footer">
                <button type="button" class="btn mb-3 rlr-button rlr-button--gray-00 text-black px-4 py-2" id="showReviewModalBtn" style="border: 0.1px solid lightgray; border-radius: 8px;">Tampilkan semua
                  ulasan</button>
              </div>
            </div>
          </div>
          <!-- FAQ-->
          <div class="rlr-secondary-menu-desc" data-id="rlr-product-sec-faq">
            <!-- Icon -->
            <div class="rlr-secondary-menu-desc__icon">
              <svg width="52" height="54" viewBox="0 0 52 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M47.9971 40.8774C51.3711 35.5059 52.6762 29.0842 51.6677 22.8163C50.6593 16.5484 47.4065 10.8647 42.5192 6.8308C37.6319 2.79691 31.4458 0.689826 25.1205 0.904586C18.7953 1.11935 12.7653 3.6412 8.16107 7.99736C3.55685 12.3535 0.694579 18.2448 0.110868 24.5668C-0.472843 30.8887 1.26209 37.2072 4.99041 42.3376C8.71873 47.468 14.1844 51.0581 20.3627 52.4347C26.541 53.8113 33.0077 52.8799 38.5504 49.8151L49.3317 52.6147C49.6365 52.693 49.9567 52.6867 50.2582 52.5963C50.5596 52.506 50.8309 52.3351 51.0428 52.1019C51.2548 51.8687 51.3995 51.582 51.4614 51.2726C51.5234 50.9632 51.5002 50.6427 51.3944 50.3455L47.9971 40.8774ZM38.6544 48.0328C38.4348 47.9753 38.2014 48.0065 38.0044 48.1198C32.8405 51.0625 26.7801 52.0034 20.971 50.7642C15.162 49.525 10.0076 46.1917 6.48404 41.3957C2.96052 36.5996 1.31253 30.6739 1.85219 24.7406C2.39186 18.8074 5.08169 13.2787 9.41227 9.2016C13.7429 5.12451 19.4135 2.78212 25.3502 2.61806C31.2869 2.454 37.0775 4.47967 41.6252 8.31141C46.173 12.1432 49.1622 17.5149 50.0266 23.4094C50.891 29.3038 49.5707 35.3116 46.3158 40.2949C46.2423 40.4065 46.1955 40.5336 46.1789 40.6663C46.1624 40.799 46.1766 40.9337 46.2204 41.06L49.7651 50.9367L38.6544 48.0328Z" fill="#99A3AD" />
                <path d="M25.5503 34.2444C24.8943 34.2199 24.2553 34.4575 23.7737 34.9051C23.5472 35.1221 23.3685 35.3843 23.2491 35.6748C23.1297 35.9652 23.0722 36.2775 23.0803 36.5916C23.0679 36.9042 23.1226 37.2158 23.2407 37.5053C23.3589 37.7947 23.5377 38.0554 23.765 38.2695C24.267 38.6903 24.9004 38.9209 25.5547 38.9209C26.2089 38.9209 26.8423 38.6903 27.3443 38.2695C27.5665 38.0555 27.7415 37.7972 27.858 37.5112C27.9744 37.2252 28.0297 36.9178 28.0203 36.609C28.0285 36.2949 27.971 35.9826 27.8516 35.6922C27.7322 35.4017 27.5535 35.1395 27.327 34.9225C27.0905 34.697 26.8116 34.5209 26.5066 34.4045C26.2016 34.288 25.8765 34.2336 25.5503 34.2444Z" fill="#99A3AD" />
                <path d="M30.872 17.7334C29.4476 16.6114 27.6615 16.0543 25.854 16.1684C24.0226 16.0615 22.2183 16.6514 20.8014 17.8203C20.1747 18.3996 19.6812 19.1086 19.355 19.8983C19.0287 20.688 18.8776 21.5394 18.912 22.3935H23.332C23.3148 21.6853 23.5692 20.9976 24.0427 20.4721C24.2777 20.2352 24.5595 20.0502 24.8699 19.929C25.1803 19.8077 25.5126 19.7528 25.8453 19.7678C27.4313 19.7678 28.2287 20.6373 28.2287 22.3761C28.2256 22.9526 28.0668 23.5174 27.7693 24.0106C27.2536 24.7605 26.6351 25.4336 25.932 26.0103C25.117 26.6886 24.4678 27.5451 24.034 28.5142C23.6467 29.6069 23.4702 30.7635 23.514 31.9224H27.414L27.4747 30.9921C27.5919 29.9917 28.0623 29.0664 28.8007 28.3838L30.04 27.2014C30.854 26.4594 31.5393 25.5867 32.068 24.6192C32.4542 23.8572 32.6533 23.0136 32.6487 22.1587C32.6975 21.3352 32.5635 20.511 32.2562 19.7457C31.949 18.9804 31.4762 18.2931 30.872 17.7334V17.7334Z" fill="#99A3AD" />
              </svg>
            </div>
            <div class="rlr-secondary-menu-desc__details">
              <!-- Faq Items -->
              <div class="accordion rlr-accordion">

                <?
                foreach ($faqs->read($business->idBisnis) as $faq) : ?>
                  <div class="accordion-item rlr-accordion__item" style="border-radius: 0;">
                    <div class="accordion-header rlr-accordion__header" id="rlr-faq-collapse-header<?= $faq['id_faq_bisnis'] ?>">
                      <button class="accordion-button rlr-accordion__button" type="button" data-bs-toggle="collapse" data-bs-target="#rlr-faq-collapse<?= $faq['id_faq_bisnis'] ?>" aria-expanded="true" aria-controls="rlr-faq-collapse<?= $faq['id_faq_bisnis'] ?>">
                        <span class="rlr-accordion__badge">?</span> <?= $faq['pertanyaan'] ?>
                      </button>
                    </div>
                    <div id="rlr-faq-collapse<?= $faq['id_faq_bisnis'] ?>" class="accordion-collapse collapse show" aria-labelledby="rlr-faq-collapse-header<?= $faq['id_faq_bisnis'] ?>">
                      <div class="accordion-body rlr-accordion__body">
                        <div class="rlr-readmore-desc">
                          <p class="rlr-readmore-desc__content rlr-js-desc">
                            <?= $faq['jawaban'] ?>
                          </p>
                          <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
                        </div>
                      </div>
                    </div>
                  </div>
                <?
                endforeach; ?>

              </div>
            </div>
          </div>
        </div>
        <!-- Booking Form -->
        <aside class="col-xl-4 col-xxxl-3 d-xl-block offset-xxxl-1 mt-5 mt-lg-0">
          <form class="rlr-booking-card pb-3">
            <fieldset class="rlr-fieldrow">
              <legend class="rlr-booking-card__legend mb-3">Lokasi dan kontak</legend>
            </fieldset>
            <div class="rlr-lightbox--gallery">
              <a data-fslightbox="custom-google-maps" data-class="d-block" href="#google-maps">
                <figure class="rlr-lightbox--gallery__figure" style="margin-bottom: var(--spacing-7);">
                  <div class="rlr-fieldrow__map" id="map"></div>
                </figure>
              </a>
              <iframe src="https://maps.google.com/?q=<?= $business->lat ?>,<?= $business->lng ?>&output=embed" id="google-maps" allow="autoplay; fullscreen" width="1920" height="1080"> </iframe>
            </div>
            <fieldset class="rlr-booking-card__results rlr-booking-card__results--found mt-0">
              <ul class="rlr-booking-card__result-list">
                <li class="rlr-icon-text">
                  <i class="rlr-icon-font flaticon-map-marker"> </i>
                  <div class="rlr-icon-text__text-wrapper">
                    <span class="description-url" onclick="window.open('https://maps.google.com/?q=<?= $business->lat ?>,<?= $business->lng ?>', '_blank')">
                      <?= $business->alamat ?>
                      <span class="rlr-svg-icon">
                        <svg width="16" height="16" viewBox="-1.44 -1.44 26.88 26.88" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000000" stroke-width="1.104" stroke-linecap="round" stroke-linejoin="miter">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_iconCarrier">
                            <polyline points="17 14 17 7 10 7"></polyline>
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                          </g>
                        </svg>
                      </span>
                    </span>
                  </div>
                </li>
              </ul>
              <ul class="rlr-booking-card__result-list">
                <li class="rlr-icon-text">
                  <i class="rlr-icon-font flaticon-globe"> </i>
                  <div class="rlr-icon-text__text-wrapper">
                    <span class="description-url" onclick="window.location.href='<?= $business->website ?>'">
                      Website
                      <span class="rlr-svg-icon">
                        <svg width="16" height="16" viewBox="-1.44 -1.44 26.88 26.88" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000000" stroke-width="1.104" stroke-linecap="round" stroke-linejoin="miter">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_iconCarrier">
                            <polyline points="17 14 17 7 10 7"></polyline>
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                          </g>
                        </svg>
                      </span>
                    </span>
                  </div>
                </li>
              </ul>
              <ul class="rlr-booking-card__result-list">
                <li class="rlr-icon-text">
                  <i class="rlr-icon-font flaticon-email"> </i>
                  <div class="rlr-icon-text__text-wrapper">
                    <span class="description-url" onclick="window.location.href='mailto:<?= $business->email ?>'">
                      Email
                      <span class="rlr-svg-icon">
                        <svg width="16" height="16" viewBox="-1.44 -1.44 26.88 26.88" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="#000000" stroke-width="1.104" stroke-linecap="round" stroke-linejoin="miter">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_iconCarrier">
                            <polyline points="17 14 17 7 10 7"></polyline>
                            <line x1="7" y1="17" x2="17" y2="7"></line>
                          </g>
                        </svg>
                      </span>
                    </span>
                  </div>
                </li>
              </ul>
              <ul class="rlr-booking-card__result-list">
                <li class="rlr-icon-text">
                  <i class="rlr-icon-font flaticon-telephone"> </i>
                  <div class="rlr-icon-text__text-wrapper">
                    <span onclick="window.location.href='phone:<?= $business->telepon ?>'"><?= $business->telepon ?></span>
                  </div>
                </li>
              </ul>
            </fieldset>
          </form>
        </aside>
      </section>
      <? if (count($neighbours) > 1) : ?>
        <!-- Similar Products -->
        <section class="rlr-section rlr-section__mt rlr-related-product-wrapper">
          <!-- Section heading -->
          <div class="rlr-section-header">
            <!-- Section heading -->
            <div class="rlr-section__title">
              <h2 class="rlr-section__title--main">Lainnya di <?= $place->getKabupatenNameById($place->getPlaceById($business->idDesa)['id_kabupaten']) ?></h2>
              <span class="rlr-section__title--sub">Akomodasi, objek wisata, serta tempat makan dan minum lainnya yang dapat Anda
                kunjungi</span>
            </div>
            <div class="button-row">
              <a href="../search/?kabupaten=<?= $place->getPlaceById($business->idDesa)['id_kabupaten'] ?>" class="btn rlr-button rlr-button--large rlr-button--rounded rlr-button--brand"> Jelajahi </a>
            </div>
          </div>
          <div class="row rlr-featured__cards">

            <?
            $count = 0;
            foreach ($neighbours as $neighbour) :
              $count++;
              if ($neighbour['id_bisnis'] == $business->idBisnis) {
                continue;
              }
              $category = $business_service->getCategoryByBusinessId($neighbour['id_bisnis']);

              switch ($business_service->getCategoryByBusinessId($neighbour['id_bisnis'])['id_kategori']) {
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

              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-offset="250" data-aos-duration="700">
                <article class="rlr-product-card rlr-product-card--v3" itemscope itemtype="https://schema.org/Product">
                  <figure class="rlr-product-card__image-wrapper">
                    <span class="rlr-badge rlr-badge-- rlr-badge--accent-<?= $categoryBadgeAccent ?> rlr-product-card__badge"> <?= $category['nama'] ?> </span>
                    <div class="rlr-product-detail-header__button-wrapper">
                      <? if (isset($user['id_pengguna'])) : ?>
                        <button id="<?= $neighbour['id_bisnis'] ?>" type="button" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button--light rlr-wishlist-button rlr-js-action-wishlist <?= $wishlist->isWishlist($user['id_pengguna'], $neighbour['id_bisnis']) ? 'is-active' : '' ?>" aria-label="Save to Wishlist">
                        <? endif; ?>
                        <i class="rlr-icon-font flaticon-heart-1"> </i>
                        </button>
                        <span class="rlr-product-detail-header__helptext rlr-js-helptext"></span>
                    </div>
                    <a href="../listing/?id=<?= $neighbour['id_bisnis'] ?>">
                      <div class="swiper rlr-js-product-multi-image-swiper">
                        <div class="swiper-wrapper">
                          <? foreach ($photos->read($neighbour['id_bisnis']) as $photo) : ?>
                            <div class="swiper-slide">
                              <img itemprop="image" style="height: 200px; object-fit:cover" data-sizes="auto" data-src="../assets/images/listings/<?= $photo['filename'] ?>" data-srcset="../assets/images/listings/<?= $photo['filename'] ?>" class="lazyload" alt="product-image" />
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
                        <a href="../listing/?id=<?= $neighbour['id_bisnis'] ?>" class="rlr-product-card__anchor-title">
                          <h2 class="rlr-product-card__title" itemprop="name"><?= $business_service->getBusinessById($neighbour['id_bisnis'])->nama ?></h2>
                        </a>
                        <div>
                          <a href="../listing/?id=<?= $neighbour['id_bisnis'] ?>" class="rlr-product-card__anchor-cat">
                            <span class="rlr-product-card__sub-title"><?= $business_service->getBusinessById($neighbour['id_bisnis'])->alamat ?></span>
                          </a>
                        </div>
                      </div>
                    </header>
                    <!-- Product card body -->
                    <div class="rlr-product-card__details">
                      <div class="rlr-product-card__prices" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                        <span class="rlr-product-card__from"><?= $place->getKecamatanNameById($neighbour['id_kecamatan']) ?></span>
                        <div class="rlr-icon-text rlr-product-card__icon-text"><span class=""><?= $place->getKabupatenNameById($neighbour['id_kabupaten']) ?></span></div>
                      </div>
                      <div class="rlr-product-card__ratings" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                        <div class="rlr-review-stars" itemprop="ratingValue" itemscope itemtype="https://schema.org/Product">
                          <?
                          $stars = round($reviews->getAverageRatingById($neighbour['id_bisnis']));
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
                        <span class="rlr-product-card__rating-text" itemprop="reviewCount"><?= round($reviews->getAverageRatingById($neighbour['id_bisnis']), 1) ?> (<?= $reviews->getTotalReviewsById($neighbour['id_bisnis'])  ?>)</span>
                      </div>
                    </div>
                  </div>
                </article>
              </div>
            <?
              if ($count == 3) break;
            endforeach; ?>

          </div>
        </section>
      <? endif; ?>

      <? if (isset($user['level']) && $user['level'] === "bisnis" && $business_service->getBusinessByUserId($user['id_pengguna'])->idBisnis === $business->idBisnis) : ?>
        <!-- Reply Review Modal -->
        <div id="replyReviewModal" class="modal">
          <!-- Modal content -->
          <div class="modal-content">
            <div id="rlr-review-from" class="container-xxxl">
              <form action="replyReview/" method="POST" enctype="multipart/form-data">
                <section class="rlr-section rlr-section__content--md-top row justify-content-center my-0">
                  <div class="modal-header">
                    <div class="rlr-section__heading py-4 px-3">
                      <label class="rlr-form-label rlr-form-label--dark m-0" for="rlr_review_form_title"> Balas Ulasan
                      </label>
                    </div>
                  </div>
                  <div class="col-xl-12">
                    <fieldset class="rlr-product-form--show px-3">
                      <legend class="rlr-review-form__hidden-legend">Balas ulasan</legend>
                      <!-- Section heading -->
                      <div class="rlr-fieldrow__form-element">
                        <div class="rlr-fieldrow__item mt-2 mb-4">
                          <label class="rlr-form-label rlr-form-label--dark mb-3" for="rlr_review_form_title"> Judul balasan
                          </label> <input type="text" name="judul" autocomplete="off" maxlength="70" id="rlr_review_form_title" class="form-control" placeholder="Berikan judul untuk balasan ulasan">
                        </div>
                        <div class="rlr-fieldrow__item mt-2 mb-4">
                          <label class="rlr-form-label rlr-form-label--dark mb-3" for="rlr_review_form_desc"> Deskripsi balasan </label>
                          <textarea id="rlr_review_form_desc" name="komentar" class="form-control form-control--text-area" placeholder="Berikan balasan profesional mengenai bisnis Anda" rows="12"></textarea>
                        </div>
                      </div>
                  </div>
                  <input type="hidden" id="id_reply_ulasan" name="id_ulasan" value="">
                  <input type="hidden" id="id_bisnis" name="id_bisnis" value="">
                  </fieldset>
            </div>
            <div class="modal-footer d-flex justify-content-between">
              <div class="rlr-review-form__buttons mt-0 py-2 px-3" style="width: 100%">
                <button type="button" class="btn rlr-button rlr-review-form__cancel rlr-button--small rlr-button--rounded rlr-button--white mt-0" id="closeReplyReviewModalBtn">Batal</button>
                <button type="submit" class="btn rlr-button rlr-review-form__submit rlr-button--small rlr-button--rounded rlr-button--brand mt-0">Kirim</button>
              </div>
            </div>
            </section>
            </form>
          </div>
        </div>
    </div>
  <? endif ?>

  <? if (isset($user['level']) && $user['level'] === 'pengguna') : ?>
    <!-- Add Review Modal -->
    <div id="addReviewModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <div id="rlr-review-from" class="container-xxxl">
          <form action="addReview/" method="POST" enctype="multipart/form-data">
            <section class="rlr-section rlr-section__content--md-top row justify-content-center my-0">
              <div class="modal-header">
                <div class="rlr-section__heading py-4 px-3">
                  <label class="rlr-form-label rlr-form-label--dark m-0" for="rlr_review_form_title"> Tulis Ulasan
                  </label>
                </div>
              </div>
              <div class="col-xl-12">
                <fieldset class="rlr-product-form--show px-3">
                  <legend class="rlr-review-form__hidden-legend">Tulis ulasan</legend>
                  <!-- Section heading -->
                  <div class="rlr-fieldrow">
                    <div class="rlr-fieldrow_item my-2">
                      <div class='rating-stars text-center row'>
                        <ul id='stars'>
                          <li class='star' title='Poor' data-value='1'>
                            <i class='rlr-icon-font flaticon-star-1'></i>
                          </li>
                          <li class='star' title='Fair' data-value='2'>
                            <i class='rlr-icon-font flaticon-star-1'></i>
                          </li>
                          <li class='star' title='Good' data-value='3'>
                            <i class='rlr-icon-font flaticon-star-1'></i>
                          </li>
                          <li class='star' title='Excellent' data-value='4'>
                            <i class='rlr-icon-font flaticon-star-1'></i>
                          </li>
                          <li class='star' title='Perfect' data-value='5'>
                            <i class='rlr-icon-font flaticon-star-1'></i>
                          </li>
                        </ul>
                      </div>
                      <input type="hidden" name="rating" id="rating" value="1">
                      <input type="hidden" name="id_bisnis" value="<?= $business->idBisnis ?>">
                    </div>
                    <div class="rlr-fieldrow__form-element">
                      <div class="rlr-fieldrow__item mt-2 mb-4">
                        <label class="rlr-form-label rlr-form-label--dark mb-3" for="rlr_review_form_title"> Judul
                        </label> <input type="text" name="judul" autocomplete="off" maxlength="70" id="rlr_review_form_title" class="form-control" placeholder="Berikan judul menarik">
                      </div>
                      <div class="rlr-fieldrow__item mt-2 mb-4">
                        <label class="rlr-form-label rlr-form-label--dark mb-3" for="rlr_review_form_desc"> Ceritakan
                          pengalaman Anda </label>
                        <textarea id="rlr_review_form_desc" name="komentar" class="form-control form-control--text-area" placeholder="Jelaskan hal menarik dari kunjungan Anda" rows="12"></textarea>
                      </div>
                    </div>
                    <div class="rlr-fieldrow__item mt-2 mb-4" style="z-index: 200">
                      <label class="rlr-form-label rlr-form-label--dark mb-4" for="rlr_review_form_title"> Tambahkan
                        foto dari pengalaman Anda. </label>
                      <div class="upload-card">
                        <div class="drag-area">
                          <span class="visible">
                            Drag & drop gambar disini atau
                            <span class="select-file" role="button">Pilih File</span>
                          </span>
                          <span class="on-drop">Jatuhkan gambar disini</span>
                          <input name="file[]" type="file" class="file" multiple />
                        </div>

                        <!-- IMAGE PREVIEW CONTAINER -->
                        <div class="upload-container">

                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
              </div>
              <div class="modal-footer d-flex justify-content-between">
                <div class="rlr-review-form__buttons mt-0 py-2 px-3" style="width: 100%">
                  <button type="button" class="btn rlr-button rlr-review-form__cancel rlr-button--small rlr-button--rounded rlr-button--white mt-0" id="closeAddReviewModalBtn">Batal</button>
                  <button type="submit" class="btn rlr-button rlr-review-form__submit rlr-button--small rlr-button--rounded rlr-button--brand mt-0">Kirim</button>
                </div>
              </div>
            </section>
          </form>
        </div>
      </div>
    </div>
  <? endif; ?>

  <!-- Review Modal -->
  <div id="showReviewModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- Product cards -->
      <div class="modal-header d-flex justify-content-between align-items-center px-4 py-3">
        <div class="d-flex justify-content-start gap-2">
          <i class="rlr-icon-font flaticon-star-1 m-0" style="font-size: 1.5rem;"> </i>
          <h1 class="rlr-section__heading--main rlr-product-detail-header__title m-0" style="font-size: 1.5rem;"><?= round($reviews->getAverageRatingById($business->idBisnis), 1) ?> · <?= $reviews->getTotalReviewsById($business->idBisnis) ?> ulasan</h1>

        </div>
        <i class="rlr-icon-font flaticon-close m-0" style="font-size: 1rem;" id="closeShowReviewModalBtn"></i>
      </div>
      <div class="row p-4 m-0">
        <!-- Search header -->
        <div class="rlr-search-results-header rlr-search-results-header__wrapper border-0 p-0 d-xl-flex gap-3">
          <!-- Title -->
          <div class="rlr-input-group" aria-expanded="false">
            <input type="text" style="width: 100%;" autocomplete="off" class="form-control" id="searchReview" placeholder="Cari ulasan" required>
          </div>
          <!-- Sort order -->
          <div class="rlr-search-results-header__sorting-wrapper">
            <span class="rlr-search-results-header__label">Urut berdasarkan:</span>
            <div class="dropdown rlr-dropdown rlr-js-dropdown">
              <button class="btn dropdown-toggle rlr-dropdown__button rlr-js-dropdown-button" type="button" id="rlr_dropdown_menu_search_results" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="-20,25">Ulasan terbaru</button>
              <ul class="dropdown-menu rlr-dropdown__menu" aria-labelledby="rlr_dropdown_menu_search_results">
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item active" id="sort">Ulasan terbaru</a>
                </li>
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item" id="sort">Ulasan terlama</a>
                </li>
                <li>
                  <hr class="dropdown-divider rlr-dropdown__divider">
                </li>
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item" id="sort">Rating tertinggi</a>
                </li>
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item" id="sort">Rating terendah</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row m-0">
        <aside class="col-lg-12 col-xl-4 p-4">
          <label class="rlr-form-label rlr-form-label-- rlr-product-filters__label"> Rating </label>
          <ul class="rlr-checkboxes">
            <li class="form-check form-check-block">
              <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox rating-checkbox" id="rlr-filter-rating-5" value="5" type="checkbox" />
              <label aria-label="rating-5" for="rlr-filter-rating-5">
                <span class="rlr-product-filters__hidden">rating 5</span>
                <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i>
              </label>
            </li>
            <li class="form-check form-check-block">
              <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox rating-checkbox" id="rlr-filter-rating-4" value="4" type="checkbox" />
              <label aria-label="rating-4" for="rlr-filter-rating-4">
                <span class="rlr-product-filters__hidden">rating 4</span>
                <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star"> </i>
              </label>
            </li>
            <li class="form-check form-check-block">
              <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox rating-checkbox" id="rlr-filter-rating-3" value="3" type="checkbox" />
              <label aria-label="rating-3" for="rlr-filter-rating-3">
                <span class="rlr-product-filters__hidden">rating 3</span>
                <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i>
              </label>
            </li>
            <li class="form-check form-check-block">
              <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox rating-checkbox" id="rlr-filter-rating-2" value="2" type="checkbox" />
              <label aria-label="rating-2" for="rlr-filter-rating-2">
                <span class="rlr-product-filters__hidden">rating 2</span>
                <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i>
              </label>
            </li>
            <li class="form-check form-check-block">
              <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox rating-checkbox" id="rlr-filter-rating-1" value="1" type="checkbox" />
              <label aria-label="rating-1" for="rlr-filter-rating-1">
                <span class="rlr-product-filters__hidden">rating 1</span>
                <i class="rlr-icon-font flaticon-star-1"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i> <i class="rlr-icon-font flaticon-star"> </i>
              </label>
            </li>
          </ul>
        </aside>
        <div class="col-lg-12 col-xl-8 modal-review-list">
          <!-- Review -->
          <div class="review-wrapper pb-3" style="height: 500px; overflow-y: scroll;">
            <!-- Review section here -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Report Modal -->
  <div id="reportReviewModal" class="modal">
    <div class="modal-content">
      <div id="rlr-review-from" class="container-xxxl">
        <form action="reportReview/" method="POST">
          <section class="rlr-section rlr-section__content--md-top row justify-content-center my-0">
            <div class="modal-header">
              <div class="rlr-section__heading py-4 px-3">
                <label class="rlr-form-label rlr-form-label--dark m-0" for="rlr_review_form_title"> Laporkan ulasan
                </label>
              </div>
            </div>
            <div class="col-xl-12">
              <fieldset class="rlr-product-form--show px-3">
                <legend class="rlr-review-form__hidden-legend">Tulis ulasan</legend>
                <!-- Section heading -->
                <div class="rlr-fieldrow">

                  <div class="rlr-fieldrow__form-element">
                    <ul class="rlr-radios">
                      <li class="form-check">
                        <input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="profane_content_review" value="Ulasan tidak sopan, mengandung unsur pelecehan seksual atau ujaran kebencian." /> <label class="rlr-form-label rlr-form-label--radio" for="profane_content_review"> Ulasan tidak sopan,
                          mengandung unsur pelecehan seksual atau ujaran kebencian. </label>
                      </li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="ilegal_activity_review" value="Ulasan mempromosikan kegiatan ilegal" /> <label class="rlr-form-label rlr-form-label--radio" for="ilegal_activity_review"> Ulasan mempromosikan
                          kegiatan ilegal </label></li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="biased_review" value="Ulasan bias atau ditulis oleh orang yang berafiliasi dengan bisnis" /> <label class="rlr-form-label rlr-form-label--radio" for="biased_review"> Ulasan bias atau ditulis oleh orang
                          yang berafiliasi dengan bisnis </label></li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="wrong_business_review" value="Ulasan ditujukan untuk bisnis yang salah" /> <label class="rlr-form-label rlr-form-label--radio" for="wrong_business_review"> Ulasan ditujukan untuk
                          bisnis yang salah </label></li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="duplicate_review" value="Ulasan merupakan duplikat yang dibuat oleh orang yang sama" /> <label class="rlr-form-label rlr-form-label--radio" for="duplicate_review"> Ulasan merupakan duplikat yang
                          dibuat oleh orang yang sama </label></li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="not_personal_review" value="Ulasan tidak menggambarkan pengalaman pribadi" /> <label class="rlr-form-label rlr-form-label--radio" for="not_personal_review"> Ulasan tidak menggambarkan
                          pengalaman pribadi </label></li>
                      <li class="form-check"><input type="radio" required class="form-check-input rlr-form-check-input" name="report" id="other_review" value="Saya ingin melapor hal lain" /> <label class="rlr-form-label rlr-form-label--radio" for="other_review"> Saya ingin melapor hal lain </label>
                      </li>
                    </ul>
                  </div>
                  <input type="hidden" id="id_ulasan" name="id_ulasan" value="">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item mt-2 mb-4">
                      <label class="rlr-form-label rlr-form-label--dark mb-3" for="rlr_review_form_desc"> Jelaskan masalah Anda </label>
                      <textarea id="rlr_review_form_desc" name="description" class="form-control form-control--text-area" placeholder="Tolong berikan deskripsi rinci tentang masalah Anda" rows="12"></textarea>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="modal-footer d-flex justify-content-between">
              <div class="rlr-review-form__buttons mt-0 py-2 px-3" style="width: 100%">
                <button type="button" class="btn rlr-button rlr-review-form__cancel rlr-button--small rlr-button--rounded rlr-button--white mt-0" id="closeReportReviewModalBtn">Batal</button>
                <button type="submit" class="btn rlr-button rlr-review-form__submit rlr-button--small rlr-button--rounded rlr-button--brand mt-0">Kirim</button>
              </div>
            </div>
          </section>
        </form>
      </div>
    </div>
  </div>
  </div>
  </main>
  <!-- Footer -->
  <footer class="rlr-footer rlr-section rlr-section__mt">
    <div class="container">
      <!-- Footer menu -->
      <div class="rlr-footer__row justify-content-between">
        <nav class="rlr-footer__menu__col">
          <div class="navigation-brand-text">
            <div class="rlr-logo rlr-logo__navbar-brand rlr-logo--default mb-3">
              <a href="../">
                <img src="../assets/svg/logoipsum-287.svg" alt="#" class="" style="width: 200px;" />
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
              <?
              $count = 0;
              foreach ($place->getKabupatenByProvinsi('51') as $kabupaten) :
                $count++ ?>
                <li><a href="../search/?kabupaten=<?= $kabupaten['id_kabupaten'] ?>"><?= $kabupaten['nama'] ?></a></li>
              <?
                if ($count == 4) break;
              endforeach; ?>

              <? if (count($place->getKabupatenByProvinsi('51')) > 4) : ?>
                <li><a href="../search/?provinsi=51">Jelajahi <?= $place->getProvinsiNameById('51') ?></a></li>
              <? endif; ?>

            </ul>
          </nav>
          <nav class="rlr-footer__menu__col">
            <!-- Footer menu col -->
            <h4>Kategori</h4>
            <ul>
              <li><a href="../search/?kategori=1">Akomodasi</a></li>
              <li><a href="../search/?kategori=2">Makanan & Minuman</a></li>
              <li><a href="../search/?kategori=3">Objek Wisata</a></li>
            </ul>
          </nav>
          <nav class="rlr-footer__menu__col">
            <!-- Footer menu col -->
            <h4>Lainnya</h4>
            <ul>
              <li><a href="../blog/">Blog</a></li>
              <? if (isset($user['level']) && $user['level'] === 'admin') : ?>
                <li><a href="../dashboard/admin/">Dashboard Admin</a></li>
              <? elseif (isset($user['level']) && $user['level'] === 'bisnis') : ?>
                <li><a href="../dashboard/business/">Dashboard Bisnis</a></li>
              <? else : ?>
                <li><a href="../manage-listing/">Daftarkan bisnis</a></li>
              <? endif; ?>
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
  <script src="../vendors/jquery.min.js"></script>
  <script src="../vendors/navx/js/navigation.min.js" defer></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <script src="../js/old/main.js" defer></script>
  <script src="../js/listing.js"></script>
  <script src="../js/upload.js"></script>

  <script>
    <? if (isset($user['id_pengguna'])) : ?>
      $(document).on('click', '.rlr-js-action-wishlist', function() {
        var id = $(this).attr('id');
        var $this = $(this);

        $.ajax({
          url: '../wishlist/toggle_wishlist.php',
          type: 'POST',
          data: {
            id_bisnis: id,
            id_pengguna: <?= $user['id_pengguna'] ?>
          },
          error: function(response) {
            console.log(response);
          }
        });

      });
    <? endif; ?>

    let map = L.map('map');

    function loadMap(lat, lng) {
      map.setView([lat, lng], 13);
      map.invalidateSize(false);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
      }).addTo(map);

      marker = L.marker([lat, lng], {
        draggable: false
      }).addTo(map);
    }

    loadMap(<?= $business->lat ?>, <?= $business->lng ?>);
  </script>

  <script>
    $(document).ready(function() {

      param.business_id = <?= $_GET['id'] ?>

      getReviews(param);
    });
  </script>

</body>

</html>