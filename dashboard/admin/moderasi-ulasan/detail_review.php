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

$review_id = $_GET['id'];
?>

<?
$review = $reviews->getReviewById($review_id)[0];
$pengulas = $pengguna->read($review['id_pengguna']);
?>

<article class="rlr-review-card my-3" itemscope itemtype="https://schema.org/Product">
    <div class="rlr-review-card__contact">
        <!--Using in Components -->
        <div class="rlr-avatar d-flex">
            <img class="rlr-avatar__media--rounded" src="<?= $pengulas['avatar'] ?>" itemprop="avatar" alt="avatar icon" />

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
        </div>
        <div class="rlr-review-card__comments" itemprop="review description">
            <div class="rlr-readmore-desc">
                <p class="rlr-readmore-desc__content rlr-js-desc"><?= $review['komentar'] ?></p>
                <span class="rlr-readmore-desc__readmore rlr-js-readmore">Selengkapnya...</span>
                <? if ($reviewphotos->read($review['id_ulasan'])) : ?>
                    <div class="rlr-itinerary__media-group">
                        <?
                        $i = 1;
                        foreach ($reviewphotos->read($review['id_ulasan']) as $photo) : ?>
                            <div class="rlr-itinerary__media mb-0">
                                <a data-fslightbox="review-images-main<?= $photo['id_foto_ulasan'] ?>" href="../../../assets/images/reviews/<?= $photo['filename'] ?>">
                                    <figure class="rlr-lightbox--gallery__figure">
                                        <img style="object-fit: cover;" class="rlr-lightbox--gallery__img" src="../../../assets/images/reviews/<?= $photo['filename'] ?>" />
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
    </div>
</article>

<h2 class="my-5">Laporan</h2>
<table id="reportDataTable" class="display" style="width:100%;">
    <thead>
        <tr>
            <th>Pelapor</th>
            <th>Jenis Laporan</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($reports->readByIdUlasan($review_id) as $r) : ?>
            <tr>
                <td><?= $pengguna->read($r['id_pengguna'])['nama'] ?></td>
                <td><?= $r['report'] ?></td>
                <td><?= $r['description'] ?></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>