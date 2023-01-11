<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Place.php';

session_start();

$place = new Place($conn);
$pengguna = new Pengguna($conn);
$user = $pengguna->read($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Temukan objek wisata, akomodasi, serta makanan dan minuman terbaik di E-Tourism. Kami membantu Anda menemukan pengalaman wisata terbaik di seluruh dunia dengan menyediakan ulasan dan rekomendasi dari para traveler sejati. Jelajahi destinasi wisata populer atau cari inspirasi untuk liburan selanjutnya di E-Tourism." />
  <meta name="keywords" content="objek wisata, akomodasi, f&b, makanan dan minuman, rekomendasi wisata, ulasan wisata, destinasi wisata.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profil - E-Tourism</title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.ico" />
  <!-- Plugins CSS -->
  <link rel="stylesheet" href="../styles/plugins.css" />
  <!-- Main CSS -->
  <link rel="stylesheet" href="../styles/main.css" />
  <!-- Flatpickr CSS -->
  <link href="../dashboard/assets/plugins/flatpickr/flatpickr.min.css" rel="stylesheet">
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
    <div class="rlr-section__content--md-top">
      <div class="rlr-section rlr-section__my">
        <div class="container">
          <div class="row">
            <div class="col-sm-12 rlr-sidebar-menu__mobile">
              <select class="rlr-sidebar-menu__sub-menu rlr-form-select" id="rlr-js-sub-menu" name="rlr-sub-menu">
                <option value="edit-profile">Edit Profil</option>
                <option value="my-review">Ulasan Saya</option>
                <option value="logout">Logout</option>
              </select>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="row">
            <aside class="rlr-sidebar-menu col-lg-3 col-xs-12 mb-5">
              <div class="rlr-sidebar-menu__wrapper">
                <nav class="rlr-sidebar-menu">
                  <ul class="rlr-sidebar-menu__desktop">
                    <li>
                      <a class="rlr-sidebar-menu__link active" href="#">
                        <span class="rlr-sidebar-menu__link-icon"><i class="rlr-icon-font flaticon-carbon-user">
                          </i></span>
                        Edit Profil
                      </a>
                    </li>
                    <li>
                      <a class="rlr-sidebar-menu__link" href="../my-review">
                        <span class="rlr-sidebar-menu__link-icon"><i class="rlr-icon-font flaticon-carbon-box">
                          </i></span>
                        Ulasan Saya
                      </a>
                    </li>
                    <li>
                      <a class="rlr-sidebar-menu__link" href="../logout">
                        <span class="rlr-sidebar-menu__link-icon"><i class="rlr-icon-font flaticon-carbon-logout">
                          </i></span>
                        Logout
                      </a>
                    </li>
                  </ul>
                </nav>
              </div>
            </aside>
            <div class="content col-lg-9 col-xs-12">
              <div class="rlr-fieldrow">
                <div class="rlr-fieldrow__form-element" id="myAvatar">
                  <? if ($user && !is_null($user['avatar'])) : ?>
                    <a style="text-decoration:none;" href="#" onclick="openFileDialog()">
                      <img class="ui right spaced rlr-avatar rlr-avatar__media--rounded" style="height: 120px; width: 120px; object-fit: cover;" src="../assets/images/avatar/<?= $user['avatar'] ?>" alt="account avatar" />
                    </a>
                  <? else : ?>
                    <a style="text-decoration:none;" href="#" onclick="openFileDialog()">
                      <div style="height: 120px; width: 120px; align-items: center; display: flex; justify-content: center; background-color: var(--brand); color: #fff; border-radius: 50%; font-size: 50px">
                        <span><?= $initials ?></span>
                      </div>
                    </a>
                  <? endif; ?></a>
                </div>
                <input type="file" id="avatar" name="avatar" style="display: none;" onchange="updateAvatar()">


                <div class="rlr-section__heading mt-5">
                  <h2 class="rlr-section__heading--main">Edit Profil</h2>
                </div>
                <div class="row mt-5">
                  <div class="col-lg-12 col-sm-12">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                        Nama Lengkap </label>
                      <input id="nama" type="text" autocomplete="off" maxlength="70" class="form-control js-form-title" value="<?= $user['nama'] ?>" placeholder="John Doe" required>
                    </div>
                  </div>
                </div>
                <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                    Username </label>
                  <input id="username" type="text" autocomplete="off" maxlength="70" class="form-control js-form-title" value="<?= $user['username'] ?? null ?>" placeholder="@johndoe" required>
                </div>
                <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                  <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-product-title">
                    Email </label>
                  <input id="email" type="email" autocomplete="off" maxlength="70" class="form-control js-form-title" value="<?= $user['email'] ?? null ?>" placeholder="johndoe@e-tourism.com" required>
                </div>
                <div class="row">
                  <div class="col-lg-3 col-sm-12">
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Provinsi
                      </label>
                      <select required id="provinsi" name="provinsi" class="form-select rlr-form-select">
                        <option value="" disabled="disabled" selected="selected">Pilih Provinsi</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-12">
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Kabupaten
                      </label>
                      <select required id="kabupaten" name="kabupaten" class="form-select rlr-form-select">
                        <option value="" disabled="disabled" selected="selected">Pilih Kabupaten</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-12">
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Kecamatan
                      </label>
                      <select required id="kecamatan" name="kecamatan" class="form-select rlr-form-select">
                        <option value="" disabled="disabled" selected="selected">Pilih Kecamatan</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3 col-sm-12">
                    <div class="mt-4 rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-tour-select"> Desa
                      </label>
                      <select required id="desa" name="desa" class="form-select rlr-form-select">
                        <option value="" disabled="disabled" selected="selected">Pilih Desa</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-start-point"> Alamat
                      </label>
                      <textarea id="alamat" required="" class="form-control form-control--text-area" placeholder="Jalan Uluwatu No 66X." rows="12"><?= $user['alamat'] ?? null ?></textarea>
                    </div>
                  </div>
                </div>
                <div class="mt-4 rlr-fieldrow">
                  <div class="rlr-fieldrow__form-element">
                    <div class="rlr-fieldrow__item js-fieldrow__item">
                      <label class="rlr-form-label rlr-form-label--dark" for="rlr-product-form-start-point"> Tanggal
                        Lahir </label>
                      <input id="dob" value="<?= $user['tanggal_lahir'] ?? null ?>" class="form-control flatpickr1" type="text" placeholder="Pilih tanggal" required>
                    </div>
                  </div>
                </div>
                <div class="button-row align-to-right" style="margin-top: 6rem;">
                  <a onclick="updateProfile()" class="btn rlr-button rlr-button--large rlr-button--brand" style="border-radius: 12px;"> Simpan </a>
                </div>
              </div>
            </div>
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
  <script src="../js/old/main.js" defer></script>
  <script src="../dashboard/assets/plugins/jquery/jquery-3.5.1.min.js"></script>
  <script src="../dashboard/assets/plugins/flatpickr/flatpickr.js"></script>
  <script>
    $(".flatpickr1").flatpickr();

    function openFileDialog() {
      var fileInput = $("#avatar")[0];
      fileInput.value = null;
      $("#avatar").click();
    }

    function updateAvatar() {
      var fileInput = $("#avatar")[0];
      var file = fileInput.files[0];
      var src = URL.createObjectURL(file);
      var img = $("<img>", {
        class: "ui right spaced rlr-avatar rlr-avatar__media--rounded",
        style: "height: 120px; width: 120px; object-fit: cover;",
        src: src,
        alt: "account avatar"
      });
      var a = $("<a>", {
        style: "text-decoration: none;",
        href: "#",
        onclick: "openFileDialog()"
      });
      a.append(img);
      $("#myAvatar").html(a);
    }

    function updateProfile() {
      const input = $('#avatar');

      if (input[0].files[0]) {
        var formData = new FormData();
        formData.append('upload', input[0].files[0]);
        fetch('upload_avatar.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(response => {
            if (response['uploaded']) {
              updateProfileData(response['filename']);
            } else {
              console.log(response)
            }
          });
      } else {
        updateProfileData();
      }

      function updateProfileData(avatar) {
        $.ajax({
          url: 'update_profile.php',
          type: 'POST',
          data: {
            avatar: avatar ?? null,
            nama: $('#nama').val(),
            username: $('#username').val(),
            email: $('#email').val(),
            tanggal_lahir: $('#dob').val(),
            alamat: $('#alamat').val(),
            id_desa: $('#desa').val()
          },
          success: function(response) {
            alert(response);
            location.reload();
          }
        });
      }
    }
  </script>

  <script>
    $(document).ready(function() {

      $.ajax({
        type: 'GET',
        url: '../src/wilayahDropdown/getProvinsiOptions.php',
        success: function(response) {
          $('#provinsi').html(response);
        }
      });

      $('#provinsi').change(function() {
        var idProvinsi = $(this).val();

        $.ajax({
          url: '../src/wilayahDropdown/getKabupatenOptions.php',
          data: {
            idProvinsi: idProvinsi
          },
          success: function(response) {
            $('#kabupaten').html(response);

            var idKabupaten = $('#kabupaten').val();
            $.ajax({
              url: '../src/wilayahDropdown/getKecamatanOptions.php',
              data: {
                idKabupaten: idKabupaten
              },
              success: function(response) {
                $('#kecamatan').html(response);

                var idKecamatan = $('#kecamatan').val();
                $.ajax({
                  url: '../src/wilayahDropdown/getDesaOptions.php',
                  data: {
                    idKecamatan: idKecamatan
                  },
                  success: function(response) {
                    $('#desa').html(response);
                  }
                });
              }
            });
          }
        });
      });

      $('#kabupaten').change(function() {
        var idKabupaten = $(this).val();

        $.ajax({
          url: '../src/wilayahDropdown/getKecamatanOptions.php',
          data: {
            idKabupaten: idKabupaten
          },
          success: function(response) {
            $('#kecamatan').html(response);

            var idKecamatan = $('#kecamatan').val();

            $.ajax({
              url: '../src/wilayahDropdown/getDesaOptions.php',
              data: {
                idKecamatan: idKecamatan
              },
              success: function(response) {
                $('#desa').html(response);
              }
            });
          }
        });
      });

      $('#kecamatan').change(function() {
        var idKecamatan = $(this).val();
        $.ajax({
          url: '../src/wilayahDropdown/getDesaOptions.php',
          data: {
            idKecamatan: idKecamatan
          },
          success: function(response) {
            $('#desa').html(response);
          }
        });
      });

    });
  </script>

  <script>
    $(document).ready(function() {

      <? if ($user['id_desa']) : ?>
        idProvinsi = <?= $place->getPlaceById($user['id_desa'])['id_provinsi'] ?>;
        idKabupaten = <?= $place->getPlaceById($user['id_desa'])['id_kabupaten'] ?>;
        idKecamatan = <?= $place->getPlaceById($user['id_desa'])['id_kecamatan'] ?>;
        idDesa = <?= $user['id_desa'] ?>;

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
      <? endif; ?>
    });
  </script>
</body>

</html>