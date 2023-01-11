<?php
include '../src/conn.php';
include '../src/Business.php';
include '../src/BusinessService.php';
include '../src/BusinessPhoto.php';
include '../src/BusinessSearch.php';
include '../src/Review.php';
include '../src/Place.php';
include '../src/Wishlist.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT * FROM pengguna WHERE id_pengguna = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} else {
    header('Location: ../login');
}

$business_service = new BusinessService($conn);
$photos = new BusinessPhoto($conn);
$reviews = new Review($conn);
$place = new Place($conn);

$wishlist = [];

$wishlists = new Wishlist($conn);
$myWishlists = $wishlists->read($user['id_pengguna']);
foreach ($myWishlists as $mw) {
    $wishlist[] = $mw['id_bisnis'];
}

$wishlist = implode(',', $wishlist);

$search = new BusinessSearch($conn);

$params = [];

$params['query'] = (isset($_GET['query']) && !empty($_GET['query'])) ? $_GET['query'] : null;
$params['rating'] = (isset($_GET['rating']) && !empty($_GET['rating'])) ? $_GET['rating'] : null;
$params['kategori'] = (isset($_GET['kategori']) && !empty($_GET['kategori'])) ? $_GET['kategori'] : null;
$params['sort'] = (isset($_GET['sort']) && !empty($_GET['sort'])) ? $_GET['sort'] : null;
$params['desa'] = (isset($_GET['desa']) && !empty($_GET['desa'])) ? $_GET['desa'] : null;
$params['kecamatan'] = (isset($_GET['kecamatan']) && !empty($_GET['kecamatan'])) ? $_GET['kecamatan'] : null;
$params['kabupaten'] = (isset($_GET['kabupaten']) && !empty($_GET['kabupaten'])) ? $_GET['kabupaten'] : null;
$params['provinsi'] = (isset($_GET['provinsi']) && !empty($_GET['provinsi'])) ? $_GET['provinsi'] : null;
$params['wishlist'] = !empty($wishlist) ? $wishlist : null;

$results = $search->search($params);
?>
<? if (!empty($wishlist)) : ?>
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
                        <button id="<?= $business['id_bisnis'] ?>" type="button" class="btn rlr-button rlr-button--circle rlr-wishlist rlr-wishlist-button--light rlr-wishlist-button rlr-js-action-wishlist <?= $wishlists->isWishlist($user['id_pengguna'], $business['id_bisnis']) ? 'is-active' : '' ?>" aria-label="Save to Wishlist">
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
                                        <img itemprop="image" data-sizes="auto" style="height: 200px; object-fit:cover" data-src="../assets/images/listings/<?= $photo['filename'] ?>" data-srcset="../assets/images/listings/<?= $photo['filename'] ?>" class="lazyload" alt="product-image" />
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
    <p>Belum ada wishlist</p>
<? endif; ?>