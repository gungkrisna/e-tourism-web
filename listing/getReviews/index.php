<?php
include '../../src/conn.php';
include '../../src/Review.php';
include '../../src/ReviewPhoto.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

$reviews = new Review($conn);
$reviewphotos = new ReviewPhoto($conn);

$business_id = (isset($_GET['business_id']) || is_numeric($_GET['business_id'])) ? $_GET['business_id'] : null;
$offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : null;
$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? $_GET['limit'] : null;
$keyword = (isset($_GET['keyword'])) ? $_GET['keyword'] : null;
$stars = (isset($_GET['stars']) && !empty($_GET['stars'])) ? $_GET['stars'] : null;
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'Ulasan terbaru';

$results = $reviews->read($business_id, $offset, $limit, $stars, $keyword, $sort);
?>

<?
foreach ($results as $review) :
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$review['id_pengguna']]);
    $pengulas = $stmt->fetch();
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
                <span class="rlr-svg-icon button-report-review" data-id-ulasan="<?= $review['id_ulasan'] ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#000000">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path d="M6 14.4623H16.1909C17.6066 14.4623 18.472 12.7739 17.7261 11.4671L17.2365 10.6092C16.7547 9.76504 16.7547 8.69728 17.2365 7.85309L17.7261 6.99524C18.472 5.68842 17.6066 4 16.1909 4L6 4L6 14.4623ZM6 14.4623L6 20" stroke="#363853" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </g>
                    </svg>
                </span>
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
                                    <a data-fslightbox="review-images-<?= $photo['id_foto_ulasan'] ?>" href="../assets/images/reviews/<?= $photo['filename'] ?>">
                                        <figure class="rlr-lightbox--gallery__figure">
                                            <img class="rlr-lightbox--gallery__img" src="../assets/images/reviews/<?= $photo['filename'] ?>" />
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
<?
endforeach;
?>
