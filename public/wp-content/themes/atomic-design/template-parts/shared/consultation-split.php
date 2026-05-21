<?php
/**
 * Shared Consultation Split section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro (string) Optional HTML.
 * - form_id (int) Optional. Defaults to 147.
 * - image (array) Optional ACF image array.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro           = isset($args['intro']) ? trim((string) $args['intro']) : '';
$form_id         = isset($args['form_id']) ? (int) $args['form_id'] : 147;
$image           = isset($args['image']) && is_array($args['image']) ? $args['image'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$image_id  = !empty($image['ID']) ? (int) $image['ID'] : 0;
$image_url = !empty($image['url']) ? (string) $image['url'] : '';
$image_alt = !empty($image['alt']) ? (string) $image['alt'] : '';
$has_image = $image_id > 0 || $image_url !== '';

if ($section_heading === '' && trim(wp_strip_all_tags($intro)) === '' && $form_id <= 0 && !$has_image) {
    return;
}

$section_class = trim('consultation-split align' . $align . ' ' . $class_name);
$has_form      = $form_id > 0;
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container consultation-split__inner">
        <div class="consultation-split__header">
            <?php if ($section_heading !== '') : ?>
                <h2 class="consultation-split__heading"><?php echo esc_html($section_heading); ?></h2>
            <?php endif; ?>

            <?php if (trim(wp_strip_all_tags($intro)) !== '') : ?>
                <div class="consultation-split__intro">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="consultation-split__grid">
            <div class="consultation-split__card consultation-split__card--form">
                <?php if ($has_form) : ?>
                    <?php echo do_shortcode('[forminator_form id="' . absint($form_id) . '"]'); ?>
                <?php else : ?>
                    <div class="consultation-split__placeholder">
                        <?php esc_html_e('Select a Forminator form for this section.', 'atomic-design'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="consultation-split__card consultation-split__card--visual">
                <?php if ($image_id) : ?>
                    <?php echo wp_get_attachment_image($image_id, 'large', false, [
                        'class' => 'consultation-split__image',
                        'alt'   => $image_alt,
                    ]); ?>
                <?php elseif ($image_url !== '') : ?>
                    <img class="consultation-split__image"
                         src="<?php echo esc_url($image_url); ?>"
                         alt="<?php echo esc_attr($image_alt); ?>"
                         loading="lazy" />
                <?php else : ?>
                    <div class="consultation-split__placeholder">
                        <?php esc_html_e('Add an image for the right side of this section.', 'atomic-design'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
