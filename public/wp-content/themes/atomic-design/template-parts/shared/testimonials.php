<?php
/**
 * Testimonials Section (Shared Partial)
 *
 * Pulls all data from the central ACF Options Page:
 * Synced Components → Testimonials
 *
 * Slider layout: arrows + dots, stars under reviewer name/title.
 * Used in CPT single templates. For normal pages use the acf/testimonials block.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('get_field')) {
    return;
}

$testimonials  = get_field('testimonials_list', 'option');
$heading       = get_field('testimonials_heading', 'option') ?: 'What Our Clients Say';
$bg_type       = get_field('testimonials_bg_type', 'option') ?: 'none';
$color_1       = get_field('testimonials_color_1', 'option');
$color_2       = get_field('testimonials_color_2', 'option');
$read_more     = get_field('testimonials_read_more', 'option');
$google_logo   = get_field('google_reviews_logo', 'option');
$google_name   = get_field('google_reviews_business_name', 'option');
$google_rating_raw   = get_field('google_reviews_rating', 'option');
$google_review_count = get_field('google_reviews_review_count', 'option');

if (empty($testimonials) || !is_array($testimonials)) {
    return;
}

$bg_style = '';
if ($bg_type === 'solid' && $color_1) {
    $bg_style = 'style="background:' . esc_attr($color_1) . ';"';
} elseif ($bg_type === 'gradient' && $color_1 && $color_2) {
    $bg_style = 'style="background:linear-gradient(135deg,' . esc_attr($color_1) . ',' . esc_attr($color_2) . ');"';
}

$google_rating = is_numeric($google_rating_raw) ? (float) $google_rating_raw : 0.0;
$google_rating_display = number_format($google_rating, 1, '.', '');
$google_filled_stars = min(5, max(0, (int) round($google_rating)));
$google_review_count_display = (string) ($google_review_count !== null && $google_review_count !== '' ? $google_review_count : '0');
$google_business_name_display = !empty($google_name) ? (string) $google_name : 'Google Reviews';

$google_logo_url = '';
if (is_array($google_logo) && !empty($google_logo['url'])) {
    $google_logo_url = (string) $google_logo['url'];
}
?>

<section class="testimonials-block" <?php echo $bg_style; ?>>
<div class="container">

    <?php if (!empty($heading)) : ?>
        <h2 class="testimonials-heading"><?php echo esc_html($heading); ?></h2>
    <?php endif; ?>
<img src="<?php echo get_template_directory_uri(); ?>/assets/img/stars.svg"></div></div></div>
    <div class="container testimonials-content">
    <div class="testimonial-body">
  <aside class="google-reviews-badge" aria-label="Google reviews summary">

            <div class="aside-left">
                <div class="google-reviews-badge__brand">
                    <?php if ($google_logo_url) : ?>
                        <img
                            class="google-reviews-badge__logo"
                            src="<?php echo esc_url($google_logo_url); ?>"
                            alt="<?php echo esc_attr($google_business_name_display); ?>"
                            width="56"
                            height="56"
                            loading="lazy"
                        />
                    <?php else : ?>
                        <div class="google-reviews-badge__logo google-reviews-badge__logo--placeholder" aria-hidden="true">
                            <?php echo esc_html(mb_substr($google_business_name_display, 0, 1)); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="aside-right">
            <div class="google-reviews-badge__title">
                        <?php echo esc_html($google_business_name_display); ?>
                    </div>
                <div class="google-reviews-badge__rating-row" aria-label="<?php echo esc_attr($google_rating_display); ?> out of 5 stars">
                <div class="google-reviews-badge__rating-number">
                        <?php echo esc_html($google_rating_display); ?>
                    </div>
                    <div class="google-reviews-badge__stars" aria-hidden="true">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <span class="google-review-star <?php echo $i <= $google_filled_stars ? 'google-review-star--filled' : 'google-review-star--empty'; ?>">★</span>
                        <?php endfor; ?>
                    </div>

                </div>

                <div class="google-reviews-badge__review-count">
                    based on <?php echo esc_html($google_review_count_display); ?> reviews
                </div>

                <div class="google-reviews-badge__powered">
                    <span class="google-reviews-badge__powered-icon" aria-hidden="true">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 10.2V13.7H20.7C21 12.9 21.2 12 21.2 11.1C21.2 7.4 18.3 4.2 14.6 4.2C10.9 4.2 8 7.4 8 11.1C8 14.8 10.9 18 14.6 18C16.6 18 18.2 17.2 19.4 15.9L17.2 13.7C16.6 14.3 15.7 14.8 14.6 14.8C12.7 14.8 11.1 13.2 11.1 11.1C11.1 9 12.7 7.4 14.6 7.4C15.6 7.4 16.5 7.8 17.1 8.5L19.4 6.2C18.2 4.9 16.6 4.2 14.6 4.2C10.9 4.2 8 7.4 8 11.1C8 14.8 10.9 18 14.6 18C16.9 18 18.7 17.3 20 16.1C21.3 14.9 22 13.1 22 11.1C22 10.5 21.9 9.9 21.8 9.3H12V10.2Z" fill="#1A73E8"/>
                        </svg>
                    </span>
                    <span class="google-reviews-badge__powered-text">
                        powered by Google
                    </span>
                </div>

                <?php if (!empty($read_more['url'])) : ?>
                    <a
                        href="<?php echo esc_url($read_more['url']); ?>"
                        target="<?php echo esc_attr($read_more['target'] ?: '_self'); ?>"
                        class="google-reviews-badge__cta">
                        <span class="google-reviews-badge__cta-label">
                            <?php echo esc_html(!empty($read_more['title']) ? $read_more['title'] : 'Review us on Google'); ?>
                            
                        </span>
                        <span class="google-reviews-badge__cta-icon" aria-hidden="true">
                           <img src="<?php echo get_template_directory_uri(); ?>/assets/img/goggle.svg">
                        </span>
                    </a>
                <?php endif; ?>

            </div>

        </aside>

        <div class="testimonials-slider-area">
                <div class="testimonials-slider" data-testimonials-slider>
                    <div class="testimonials-slider__inner">
                        <div class="testimonials-slider__track" role="list">
                            <?php foreach ($testimonials as $item) :
                                $avatar = $item['reviewer_avatar'];
                                $name   = $item['reviewer_name']   ?? '';
                                $title  = $item['reviewer_title']  ?? '';
                                $rating = (int) ($item['star_rating'] ?? 5);
                                $date   = $item['review_date']     ?? '';
                                $text   = $item['testimonial_text'] ?? '';
                            ?>
                                <div class="testimonial-card" role="listitem">

                                    <div class="testimonial-google" aria-hidden="true">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/goggle.svg">
                                    </div>

                                    <div class="testimonial-card__heading">
                                        <div class="testimonial-header">
                                            <?php if (!empty($avatar['url'])) : ?>
                                                <img src="<?php echo esc_url($avatar['url']); ?>"
                                                     alt="<?php echo esc_attr($name); ?>"
                                                     width="56" height="56"
                                                     class="reviewer-avatar"
                                                     loading="lazy">
                                            <?php else : ?>
                                                <div class="reviewer-avatar reviewer-avatar--placeholder" aria-hidden="true">
                                                    <?php echo esc_html(mb_substr($name, 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="reviewer-meta">
                                                <?php if ($name) : ?>
                                                    <strong class="reviewer-name"><?php echo esc_html($name); ?></strong>
                                                <?php endif; ?>
                                                <?php if ($title) : ?>
                                                    <span class="reviewer-title"><?php echo esc_html($title); ?></span>
                                                <?php endif; ?>
                                                <?php if ($date) : ?>
                                                    <span class="review-date"><?php echo esc_html($date); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="star-rating" aria-label="<?php echo esc_attr($rating); ?> out of 5 stars">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <span class="star <?php echo $i <= $rating ? 'star--filled' : 'star--empty'; ?>" aria-hidden="true">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>

                                    <?php if ($text) : ?>
                                        <div class="testimonial-text">
                                            <?php echo wp_kses_post(wpautop($text)); ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="testimonials-slider__nav">
                        <button type="button" class="testimonials-slider__btn testimonials-slider__btn--prev" aria-label="<?php esc_attr_e('Previous testimonials', 'atomic-design'); ?>">
                            <svg class="testimonials-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M14.5 6.5L9 12l5.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" class="testimonials-slider__btn testimonials-slider__btn--next" aria-label="<?php esc_attr_e('Next testimonials', 'atomic-design'); ?>">
                            <svg class="testimonials-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M9.5 6.5L15 12l-5.5 5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="testimonials-slider__dots" role="tablist" aria-label="<?php esc_attr_e('Testimonial pages', 'atomic-design'); ?>"></div>
                </div>
            </div>
    </div>
     <div class="review-btn"><a href="#">Read Review</a></div>

    </div> <!-- testimonials-content -->
    

</div> <!-- container -->
</section>
