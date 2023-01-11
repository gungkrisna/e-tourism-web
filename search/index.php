<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Business.php';
include '../src/BusinessService.php';
include '../src/BusinessPhoto.php';
include '../src/BusinessSearch.php';
include '../src/Review.php';
include '../src/Place.php';
include '../src/Wishlist.php';

session_start();

$user = [];
$pengguna = new Pengguna($conn);
if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
}

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$reviews = new Review($conn);
$place = new Place($conn);

$search = new BusinessSearch($conn);
$wishlist = new Wishlist($conn);

$params = [];

$params['query'] = (isset($_GET['query']) && !empty($_GET['query'])) ? $_GET['query'] : null;
$params['rating'] = (isset($_GET['rating']) && !empty($_GET['rating'])) ? $_GET['rating'] : null;
$params['kategori'] = (isset($_GET['kategori']) && !empty($_GET['kategori'])) ? $_GET['kategori'] : null;
$params['sort'] = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : null;
$params['desa'] = (isset($_GET['desa']) && !empty($_GET['desa'])) ? $_GET['desa'] : null;
$params['desa'] = (isset($_GET['desa']) && !empty($_GET['desa'])) ? $_GET['desa'] : null;
$params['kecamatan'] = (isset($_GET['kecamatan']) && !empty($_GET['kecamatan'])) ? $_GET['kecamatan'] : null;
$params['kabupaten'] = (isset($_GET['kabupaten']) && !empty($_GET['kabupaten'])) ? $_GET['kabupaten'] : null;
$params['provinsi'] = (isset($_GET['provinsi']) && !empty($_GET['provinsi'])) ? $_GET['provinsi'] : null;


