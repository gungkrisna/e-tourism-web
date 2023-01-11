<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Article.php';

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="E-Tourism Admin Dashboard">
    <meta name="keywords" content="e-tourism, admin, dashboard">
    <meta name="author" content="gk">
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title -->
    <title><?= $read['judul'] ?> - E-Tourism</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="../../assets/plugins/pace/pace.css" rel="stylesheet">

    <!-- Blog CSS -->
    <link rel="stylesheet" href="../../../styles/plugins.css" />
    <link rel="stylesheet" href="../../../styles/custom.css" />
    <link rel="stylesheet" href="../../../styles/main.css" />


    <!-- Theme Styles -->
    <link href="../../assets/css/main.css" rel="stylesheet">
    <link href="../../assets/CSS/custom.css" rel="stylesheet">

    <link rel="icon" type="image/png" sizes="32x32" href="../../../assets/favicon.ico" />
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets/favicon.ico" />


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
                        <img src="../../assets/images/avatars/avatar.png">
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
                    <li>
                        <a href="../"><i class="material-icons-two-tone">dashboard</i>Dashboard</a>
                    </li>
                    <li class="active-page">
                        <a href=""><i class="material-icons-two-tone">article</i>Artikel<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="../new-article/" class="active">Buat Artikel</a>
                            </li>
                            <li>
                                <a href="../manage-articles/">Kelola Artikel</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-title">
                        Moderasi
                    </li>
                    <li>
                        <a href="../moderasi-bisnis/"><i class="material-icons-two-tone">store</i>Bisnis</a>
                    </li>
                    <li>
                        <a href="../moderasi-ulasan/"><i class="material-icons-two-tone">reviews</i>Ulasan</a>
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
                                            <a class="dropdown-item" href="../../../">
                                                <h5 class="dropdown-item-title">
                                                    Halaman Utama
                                                    <span class="hidden-helper-text">Buka di tab baru<i class="material-icons">keyboard_arrow_right</i></span>
                                                </h5>
                                                <span class="dropdown-item-description">Akses halaman utama web
                                                    E-Tourism.</span>
                                            </a>
                                            <a class="dropdown-item" href="../../../blog/">
                                                <h5 class="dropdown-item-title">
                                                    Blog
                                                    <span class="hidden-helper-text">Buka di tab baru<i class="material-icons">keyboard_arrow_right</i></span>
                                                </h5>
                                                <span class="dropdown-item-description">Akses halaman blog
                                                    E-Tourism.</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                        </div>
                        <div class="d-flex">
                            <ul class="navbar-nav">
                                <li class="nav-item d-flex">
                                    <button type="button" id="publishArticle" class="btn btn-success"><i class="material-icons-outlined">post_add</i>Publikasikan</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="app-content">
                <div class="content-wrapper">
                    <div class="container">
                        <main id="rlr-main" class="rlr-main--fixed-top">
                            <!-- Blog Element -->
                            <div class="container-xxl bg-white p-5" style="border-radius: var(--spacing-8);">
                                <article class="rlr-article rlr-article-single--v2">
                                    <header class="rlr-article__header">
                                        <div class="rlr-article__header__timestamp">Diterbitkan <?= date("m/d/Y", strtotime($read['tanggal'])) ?> oleh <?= $pengguna->read($read['id_pengguna'])['nama'] ?>
                                        </div>
                                        <h1 class="type-h1" id="blogTitle" placeholder="Tuliskan judul yang menarik" contenteditable="true" style="outline: none;"><?= $read['judul'] ?></h1>
                                        <h6 class="type-sub-title" id="blogSubtitle" placeholder="Sampaikan gambaran umum tentang apa yang akan Anda sampaikan dengan singkat disini." contenteditable="true" style="outline: none;"><?= $read['subjudul'] ?></h6>
                                    </header>

                                    <div class="upload-card">
                                        <div class="drag-area" style="background: url('../../../assets/images/article/<?= $read['banner'] ?>') center / cover">
                                            <span class="visible">
                                                Drag & drop gambar disini atau
                                                <div class="d-flex justify-content-center mt-3 gap-2">
                                                    <button style="display: none" ; id="delete-file" type="button" class="btn btn-danger"><i class="material-icons-outlined">delete</i>Hapus foto</button>
                                                    <button id="select-file" type="button" class="btn btn-light"><i class="material-icons-outlined">upload_file</i>Pilih foto</button>
                                                </div>
                                            </span>
                                            <span class="on-drop">Jatuhkan gambar disini</span>

                                            <input name="file" type="file" class="file" />

                                        </div>
                                    </div>
                                    <div class="rlr-article__wrapper">
                                        <div class="content" id="article"></div>
                                    </div>
                                </article>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="../../assets/plugins/jquery/jquery-3.5.1.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
    <script src="../../assets/plugins/pace/pace.min.js"></script>
    <script src="../../assets/js/main.min.js"></script>
    <script src="../../assets/js/custom.js"></script>
    <script src="../../assets/js/ckeditor5/ckeditor.js"></script>

    <script>
        let ckEditorArticleInstance;
        $(document).ready(function() {
            BalloonBlockEditor
                .create(document.querySelector('#article'), {
                    placeholder: 'Bagikan sesuatu yang menarik di sini',
                    removePlugins: ['Title'],
                    fontFamily: {
                        options: [
                            'Noto Sans'
                        ]
                    },
                    fontColor: {
                        colors: [{
                            color: 'hsl(0, 0%, 0%)',
                            label: 'Black'
                        }]
                    },
                    simpleUpload: {
                        // The URL that the images are uploaded to.
                        uploadUrl: 'image_upload.php',

                        // Enable the XMLHttpRequest.withCredentials property.
                        withCredentials: true,

                        // Headers sent along with the XMLHttpRequest to the upload server.
                        headers: {
                            'X-CSRF-TOKEN': 'CSRF-Token',
                            Authorization: 'Bearer <JSON Web Token>'
                        }
                    }
                })
                .then(editor => {
                    ckEditorArticleInstance = editor;
                    editor.setData('<?= $read['konten'] ?>');
                })
                .catch(error => {
                    console.error(error);
                });

            $('#publishArticle').click(function() {
                pushArticle('publik');
            });

            $('#saveArticle').click(function() {
                pushArticle('draft');
            });

            function pushArticle(status) {

                if ($('.file')[0].files[0]) {
                    var formData = new FormData();
                    formData.append('upload', $('.file')[0].files[0]);
                    fetch('../new-article/image_upload.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(response => {
                            if (response['uploaded']) {
                                updateArticle(response['filename']);
                            } else {
                                console.log('Error: ' + response['error']['message']);
                            }
                        });
                } else {
                    updateArticle();
                }

                function updateArticle(banner) {
                    $.ajax({
                        type: 'POST',
                        url: 'update.php',
                        data: {
                            id_artikel: <?= $_GET['id'] ?>,
                            judul: $('#blogTitle').text(),
                            subtitle: $('#blogSubtitle').text(),
                            banner: banner ?? '<?= $read['banner'] ?>',
                            article: ckEditorArticleInstance.getData(),
                            status: status
                        },
                        success: function(response) {
                            console.log(response);
                            var redirect_url = response;
                            window.location = redirect_url;
                        }
                    });
                }
            }
        });
    </script>
</body>

</html>