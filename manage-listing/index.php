<?php
include '../src/conn.php';
include '../src/Business.php';
include '../src/BusinessService.php';
include '../src/BusinessPhoto.php';
include '../src/Service.php';
include '../src/Review.php';
include '../src/Pengguna.php';
include '../src/ReviewPhoto.php';
include '../src/FAQ.php';
include '../src/Place.php';

session_start();

$pengguna = new Pengguna($conn);
if (isset($_SESSION['user_id'])) {
  $user = $pengguna->read($_SESSION['user_id']);
} else {
  header('Location: ../login');
}

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$faqs = new FAQ($conn);
$place = new Place($conn);

if ($user['level'] === 'admin') {
  header('Location: ../');
}

if ($user['level'] === 'bisnis') {
  $isEdit = true;
  $business = $business_service->getBusinessByUserId($user['id_pengguna']);
  $services = new Service($conn);
  $availableService = $services->readAvailable($business->idBisnis);
  $unavailableService = $services->readUnavailable($business->idBisnis);
  $businessFAQ = $faqs->read($business->idBisnis);
} else {
  $isEdit = false;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $isEdit ? 'Edit' : 'Buat' ?> Listing - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="../styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />

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
                $name_parts = explode(" ",  $user['nama']);
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
          </a>

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
    <div class="rlr-section rlr-section__content--lg-top rlr-product-form">
      <div class="container">
        <div class="row">
          <aside class="col-xl-3">
            <div class="rlr-progress">
              <ul class="rlr-progress__steps js-progress-bar-slider" role="tablist">
                <li class="rlr-step js-step-1">
                  <div class="rlr-step__bullet rlr-step__bullet--active rlr-step--isFirst js-bullet"></div>
                  <div class="rlr-step__icon rlr-step__icon--active">
                    <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="#28B0A6"></rect>
                      <path d="M38.653 13h-1.607v1.492a.56.56 0 0 1-.558.56.557.557 0 0 1-.558-.56V13H19.343v1.492a.56.56 0 0 1-.558.56.557.557 0 0 1-.558-.56V13H16.62a2.63 2.63 0 0 0-1.857.782 2.647 2.647 0 0 0-.763 1.87v24.724a2.626 2.626 0 0 0 .766 1.859A2.612 2.612 0 0 0 16.62 43h22.033a2.605 2.605 0 0 0 2.422-1.618c.131-.319.198-.66.198-1.005V15.651a2.647 2.647 0 0 0-.764-1.87A2.63 2.63 0 0 0 38.654 13zM23.705 35.085l-3.503 3.36a.557.557 0 0 1-.78-.009l-1.905-1.911a.56.56 0 0 1 .608-.913c.068.028.13.069.181.12l1.52 1.525 3.108-2.982a.557.557 0 0 1 .944.418.561.561 0 0 1-.173.392zm0-8-3.503 3.36a.557.557 0 0 1-.78-.009l-1.905-1.911a.561.561 0 0 1 .395-.956c.148 0 .29.059.394.164l1.52 1.524 3.108-2.982a.557.557 0 0 1 .944.418.561.561 0 0 1-.173.392zm0-8-3.503 3.36a.557.557 0 0 1-.78-.009l-1.905-1.911a.56.56 0 0 1 .608-.913c.068.028.13.069.181.12l1.52 1.525 3.108-2.982a.557.557 0 0 1 .944.418.561.561 0 0 1-.173.392zM37.362 36.92H28.19a.557.557 0 0 1-.558-.56.56.56 0 0 1 .558-.56h9.17a.558.558 0 0 1 .559.56.561.561 0 0 1-.559.56zm0-8H28.19a.557.557 0 0 1-.558-.56.56.56 0 0 1 .558-.56h9.17a.558.558 0 0 1 .559.56.561.561 0 0 1-.559.56zm0-8H28.19a.557.557 0 0 1-.558-.56.56.56 0 0 1 .558-.56h9.17a.558.558 0 0 1 .559.56.561.561 0 0 1-.559.56z" fill="#fff"></path>
                    </svg>
                  </div>
                  <div class="rlr-step__text d-none d-lg-block">
                    <span class="rlr-step__text--active type-sub-title"> Informasi Bisnis </span>
                  </div>
                </li>
                <li class="rlr-step js-step-2">
                  <div class="rlr-step__bullet rlr-step__bullet--inactive rlr-step--isFirst js-bullet"></div>
                  <div class="rlr-step__icon rlr-step__icon--inactive">
                    <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="#546179"></rect>
                      <path d="M28.716 36.876h-5.882a.98.98 0 0 0 0 1.96h5.882a2.941 2.941 0 0 0 0-5.881h-1.96a.98.98 0 0 1 0-1.96h5.88a.98.98 0 0 0 0-1.961h-5.88a2.941 2.941 0 0 0 0 5.881h1.96a.98.98 0 0 1 0 1.96zM36.558 27.318a1.49 1.49 0 0 0-2.98 0c0 .824 1.49 2.696 1.49 2.696s1.49-1.872 1.49-2.696zM22.423 34.454a1.49 1.49 0 0 0-2.98 0c0 .824 1.49 2.686 1.49 2.686s1.49-1.862 1.49-2.686z" fill="#fff"></path>
                      <path d="M28 7.886C16.898 7.9 7.902 16.894 7.89 27.995c1.105 26.677 39.118 26.67 40.218 0C48.096 16.895 39.1 7.899 28 7.886zM40.742 38.22a2.937 2.937 0 0 1-2.933 2.94H18.197a2.937 2.937 0 0 1-2.941-2.932V23.505h25.487v14.714zm0-16.68H15.256v-1.911a2.95 2.95 0 0 1 2.94-2.94h2.942v-.981a.98.98 0 0 1 1.96 0v.98h3.921v-.98a.98.98 0 0 1 1.96 0v.98h3.922v-.98a.98.98 0 0 1 1.96 0v.98h2.941a2.95 2.95 0 0 1 2.941 2.941v1.912z" fill="#fff"></path>
                    </svg>
                  </div>
                  <div class="rlr-step__text d-none d-lg-block">
                    <span class="rlr-step__text--inactive type-sub-title"> Lokasi </span>
                  </div>
                </li>
                <li class="rlr-step js-step-3">
                  <div class="rlr-step__bullet rlr-step__bullet--inactive rlr-step--isFirst js-bullet"></div>
                  <div class="rlr-step__icon rlr-step__icon--inactive">
                    <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="#546179" />
                      <path d="M28 47a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5zM28 12c-5.376 0-9.75 4.374-9.75 9.75v.938h7.5v-.938c0-1.24 1.01-2.25 2.25-2.25s2.25 1.01 2.25 2.25a2.25 2.25 0 0 1-.796 1.717L24.25 27.89v6.734h7.5v-3.266l2.561-2.177a9.737 9.737 0 0 0 3.439-7.432c0-5.376-4.374-9.75-9.75-9.75z" fill="#fff" />
                    </svg>
                  </div>
                  <div class="rlr-step__text d-none d-lg-block">
                    <span class="rlr-step__text--inactive type-sub-title"> Ketentuan Layanan & FAQ&#x27;s </span>
                  </div>
                </li>
                <li class="rlr-step js-step-4">
                  <div class="rlr-step__bullet rlr-step__bullet--inactive rlr-step--isFirst js-bullet"></div>
                  <div class="rlr-step__icon rlr-step__icon--inactive">
                    <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="#546179" />
                      <path d="M24.253 39.954a1.63 1.63 0 0 1-2.31 0l-9.225-9.226a2.45 2.45 0 0 1 0-3.466l1.155-1.155a2.45 2.45 0 0 1 3.466 0l5.76 5.76L38.66 16.303a2.45 2.45 0 0 1 3.466 0l1.155 1.156a2.45 2.45 0 0 1 0 3.465l-19.029 19.03z" fill="#fff" />
                    </svg>
                  </div>
                  <div class="rlr-step__text d-none d-lg-block">
                    <span class="rlr-step__text--inactive type-sub-title"> Pratinjau &amp; Publikasikan </span>
                  </div>
                </li>
              </ul>
            </div>
          </aside>
          <div class="col-xl-6 offset-xl-1">
            <form action="publish/" method="POST" id="jsForm" enctype="multipart/form-data">
              <fieldset class="rlr-product-form--hide start" data-attr="js-step-1">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <!-- <? if ($isEdit && $business_service->readRejectedBusinessListing($business->idBisnis)) : ?>
                  <div class="pending-message-wrapper mb-5 px-4 py-3" style="width: 100%; border-radius: 12px; border-color: #FF9700; background-color: #FF9700; color: #ffffff;">
                    <h3 class="rlr-section__heading--main mb-2">Bisnis ditangguhkan</h3>
                    <h5 class="rlr-section__heading--sub"><?= $business_service->readRejectedBusinessListing($business->idBisnis)[0]['alasan'] ?></h5>
                  </div>
                  <? endif; ?>  -->
                  <? if (isset($business)) {
                    $admin_message = $business_service->readRejectedBusinessListing($business->idBisnis);
                  }
                  if ($isEdit && $business->status !== 'disetujui') : ?>
                    <div class="pending-message-wrapper mb-5 px-4 py-3" style="width: 100%; border-radius: 12px; border-color: #FF9700; background-color: #FF9700; color: #ffffff;">
                      <h3 class="rlr-section__heading--main mb-2"><?= $business->status == 'pending' ? 'Bisnis belum aktif' : 'Bisnis ditolak' ?></h3>
                      <h5 class="rlr-section__heading--sub"><?= $business->status == 'pending' ? 'Bisnis Anda sedang menunggu persetujuan Admin.' : 'Bisnis ditolak.' ?> <?= $admin_message ? ' Alasan: ' . $admin_message[0]['alasan'] : '' ?></h5>
                    </div>
                  <? endif; ?>

                  <h2 class="rlr-section__heading--main">Tambahkan judul, deskripsi, dan pilih kategori bisnis</h2>
                  <span class="rlr-section__heading--sub">Nama bisnis, deskripsi, dan kategori yang sesuai akan membantu
                    bisnis Anda untuk dikenal oleh calon pelanggan pada situs listing kami.</span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <div class="row">
                        <div class="col-xl-10">
                          <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title"> Nama
                            Bisnis </label>
                          <input <?= $isEdit ? 'value="' . $business->nama . '"' : '' ?> type="text" name="nama" autocomplete="off" maxlength="70" id="rlr-product-form-product-title" class="form-control js-form-title" placeholder="Masukkan nama bisnis disini" />
                        </div>
                        <div class="col-xl-2">
                          <button type="button" class="btn rlr-button btn rlr-button rlr-button--form-tooltip rlr-button--transparent rlr-js-tool-tip" data-tippy-content='&lt;span class&#x3D;"type-lead-semibold"&gt;Nama Bisnis&lt;/span&gt; &lt;p&gt;Pastikan nama yang Anda masukkan menggambarkan bisnis Anda dengan tepat agar mudah ditemukan oleh calon pelanggan.&lt;/p&gt;'>
                            <i class="rlr-icon-font flaticon-information-button"> </i>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="rlr-fieldrow__item js-fieldrow__item rlr-fieldrow__item--multiple has-success mt-5">
                      <div class="row">
                        <div class="col-xl-10">
                          <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-overview"> Deskripsi </label>
                          <textarea required="" id="deskripsi" name="deskripsi" class="form-control form-control--text-area" placeholder="Jelaskan tentang bisnis Anda" rows="12"><?= $isEdit ? $business->deskripsi : '' ?></textarea>
                        </div>
                        <div class="col-xl-2">
                          <button type="button" class="btn rlr-button btn rlr-button rlr-button--form-tooltip rlr-button--transparent rlr-js-tool-tip" data-tippy-content="<span class=&quot;type-lead-semibold&quot;>Deskripsi</span> <p>Masukkan deskripsi singkat tentang bisnis Anda. Deskripsi akan ditampilkan pada halaman bisnis untuk membantu calon pelanggan memahami keunikan bisnis Anda.</p>">
                            <i class="rlr-icon-font flaticon-information-button"> </i>
                          </button>
                        </div>
                      </div>
                      <div class="rlr-error text-help" style="display: none;"></div>
                    </div>
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <div class="row">
                        <div class="col-xl-10">
                          <label class="rlr-form-label rlr-form-label--dark"> Kategori Bisnis </label>
                          <ul class="rlr-radios">
                            <? foreach ($business_service->getAllBusinessCategory() as $c) : ?>
                              <li class="form-check">
                                <input type="radio" required class="form-check-input rlr-form-check-input" name="kategori" id="kategori-<?= $c['id_kategori'] ?>" value="<?= $c['id_kategori'] ?>" <?= $isEdit && $c['id_kategori'] === $business_service->getCategoryByBusinessId($business->idBisnis)['id_kategori'] ? 'checked' : '' ?> /> <label class="rlr-form-label rlr-form-label--radio" for="kategori-<?= $c['id_kategori'] ?>"> <?= $c['nama'] ?> </label>
                              </li>
                            <? endforeach; ?>
                          </ul>
                        </div>
                        <div class="col-xl-2">
                          <button type="button" class="btn rlr-button btn rlr-button rlr-button--form-tooltip rlr-button--transparent rlr-js-tool-tip" data-tippy-content='&lt;span class&#x3D;"type-lead-semibold"&gt;Kategori Bisnis&lt;/span&gt; &lt;p&gt;Pilih kategori yang sesuai untuk mengelompokkan bisnis Anda di situs listing kami.&lt;/p&gt;'>
                            <i class="rlr-icon-font flaticon-information-button"> </i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide rlr-js-product-form-fieldset" data-attr="js-step-1">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Tambahkan detail kontak</h2>
                  <span class="rlr-section__heading--sub">Tambahkan informasi kontak untuk memudahkan pengguna lain
                    menghubungi bisnis Anda.</span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <div class="row">
                        <div class="col-xl-12">
                          <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                            Telepon </label>
                          <input <?= $isEdit ? 'value="' . $business->telepon . '"' : '' ?> type="tel" name="telepon" autocomplete="off" maxlength="70" id="rlr-product-form-product-title" class="form-control js-form-title" placeholder="+62 361-5555-5555" required />
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <div class="row">
                        <div class="col-xl-12">
                          <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                            E-mail </label>
                          <input <?= $isEdit ? 'value="' . $business->email . '"' : '' ?> type="email" name="email" autocomplete="off" maxlength="70" id="rlr-product-form-product-title" class="form-control js-form-title" placeholder="hi@e-tourism.com" required />
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <div class="row">
                        <div class="col-xl-12">
                          <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                            Website (opsional) </label>
                          <input <?= $isEdit && isset($business->website) ? 'value="' . $business->website . '"' : '' ?> type="url" name="website" autocomplete="off" maxlength="70" id="rlr-product-form-product-title" class="form-control js-form-title" placeholder="e-tourism.com" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide stop" data-attr="js-step-1">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Unggah foto bisnis</h2>
                  <span class="rlr-section__heading--sub">Foto yang tempat menggambarkan bisnis Anda akan membantu calon
                    pelanggan mengenali bisnis Anda dengan baik.</span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--" for="rlr_input_splide_photouploader"> Foto Bisnis
                      </label>
                      <div class="upload-card">
                        <div class="drag-area">
                          <span class="visible">
                            Drag & drop gambar disini atau
                            <span class="select-file" role="button">Pilih File</span>
                          </span>
                          <span class="on-drop">Jatuhkan gambar disini</span>
                          <? if ($isEdit) {
                            $result = $photos->read($business->idBisnis);
                            if (count($result) > 0) {
                              $files = array();

                              foreach ($result as $row) {
                                $id = $row['id_foto_bisnis'];
                                $filename = $row['filename'];
                                $filepath = '../assets/images/listings/' . $filename;
                                $filetype = mime_content_type($filepath);
                                $files[] = (object) array(
                                  'id' => $id,
                                  'name' => $filename,
                                  'type' => $filetype,
                                  'size' => filesize($filepath),
                                  'content' => base64_encode(file_get_contents($filepath))
                                );
                              }
                              // Encode the files array as JSON
                              $json_files = json_encode($files);
                              echo "<script>console.log($json_files)</script>";
                            }
                          } ?>

                          <input name="file[]" type="file" class="file" <?= $isEdit ? "data-files='" . $json_files . "'" : '' ?> multiple />
                        </div>

                        <!-- IMAGE PREVIEW CONTAINER -->
                        <div class="upload-container">

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide start stop" data-attr="js-step-2">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Lokasi</h2>
                  <span class="rlr-section__heading--sub">Alamat bisnis dengan titik koordinat yang tepat akan membantu
                    pengguna menemukan bisnis Anda melalui peta pada situs listing kami.</span>
                </div>
                <div class="rlr-fieldrow__item js-fieldrow__item mt-4">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Provinsi
                  </label>
                  <select required id="provinsi" name="provinsi" class="form-select rlr-form-select">
                    <option value="" disabled="disabled" selected="selected">Pilih Provinsi</option>
                  </select>
                </div>

                <div class="rlr-fieldrow__item js-fieldrow__item mt-4">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Kabupaten
                  </label>
                  <select required id="kabupaten" name="kabupaten" class="form-select rlr-form-select">
                    <option value="" disabled="disabled" selected="selected">Pilih Kabupaten</option>
                  </select>
                </div>

                <div class="rlr-fieldrow__item js-fieldrow__item mt-4">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Kecamatan </label>
                  <select required id="kecamatan" name="kecamatan" class="form-select rlr-form-select">
                    <option value="" disabled="disabled" selected="selected">Pilih Kecamatan</option>
                  </select>
                </div>

                <div class="rlr-fieldrow__item js-fieldrow__item mt-4">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Desa </label>
                  <select required id="desa" name="desa" class="form-select rlr-form-select">
                    <option value="" disabled="disabled" selected="selected">Pilih Desa</option>
                  </select>
                </div>

                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark"> Masukkan
                        Alamat </label>
                      <input <?= $isEdit ? 'value="' . $business->alamat . '"' : '' ?> type="text" autocomplete="off" required id="alamat" name="alamat" class="form-control" placeholder="Jl. Uluwatu No 66X" />
                      <input type="hidden" id="lat" name="lat" value="" ?>
                      <input type="hidden" id="lng" name="lng" value="" ?>
                      <div class="rlr-fieldrow__map" id="map">
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide start" data-attr="js-step-3">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Tambah ketentuan layanan yang disediakan</h2>
                  <span class="rlr-section__heading--sub">Tambahkan ketentuan layanan yang lengkap dan jelas ke halaman
                    bisnis Anda. Ketentuan layanan mencakup informasi tentang apa yang diperbolehkan dan tidak
                    diperbolehkan oleh bisnis, serta bagaimana pelanggan harus bertindak saat menggunakan layanan bisnis
                    Anda.</span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple">
                      <div class="rlr-fieldrow__options js-fieldrow__item">
                        <label class="rlr-form-label rlr-form-label--dark"> Masukkan layanan yang disediakan </label>
                        <input value="<?= $availableService[0]['layanan'] ?? null ?>" required type="text" autocomplete="off" maxlength="70" class="form-control" name="layanan-disediakan[]" placeholder="Deskripsi singkat mengenai layanan yang disediakan" />
                      </div>
                    </div>
                    <button id="addAvailableService" type="button" class="btn rlr-button text-button rlr-button--product-form-repeater js-repeater rlr-button--small rlr-button--rounded rlr-button--gray rlr-button--transparent">+
                      Tambah layanan yang disediakan</button>

                    <?
                    if ($isEdit) {
                      $counter = 0;
                      foreach ($availableService as $available) :
                        if ($counter > 0) {
                    ?>
                          <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple rlr-fieldrow__clone--expand">
                            <div class="rlr-fieldrow__options js-fieldrow__item">
                              <label class="rlr-form-label rlr-form-label--dark"> Masukkan layanan yang disediakan </label>
                              <input required value="<?= $available['layanan'] ?>" type="text" autocomplete="off" maxlength="70" class="form-control" name="layanan-disediakan[]" placeholder="Deskripsi singkat mengenai layanan yang disediakan" />
                            </div>
                            <button class="btn rlr-button text-button rlr-button--product-form-repeater rlr-button--small rlr-button--rounded rlr-button__color--delete rlr-button--transparent delete-dbField">- delete</button>
                          </div>
                    <?
                        }
                        $counter++;
                      endforeach;
                    } ?>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide" data-attr="js-step-3">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Tambah ketentuan layanan yang tidak disediakan</h2>
                  <span class="rlr-section__heading--sub">Tambahkan ketentuan layanan yang lengkap dan jelas ke halaman
                    bisnis Anda. Ketentuan layanan mencakup informasi tentang apa yang diperbolehkan dan tidak
                    diperbolehkan oleh bisnis, serta bagaimana pelanggan harus bertindak saat menggunakan layanan bisnis
                    Anda.</span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">

                    <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple">
                      <div class="rlr-fieldrow__options js-fieldrow__item">
                        <label class="rlr-form-label rlr-form-label--dark"> Masukkan layanan yang tidak disediakan </label>
                        <input value="<?= $unavailableService[0]['layanan'] ?? null ?>" required type="text" autocomplete="off" maxlength="70" class="form-control" name="layanan-tidak-disediakan[]" placeholder="Deskripsi singkat mengenai layanan yang tidak disediakan" />
                      </div>
                    </div>
                    <button type="button" class="btn rlr-button text-button rlr-button--product-form-repeater js-repeater rlr-button--small rlr-button--rounded rlr-button--gray rlr-button--transparent">+
                      Tambah layanan yang tidak disediakan</button>

                    <?
                    $counter = 0;
                    if ($isEdit) {
                      foreach ($unavailableService as $unavailable) :
                        if ($counter > 0) { ?>
                          <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple rlr-fieldrow__clone--expand">
                            <div class="rlr-fieldrow__options js-fieldrow__item">
                              <label class="rlr-form-label rlr-form-label--dark"> Masukkan layanan yang tidak disediakan </label>
                              <input required value="<?= $unavailable['layanan'] ?>" type="text" autocomplete="off" maxlength="70" class="form-control" name="layanan-tidak-disediakan[]" placeholder="Deskripsi singkat mengenai layanan yang tidak disediakan" />
                            </div>
                            <button class="btn rlr-button text-button rlr-button--product-form-repeater rlr-button--small rlr-button--rounded rlr-button__color--delete rlr-button--transparent delete-dbField">- delete</button>
                          </div>
                    <? }
                        $counter++;
                      endforeach;
                    } ?>
                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide stop" data-attr="js-step-3">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Tambah pertanyaan yang sering diajukan (FAQ)</h2>
                  <span class="rlr-section__heading--sub">Jika ada pertanyaan yang sering diajukan oleh pelanggan,
                    tambahkan pertanyaan dan jawaban tersebut ke daftar FAQ agar pengguna lain dapat dengan mudah
                    menemukan informasi yang mereka butuhkan. </span>
                </div>
                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple">
                      <div class="rlr-fieldrow__options js-fieldrow__item">
                        <label class="rlr-form-label rlr-form-label--dark"> Pertanyaan </label> <input value="<?= $businessFAQ[0]['pertanyaan'] ?? null ?>" id="faqQuestionField" type="text" autocomplete="off" required maxlength="70" class="form-control" name="pertanyaan[]" placeholder="Masukkan pertanyaan yang sering diajukan" />
                      </div>
                      <div class="rlr-fieldrow__options js-fieldrow__item">
                        <label class="rlr-form-label rlr-form-label--dark"> Jawaban </label> <textarea id="faqAnswerField" required class="form-control form-control--text-area" name="jawaban[]" placeholder="Masukkan jawaban singkat dari pertanyaan tersebut." rows="12"><?= $businessFAQ[0]['jawaban'] ?? null ?></textarea>
                      </div>
                    </div>
                    <button type="button" class="btn rlr-button text-button rlr-button--product-form-repeater js-repeater rlr-button--small rlr-button--rounded rlr-button--gray rlr-button--transparent">+
                      Tambah FAQ</button>

                    <?
                    if ($isEdit) {
                      $counter = 0;
                      foreach ($businessFAQ as $faq) :
                        if ($counter > 0) { ?>
                          <div class="rlr-fieldrow__item rlr-fieldrow__item--multiple rlr-fieldrow__clone--expand">
                            <div class="rlr-fieldrow__options js-fieldrow__item">
                              <label class="rlr-form-label rlr-form-label--dark"> Pertanyaan </label> <input value="<?= $faq['pertanyaan'] ?? null ?>" id="faqQuestionField" type="text" autocomplete="off" required maxlength="70" class="form-control" name="pertanyaan[]" placeholder="Masukkan pertanyaan yang sering diajukan" />
                            </div>
                            <div class="rlr-fieldrow__options js-fieldrow__item">
                              <label class="rlr-form-label rlr-form-label--dark"> Jawaban </label> <textarea id="faqAnswerField" required class="form-control form-control--text-area" name="jawaban[]" placeholder="Masukkan jawaban singkat dari pertanyaan tersebut." rows="12"><?= $faq['jawaban'] ?? null ?></textarea>
                            </div>
                            <button class="btn rlr-button text-button rlr-button--product-form-repeater rlr-button--small rlr-button--rounded rlr-button__color--delete rlr-button--transparent delete-dbField">- delete</button>
                          </div>
                    <? }
                        $counter++;
                      endforeach;
                    } ?>

                  </div>
                </div>
              </fieldset>
              <fieldset class="rlr-product-form--hide rlr-js-product-form-fieldset start" data-attr="js-step-4">
                <!-- Section heading -->
                <div class="rlr-section__heading">
                  <h2 class="rlr-section__heading--main">Konfirmasi dan Publikasikan</h2>
                  <span class="rlr-section__heading--sub">Harap periksa kembali semua informasi yang telah Anda masukkan sebelum mempublikasikan listing. Pastikan bahwa semua informasi yang telah Anda masukkan sudah benar dan lengkap.</span>
                  <ul class="rlr-checkboxes mt-5">
                    <li class="form-check">
                      <input class="form-check-input rlr-form-check-input rlr-product-filters__checkbox" id="konfirmasi" type="checkbox">
                      <label class="rlr-form-label rlr-form-label--checkbox rlr-product-filters__checkbox-label" for="konfirmasi"> Saya yakin bahwa semua informasi yang telah diisi sudah benar dan lengkap. </label>
                    </li>
                  </ul>
                </div>
              </fieldset>
            </form>
            <hr />
            <div>
              <nav class="rlr-pagination" aria-label="Page navigation example">
                <ul class="pagination rlr-pagination__list">
                  <li class="page-item rlr-pagination__page-item--form jsPrev disabled">
                    <a class="page-link rlr-pagination__page-link--form" href="#" tabindex="-1"> Sebelumnya </a>
                  </li>
                  <li class="page-item rlr-pagination__page-item--form jsNext" id="nextSection">
                    <a class="page-link rlr-pagination__page-link--form" href="#"> Berikutnya </a>
                  </li>
                  <li class="page-item rlr-pagination__page-item--form" style="display: none;" id="submitSection">
                    <a id="submitBtn" class="page-link rlr-pagination__page-link--form" href="#"> Submit </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
          <aside class="col-xl-2 d-none d-xl-block"></aside>
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
              <? if ($user['level'] === 'admin') : ?>
                <li><a href="./dashboard/admin/">Dashboard Admin</a></li>
              <? elseif ($user['level'] === 'bisnis') : ?>
                <li><a href="./dashboard/business">Dashboard Bisnis</a></li>
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
  <script src="../vendors/jquery.min.js"></script>
  <script src="../vendors/navx/js/navigation.min.js" defer></script>
  <script src="../js/main.js" defer></script>
  <script src="../js/wilayah.js" defer></script>
  <script src="../js/upload.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
  <?
  if ($isEdit) : ?>
    <script>
      $(document).ready(function() {


        /*

          Wilayah
          
        */

        idProvinsi = <?= $place->getPlaceById($business->idDesa)['id_provinsi'] ?>;
        idKabupaten = <?= $place->getPlaceById($business->idDesa)['id_kabupaten'] ?>;
        idKecamatan = <?= $place->getPlaceById($business->idDesa)['id_kecamatan'] ?>;
        idDesa = <?= $business->idDesa ?>;

        provIntv = setInterval(function() {
          if ($(`#provinsi option[value="${idProvinsi}"]`).length) {
            $('#provinsi').val(idProvinsi).change();
            clearInterval(provIntv);
          }
        }, 50);

        kabIntv = setInterval(function() {
          if ($(`#kabupaten option[value="${idKabupaten}"]`).length) {
            $('#kabupaten').val(idKabupaten).change();
            clearInterval(kabIntv);
          }
        }, 50);

        kecIntv = setInterval(function() {
          if ($(`#kecamatan option[value="${idKecamatan}"]`).length) {
            $('#kecamatan').val(idKecamatan).change();
            clearInterval(kecIntv);
          }
        }, 50);

        desaIntv = setInterval(function() {
          if ($(`#desa option[value="${idDesa}"]`).length) {
            $('#desa').val(idDesa);
            clearInterval(desaIntv);
          }
        }, 50);

        /*

          Load Map

        */

        mapIntv = setInterval(function() {
          if ($('#map').is(':visible')) {
            map.invalidateSize();
            alamatChanged = true;
            loadMap(<?= $business->lat ?>, <?= $business->lng ?>);
            clearInterval(mapIntv);
          }
        }, 100);

      });

      /*

        Add and Delete Field

      */

      $('.delete-dbField').click(function(e) {
        e.preventDefault();
        var $parentToDelete = $(this).parent();
        $parentToDelete.remove();
      });
    </script>
  <? else : ?>
    <script>
      $(document).ready(function() {
        mapIntv = setInterval(function() {
          if ($('#map').is(':visible')) {
            map.invalidateSize();
            alamatChanged = false;
            loadMap(-6.16819755, 106.82380775460769) // Indonesia lat lng
            clearInterval(mapIntv);
          }
        }, 100);
      });
    </script>
  <? endif; ?>
  <script>
    showImages();

    $(document).ready(function() {
      $("#konfirmasi").change(function() {
        if (this.checked) {
          $('#nextSection').hide();
          $('#submitSection').show();
        } else {
          $('#submitSection').hide();
          $('#nextSection').show();
        }
      });

      $(document).on('click', '#submitBtn', function(e) {
        e.preventDefault();
        $('#jsForm').submit();
      });
    });
  </script>
</body>

</html>