$results = $search->search($params);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $params['query'] ?? 'Jelajah Destinasi' ?> - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="../styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <!-- JQuery -->
  <script src="../vendors/jquery.min.js"></script>
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
            <a class="navigation-link" href="#">
              <?= isset($_SESSION['user_id']) ? $user['nama'] : 'Guest' ?>
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
          <? endif; ?></a>

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
    <div class="rlr-search-results-page container">
      <aside class="row">
        <!-- Search results header -->
        <div class="rlr-search-results-header rlr-search-results-header__wrapper">
          <!-- Title -->
          <ol class="breadcrumb rlr-breadcrumb__items">
            <li class="breadcrumb-item rlr-breadcrumb__item"><a href="../">Home</a></li>
            <li class="breadcrumb-item rlr-breadcrumb__item"><a href="#">Search</a></li>
          </ol>
          <!-- Sort order -->
          <div class="rlr-search-results-header__sorting-wrapper">
            <span class="rlr-search-results-header__label">Urutkan berdasarkan:</span>
            <div class="dropdown rlr-dropdown rlr-js-dropdown">
              <button class="btn dropdown-toggle rlr-dropdown__button rlr-js-dropdown-button" type="button" id="rlr_dropdown_menu_search_results" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="-50,35">Terbaru</button>
              <ul class="dropdown-menu rlr-dropdown__menu" aria-labelledby="rlr_dropdown_menu_search_results">
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item active" id="latest">Terbaru</a>
                </li>
                <li>
                  <hr class="dropdown-divider rlr-dropdown__divider" />
                </li>
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item" id="most-reviewed">Ulasan</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </aside>
      <!-- Product cards -->
      <div class="row rlr-search-results-page__product-details">
        <aside class="col-xl-3 rlr-search-results-page__sidebar">
          <div class="rlr-product-filters__filters-wrapper">
            <div class="rlr-sidebar__widget rlr-sidebar--search widget_search mb-2">
              <label for="wp-block-search__input-1" class="wp-block-search__label">Cari</label>
              <div class="wp-block-search__inside-wrapper"><input type="search" id="search" class="wp-block-search__input" name="s" value="" placeholder="" required="" /></div>
            </div>
            <!--  Rating filter -->
            <div class="rlr-product-filters__filter">
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
            </div>
            <!-- Category filter -->
            <div class="rlr-product-filters__filter">
              <label class="rlr-form-label rlr-form-label-- rlr-product-filters__label"> Kategori </label>
              <ul class="rlr-checkboxes">
                <? foreach ($business_service->getAllBusinessCategory() as $c) : ?>
                  <li class="form-check form-check-block">
                    <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox kategori-checkbox" value="<?= $c['id_kategori'] ?>" id="kategori-<?= $c['id_kategori'] ?>" type="checkbox" />
                    <label class="rlr-form-label rlr-form-label--checkbox rlr-product-filters__checkbox-label" for="kategori-<?= $c['id_kategori'] ?>"> <?= $c['nama'] ?> </label>
                  </li>
                <? endforeach; ?>
              </ul>
            </div>
          </div>
        </aside>
        <div class="rlr-search-results-page__product-list col-lg-9">
          <div class="row rlr-search-results-page__card-wrapper" id="filteredSearch">
            <? if (!empty($results)) : ?>
              <?
              foreach ($results as $business) :
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
                <div class="col-md-6 col-lg-4">
                  <!-- Product card item -->
                  <article class="rlr-product-card rlr-product-card--v3" itemscope itemtype="https://schema.org/Product">
                    <figure class="rlr-product-card__image-wrapper">
                      <span class="rlr-badge rlr-badge-- rlr-badge--accent-<?= $categoryBadgeAccent ?> rlr-product-card__badge"> <?= $category['nama'] ?> </span>
                      <div class="rlr-product-detail-header__button-wrapper">
                        <? if (isset($user['id_pengguna'])) : ?>
                          <button id="<?= $business['id_bisnis'] ?>" type="button" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button--light rlr-wishlist-button rlr-js-action-wishlist <?= $wishlist->isWishlist($user['id_pengguna'], $business['id_bisnis']) ? 'is-active' : '' ?>" aria-label="Save to Wishlist">
                          <? endif; ?>
                          <i class="rlr-icon-font flaticon-heart-1"> </i>
                          </button>
                          <span class="rlr-product-detail-header__helptext rlr-js-helptext"></span>
                      </div>
                      <a href="../listing/?id=<?= $business['id_bisnis'] ?>">
                        <div class="swiper rlr-js-product-multi-image-swiper">
                          <div class="swiper-wrapper">
                            <? foreach ($photos->read($business['id_bisnis']) as $photo) : ?>
                              <div class="swiper-slide">
                                <img style="height: 200px; object-fit:cover" itemprop="image" data-sizes="auto" data-src="../assets/images/listings/<?= $photo['filename'] ?>" data-srcset="../assets/images/listings/<?= $photo['filename'] ?>" class="lazyload" alt="product-image" />
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
                          <a href="../listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-title">
                            <h2 class="rlr-product-card__title" itemprop="name"><?= $business['nama'] ?></h2>
                          </a>
                          <div>
                            <a href="../listing/?id=<?= $business['id_bisnis'] ?>" class="rlr-product-card__anchor-cat">
                              <span class="rlr-product-card__sub-title"><?= $business['alamat'] ?></span>
                            </a>
                          </div>
                        </div>
                      </header>
                      <!-- Product card body -->
                      <div class="rlr-product-card__details">
                        <div class="rlr-product-card__prices" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                          <span class="rlr-product-card__from"><?= $business['kecamatan'] ?></span>
                          <div class="rlr-icon-text rlr-product-card__icon-text"><span class=""><?= $business['kabupaten'] ?></span></div>
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
              endforeach; ?>
            <? else : ?>
              <p>Tidak ditemukan hasil</p>
            <? endif; ?>
          </div>
          <hr class="rlr-search-results-page__divider" />
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
  <script src="../vendors/navx/js/navigation.min.js" defer></script>
  <script src="../js/old/main.js" defer></script>
  <script src="../js/search.js"></script>
  <script>
    <? if (isset($user['id_pengguna'])) : ?>
      $(document).on('click', '.rlr-js-action-wishlist', function() {
        var id = $(this).attr('id');
        var $this = $(this); // store current element in variable

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


    var ratings = '<?= $params['rating']; ?>'.split(',');
    if (ratings.length > 0 && ratings.includes('5') && ratings.includes('4') && ratings.includes('3') && ratings.includes('2') && ratings.includes('1')) {
      $('.rating-checkbox').prop('checked', true);
    } else {
      $('.rating-checkbox').each(function() {
        if (ratings.includes($(this).val())) {
          $(this).prop('checked', true);
        }
      });
    }

    var kategoriList = '<?= $params['kategori']; ?>'.split(',');
    if (kategoriList.length > 0 && kategoriList.includes('1') && kategoriList.includes('2') && kategoriList.includes('3')) {
      $('.kategori-checkbox').prop('checked', true);
    } else {
      $('.kategori-checkbox').each(function() {
        if (kategoriList.includes($(this).val())) {
          $(this).prop('checked', true);
        }
      });
    }
  </script>
</body>

</html>