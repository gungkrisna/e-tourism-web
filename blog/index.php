<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Article.php';
include '../src/Place.php';

session_start();

$pengguna = new Pengguna($conn);

$user = [];
if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
}

$place = new Place($conn);
$artikel = new Article($conn);

$keyword = (isset($_GET['keyword']) && !empty($_GET['keyword'])) ? $_GET['keyword'] : null;
$sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'DESC';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $params['keyword'] ?? 'Blog' ?> - E-Tourism</title>
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
    <div class="container">

      <aside class="row">
        <!-- Search results header -->
        <div class="rlr-search-results-header rlr-search-results-header__wrapper">
          <!-- Title -->
          <ol class="breadcrumb rlr-breadcrumb__items">
            <li class="breadcrumb-item rlr-breadcrumb__item"><a href="../">Home</a></li>
            <li class="breadcrumb-item rlr-breadcrumb__item"><a href="#">Blog</a></li>
          </ol>
          <!-- Sort order -->
          <div class="rlr-search-results-header__sorting-wrapper">
            <span class="rlr-search-results-header__label">Urutkan berdasarkan:</span>
            <div class="dropdown rlr-dropdown rlr-js-dropdown">
              <button class="btn dropdown-toggle rlr-dropdown__button rlr-js-dropdown-button" type="button" id="rlr_dropdown_menu_search_results" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="-50,35">Terbaru</button>
              <ul class="dropdown-menu rlr-dropdown__menu" aria-labelledby="rlr_dropdown_menu_search_results">
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item active" id="DESC">Terbaru</a>
                </li>
                <li>
                  <hr class="dropdown-divider rlr-dropdown__divider" />
                </li>
                <li>
                  <a class="dropdown-item rlr-dropdown__item rlr-js-dropdown-item" id="ASC">Terlama</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </aside>
      <div class="row rlr-search-results-page__product-details rlr-section__py">
        <aside class="col-xl-3 rlr-search-results-page__sidebar">
          <aside class="rlr-sidebar">
            <div class="rlr-sidebar__widget rlr-sidebar--search widget_search">
              <label for="wp-block-search__input-1" class="wp-block-search__label">Cari</label>
              <div class="wp-block-search__inside-wrapper"><input type="search" id="search" class="wp-block-search__input" name="s" value="" placeholder="" required="" /></div>
            </div>
          </aside>
        </aside>
        <section class="col-xl-9 rlr-search-results-page__product-list">
          <div class="row rlr-listings__header">
            <h1 class="rlr-section__heading--main">Blog</h1>
          </div>
          <div class="row rlr-search-results-page__card-wrapper" id="filteredBlog">
            <?
            $result_artikel = $artikel->readAll(null, null, $keyword, 'publik', 'id_artikel', $sort);
            if (!empty($result_artikel)) : ?>
              <? foreach ($result_artikel as $a) : ?>
                <div class="col-md-6 col-lg-4">
                  <article class="rlr-postcard p-0">
                    <img class="rlr-postcard__thumbnail" style="height: 200px; object-fit:cover" src="../assets/images/article/<?= $a['banner'] ?>" alt="blog image">
                    <div class="rlr-postcard__summary p-4">
                      <span class="rlr-postcard__author"><?= $pengguna->read($a['id_pengguna'])['nama'] ?> | <?= date("m/d/Y", strtotime($a['tanggal'])) ?></span>
                      <a href="../article/?id=<?= $a['id_artikel'] ?>" class="rlr-product-card__anchor-title">
                        <h2 class="rlr-product-card__title"><?= $a['judul'] ?></h2>
                      </a>
                      <?
                      $description = strip_tags(str_replace('<', ' <', $a['konten']));

                      if (strlen($description) > 140) {
                        $description = substr($description, 0, 140) . '...';
                      }
                      ?>
                      <p><?= $description ?></p>
                    </div>
                  </article>
                </div>
              <? endforeach; ?>
            <? else : ?>
              <p>Tidak ditemukan hasil</p>
            <? endif; ?>
          </div>
          <hr class="rlr-search-results-page__divider" />
        </section>
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
  <script src="../js/main.js" defer></script>
  <script src="../js/blog.js" defer></script>
</body>

</html>