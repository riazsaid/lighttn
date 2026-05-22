<?php
/**
 * Shared title + rich text section.
 *
 * Args:
 * - section_heading (string) Required.
 * - heading_alignment (string) Optional. left|center.
 * - description (string) Required HTML from WYSIWYG.
 * - cta (array) Optional ACF link array.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$heading_alignment = isset($args['heading_alignment']) ? trim((string) $args['heading_alignment']) : 'left';
$description     = isset($args['description']) ? trim((string) $args['description']) : '';
$cta             = isset($args['cta']) && is_array($args['cta']) ? $args['cta'] : [];
$class_name      = isset($args['class_name']) ? trim((string) $args['class_name']) : '';

if ($section_heading === '' || $description === '') {
    return;
}

$heading_alignment = $heading_alignment === 'center' ? 'center' : 'left';
$description = wpautop($description);
$section_class = trim('title-description-columns title-description-columns--heading-' . $heading_alignment . ' scroll-reveal ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="title-description-columns__inner">
            <h2 class="title-description-columns__heading scroll-reveal" style="--reveal-delay: 80ms;"><?php echo esc_html($section_heading); ?></h2>

            <div class="title-description-columns__content scroll-reveal" style="--reveal-delay: 160ms;">
                <?php echo wp_kses_post($description); ?>
            </div>

            <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
                <div class="title-description-columns__actions scroll-reveal" style="--reveal-delay: 220ms;">
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
