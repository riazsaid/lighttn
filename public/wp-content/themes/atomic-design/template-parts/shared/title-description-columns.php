<?php
/**
 * Shared title + rich text section.
 *
 * Args:
 * - section_heading (string) Required.
 * - description (string) Required HTML from WYSIWYG.
 * - cta (array) Optional ACF link array.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$description     = isset($args['description']) ? trim((string) $args['description']) : '';
$cta             = isset($args['cta']) && is_array($args['cta']) ? $args['cta'] : [];

if ($section_heading === '' || $description === '') {
    return;
}

$description = wpautop($description);
?>

<section class="title-description-columns">
    <div class="container">
        <div class="title-description-columns__inner">
            <h2 class="title-description-columns__heading"><?php echo esc_html($section_heading); ?></h2>

            <div class="title-description-columns__content">
                <?php echo wp_kses_post($description); ?>
            </div>

            <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
                <div class="title-description-columns__actions">
                    <a class="title-description-columns__cta"
                       href="<?php echo esc_url($cta['url']); ?>"
                       target="<?php echo esc_attr($cta['target'] ?: '_self'); ?>">
                        <?php echo esc_html($cta['title']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
