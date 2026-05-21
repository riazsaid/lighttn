<?php
/**
 * Shared Hero renderer.
 *
 * Page/block data controls title, subtitle, and background image. Shared CTA,
 * CTA icon, and trust markers come from Synced Components -> Hero Settings.
 */

if (!defined('ABSPATH')) {
    exit;
}

$hero_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

$title = isset($args['title'])
    ? (string) $args['title']
    : (function_exists('get_field') ? (string) get_field('hero_title', $hero_post_id) : '');

$subtitle = isset($args['subtitle'])
    ? (string) $args['subtitle']
    : (function_exists('get_field') ? (string) get_field('hero_subtitle', $hero_post_id) : '');

$cta_link = function_exists('get_field') ? (get_field('hero_global_primary_link', 'option') ?: []) : [];
$cta_icon = function_exists('get_field') ? (get_field('hero_global_cta_icon', 'option') ?: []) : [];

$bg_url = isset($args['bg_url']) ? (string) $args['bg_url'] : '';
if ($bg_url === '' && function_exists('get_field')) {
    $hero_media = get_field('hero_media', $hero_post_id);
    if (is_array($hero_media) && !empty($hero_media['url'])) {
        $bg_url = (string) $hero_media['url'];
    }
}

$cert_icon = function_exists('get_field') ? (get_field('hero_certification_icon', 'option') ?: []) : [];
$cert_text = function_exists('get_field') ? trim((string) get_field('hero_certification_text', 'option')) : '';

$review_initials = function_exists('get_field') ? (get_field('hero_review_initials', 'option') ?: []) : [];
$review_initials = is_array($review_initials) ? array_values(array_filter($review_initials, static function ($item) {
    return !empty($item['initial']);
})) : [];

$review_label = function_exists('get_field') ? trim((string) get_field('hero_review_label', 'option')) : '';
$review_rating = function_exists('get_field') ? (int) get_field('hero_review_rating', 'option') : 0;
$review_rating = max(0, min(5, $review_rating));

$bbb_logo = function_exists('get_field') ? (get_field('hero_bbb_logo', 'option') ?: []) : [];
$bbb_text = function_exists('get_field') ? trim((string) get_field('hero_bbb_text', 'option')) : '';

$align = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

if ($title === '' && $subtitle === '') {
    return;
}

$style_attr = '';
if ($bg_url !== '') {
    $style_attr = ' style="--hero-bg-image: url(' . esc_url($bg_url) . ');"';
}

$section_class = trim('hero align' . $align . ' ' . $class_name);
$has_actions = !empty($cta_link['url']) && !empty($cta_link['title']);
$has_cert = !empty($cert_icon['ID']) || !empty($cert_icon['url']) || $cert_text !== '';
$has_reviews = !empty($review_initials) || $review_label !== '' || $review_rating > 0 || !empty($bbb_logo['ID']) || !empty($bbb_logo['url']) || $bbb_text !== '';
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container hero__shell">
        <div class="hero__panel" <?php echo $style_attr; ?>>
            <div class="hero__content">
                <?php if ($title !== ''): ?>
                    <h1 class="hero__title hero__reveal"><?php echo wp_kses_post($title); ?></h1>
                <?php endif; ?>

                <?php if ($subtitle !== ''): ?>
                    <div class="hero__subtitle hero__reveal"><?php echo wp_kses_post(wpautop($subtitle)); ?></div>
                <?php endif; ?>
            </div>

            <?php if ($has_actions || $has_cert): ?>
                <div class="hero__lower">
                    <?php if ($has_actions): ?>
                        <div class="hero__actions hero__reveal">
                            <a class="hero__link hero__link--primary" href="<?php echo esc_url($cta_link['url']); ?>"
                                target="<?php echo esc_attr($cta_link['target'] ?? '_self'); ?>">
                                <span class="hero__link-label"><?php echo esc_html($cta_link['title']); ?></span>
                                <span class="hero__link-icon" aria-hidden="true">
                                    <?php if (!empty($cta_icon['ID'])): ?>
                                        <?php echo wp_get_attachment_image($cta_icon['ID'], 'thumbnail', false, [
                                            'class' => 'hero__link-icon-image',
                                            'alt' => '',
                                        ]); ?>
                                    <?php elseif (!empty($cta_icon['url'])): ?>
                                        <img class="hero__link-icon-image" src="<?php echo esc_url($cta_icon['url']); ?>" alt=""
                                            loading="lazy" />
                                    <?php else: ?>
                                        <span class="hero__link-arrow hero__link-arrow--primary"></span>
                                        <span class="hero__link-arrow hero__link-arrow--secondary"></span>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($has_cert): ?>
                        <div class="hero__certification hero__reveal">
                            <?php if (!empty($cert_icon['ID'])): ?>
                                <?php echo wp_get_attachment_image($cert_icon['ID'], 'thumbnail', false, [
                                    'class' => 'hero__certification-icon',
                                    'alt' => '',
                                ]); ?>
                            <?php elseif (!empty($cert_icon['url'])): ?>
                                <img class="hero__certification-icon" src="<?php echo esc_url($cert_icon['url']); ?>" alt=""
                                    loading="lazy" />
                            <?php endif; ?>

                            <?php if ($cert_text !== ''): ?>
                                <span class="hero__certification-text"><?php echo nl2br(esc_html($cert_text)); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($has_reviews): ?>
            <aside class="hero__reviews" aria-label="<?php echo esc_attr__('Review highlights', 'atomic-design'); ?>">
                <?php if (!empty($review_initials)): ?>
                    <div class="hero__review-initials" aria-hidden="true">
                        <?php foreach ($review_initials as $item):
                            $initial = trim((string) ($item['initial'] ?? ''));
                            $color = trim((string) ($item['color'] ?? ''));
                            $color = function_exists('sanitize_hex_color') ? sanitize_hex_color($color) : $color;
                            $color = $color ?: '';
                            $style = $color !== '' ? ' style="--hero-initial-bg: ' . esc_attr($color) . ';"' : '';
                            if ($initial === '') {
                                continue;
                            }
                            ?>
                            <span class="hero__review-initial" <?php echo $style; ?>><?php echo esc_html(substr($initial, 0, 1)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="hero__review-copy">
                    <?php if ($review_label !== ''): ?>
                        <strong class="hero__review-label"><?php echo esc_html($review_label); ?></strong>
                    <?php endif; ?>

                    <?php if ($review_rating > 0): ?>
                        <div class="hero__stars"
                            aria-label="<?php echo esc_attr(sprintf(__('%d out of 5 stars', 'atomic-design'), $review_rating)); ?>">
                            <?php for ($star = 1; $star <= 5; $star++): ?>
                                <span aria-hidden="true"><?php echo $star <= $review_rating ? '★' : '☆'; ?></span>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($bbb_logo['ID']) || !empty($bbb_logo['url']) || $bbb_text !== ''): ?>
                    <div class="hero__bbb">
                        <?php if (!empty($bbb_logo['ID'])): ?>
                            <?php echo wp_get_attachment_image($bbb_logo['ID'], 'medium', false, [
                                'class' => 'hero__bbb-logo',
                                'alt' => $bbb_text,
                            ]); ?>
                        <?php elseif (!empty($bbb_logo['url'])): ?>
                            <img class="hero__bbb-logo" src="<?php echo esc_url($bbb_logo['url']); ?>"
                                alt="<?php echo esc_attr($bbb_text); ?>" loading="lazy" />
                        <?php elseif ($bbb_text !== ''): ?>
                            <span class="hero__bbb-text"><?php echo esc_html($bbb_text); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>
        <?php endif; ?>
    </div>
</section>