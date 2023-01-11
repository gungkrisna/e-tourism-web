<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Article.php';
include '../src/Place.php';

session_start();

$place = new Place($conn);
$pengguna = new Pengguna($conn);
$user = [];

if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
  $place = new Place($conn);
}

if (isset($_GET['id'])) {
  $artikel = new Article($conn);
  $read = $artikel->read($_GET['id'])[0];

  if (!$read) {
    header('HTTP/1.1 404 Not Found');
    include '../404.html'; //need to fix
    exit();
  }

  if ($read['status'] !== 'publik' && $read['id_pengguna'] != $user['id_pengguna']) {
    header('HTTP/1.1 404 Not Found');
    include '../404.html'; //need to fix
    exit();
  }

  if ($read['status'] !== 'publik' && $read['id_pengguna'] == $user['id_pengguna']) {
    echo "<script>console.log('Draft')</script>";
  }
} else {
  header('HTTP/1.1 404 Not Found');
  include '../404.html'; //need to fix
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Your vacation, tours and travel theme needs are all met at E-Tourism." />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $read['judul'] ?> - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="../styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
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
          <? endif; ?> </a>

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
    <!-- Blog Element -->
    <div class="container-xxl">
      <article class="rlr-article rlr-article-single--v2">
        <header class="rlr-article__header">
          <div class="rlr-article__header__timestamp">Diterbitkan <?= date("m/d/Y", strtotime($read['tanggal'])) ?> oleh <?= $pengguna->read($read['id_pengguna'])['nama'] ?> • <? $konten = strip_tags(str_replace('<', ' <', $read['konten']));
                                                                                                                                                                                $image = 3; ?> <?= $artikel->getReadTimeInMinutes(strlen($konten), $image) ?> mins read</div>
          <h1 class="type-h1"><?= $read['judul'] ?></h1>
          <h6 class="type-sub-title"><?= $read['subjudul'] ?></h6>
        </header>
        <div class="rlr-article__featured-photo">
          <img src="../assets/images/article/<?= $read['banner'] ?>" alt="Featured Photo" />
        </div>
        <div class="rlr-article__wrapper">
          <div class="content" style="overflow-wrap: break-word;">
            <?= str_replace("../../../", "../", $read['konten']) ?>
          </div>
        </div>
      </article>
      <!-- Recent blog -->
      <section class="rlr-section rlr-section__mt rlr-related-product-wrapper">
        <div class="rlr-section-header pb-5">
          <!-- Section heading -->
          <div class="rlr-section__title">
            <h2 class="rlr-section__title--main">Blog terbaru</h2>
          </div>
          <div class="button-row">
            <a href="../blog" class="btn rlr-button rlr-button--large rlr-button--rounded rlr-button--brand"> Selengkapnya </a>
          </div>
        </div>
        <div class="row rlr-search-results-page__card-wrapper">
          <? foreach ($artikel->readAll(0, 3, 'a', 'publik') as $a) : ?>
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
        </div>
      </section>
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
              <? if (isset($user)) : ?>
                <? if ($user['level'] === 'admin') : ?>
                  <li><a href="./dashboard/admin/">Dashboard Admin</a></li>
                <? elseif ($user['level'] === 'bisnis') : ?>
                  <li><a href="./dashboard/business">Dashboard Bisnis</a></li>
                  <? endif; ?>
                  <? else : ?>
                  <li><a href="./manage-listing/">Daftarkan bisnis</a></li>
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
</body>

</html>