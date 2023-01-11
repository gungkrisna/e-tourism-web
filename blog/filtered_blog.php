<?php
include '../src/conn.php';
include '../src/Pengguna.php';
include '../src/Article.php';

$artikel = new Article($conn);
$pengguna = new Pengguna($conn);

$keyword = (isset($_GET['keyword']) && !empty($_GET['keyword'])) ? $_GET['keyword'] : null;
$sort = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : 'DESC';

?>

<? foreach ($artikel->readAll(null, null, $keyword, 'publik', 'id_artikel', $sort) as $a) : ?>
    <div class="col-md-6 col-lg-4">
        <article class="rlr-postcard p-0">
            <img class="rlr-postcard__thumbnail" src="../assets/images/article/<?= $a['banner'] ?>" alt="blog image">
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