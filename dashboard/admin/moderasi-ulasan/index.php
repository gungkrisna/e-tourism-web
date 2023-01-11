<?php
include '../../../src/conn.php';
include '../../../src/Pengguna.php';
include '../../../src/Report.php';
include '../../../src/Review.php';
include '../../../src/ReviewPhoto.php';

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

$reports = new Report($conn);
$reviews = new Review($conn);
$reviewphotos = new ReviewPhoto($conn);
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
    <title>Moderasi Ulasan - E-Tourism</title>

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/plugins/perfectscroll/perfect-scrollbar.css" rel="stylesheet">
    <link href="../../assets/plugins/pace/pace.css" rel="stylesheet">
    <link href="../../assets/plugins/datatables/datatables.min.css" rel="stylesheet">

    <!-- Review CSS -->
    <link rel="stylesheet" href="../../../styles/plugins.css" />
    <link rel="stylesheet" href="../../../styles/main.css" />

    <!-- Theme Styles -->
    <link href="../../assets/css/main.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">

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
                    <li>
                        <a href=""><i class="material-icons-two-tone">article</i>Artikel<i class="material-icons has-sub-menu">keyboard_arrow_right</i></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="../new-article/">Buat Artikel</a>
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
                    <li class="active-page">
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
                                <li class="nav-item">
                                    <a class="nav-link" href="../../../logout"><i class="material-icons">logout</i></a>
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
                                    <h1>Moderasi Ulasan</h1>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body overflow-auto" id="mainReportCard">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="allReport" tabindex="-1" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="allReportTitle">Detail Laporan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body overflow-auto" id="allReportBody">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary aksi-ignore" id="">Abaikan Laporan</button>
                                    <button type="button" class="btn btn-danger aksi-delete" id="" href="#">Hapus Ulasan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Javascripts -->
        <script src="../../assets/plugins/jquery/jquery-3.5.1.min.js"></script>
        <script src="../../assets/plugins/bootstrap/js/popper.min.js"></script>
        <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
        <script src="../../assets/plugins/pace/pace.min.js"></script>
        <script src="../../assets/plugins/highlight/highlight.pack.js"></script>
        <script src="../../assets/plugins/datatables/datatables.min.js"></script>
        <script src="../../assets/js/main.min.js"></script>
        <script src="../../../js/listing.js"></script>
        <script>

            function loadData() {
                $.ajax({
                    url: "load_report.php",
                    type: "POST",
                    dataType: "html",
                    success: function(response) {
                        $("#mainReportCard").html(response);
                    }
                });
            }
            $(document).ready(function() {
                "use strict";

                loadData();

                function initDatatable() {
                    $('#reportDataTable').DataTable({
                        "scrollCollapse": true
                    });
                }

                $('#allReport').on('shown.bs.modal', function() {
                    $("#reportDataTable").dataTable().fnDestroy();
                    initDatatable();
                });

                /* BTN AKSI */
                $(document).on('click', '.aksi-delete', function() {
                    $.ajax({
                        url: "delete_review.php",
                        data: {
                            id: $(this).attr('id'),
                        },
                        type: "POST",
                        success: function(result) {
                            alert(result);
                        }
                    });
                    $('#allReport').modal('hide');
                    loadData();
                });

                $('.aksi-ignore').click(function() {
                    $.ajax({
                        url: "delete_report.php",
                        data: {
                            id: $(this).attr('id'),
                        },
                        type: "POST",
                        success: function(result) {
                            alert(result);
                        }
                    });
                    $('#allReport').modal('hide');
                    loadData();
                });

                $(document).on('click', '.aksi-detail', function() {
                    $('.aksi-ignore').attr('id', $(this).attr('id'));
                    $('#allReport .aksi-delete').attr('id', $(this).attr('id'));
                    $.ajax({
                        url: "detail_review.php",
                        data: {
                            id: $(this).attr('id'),
                        },
                        type: "GET",
                        success: function(response) {
                            $('#allReportBody').html(response);
                        }
                    });
                });
            });
        </script>

</body>

</html>