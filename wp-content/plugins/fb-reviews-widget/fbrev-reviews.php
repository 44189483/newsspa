<?php
wp_register_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));
wp_enqueue_script('rplg_js', plugins_url('/static/js/rplg.js', __FILE__));

include_once(dirname(__FILE__) . '/fbrev-reviews-helper.php');

$rating = 0;
if (count($reviews) > 0) {
    foreach ($reviews as $review) {
        $rating = $rating + $review->rating;
    }
    $rating = round($rating / count($reviews), 1);
    $rating = number_format((float)$rating, 1, '.', '');
}
?>

<div class="wp-fbrev wpac">
    <div class="wp-facebook-list<?php if ($dark_theme) { ?> wp-dark<?php } ?>">
        <div class="wp-facebook-place">
            <?php fbrev_page($page_id, $page_name, $rating, $reviews, $open_link, $nofollow_link); ?>
        </div>
        <div class="wp-facebook-content-inner">
            <?php fbrev_page_reviews($page_id, $reviews, $open_link, $nofollow_link); ?>
        </div>
    </div>
</div>