<?php
/**
 * Shared Consultation Split section.
 *
 * Args:
 * - section_heading (string) Optional. Falls back to Synced Components -> Consultation Split.
 * - intro (string) Optional HTML.
 * - form_id (int) Optional. Defaults to global option, then 386.
 * - booking_embed_url (string) Optional booking iframe URL.
 * - booking_embed_html (string) Optional booking widget/script HTML shown on the right.
 * - image (array) Optional ACF image array.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = isset($args) && is_array($args) ? $args : [];
$has_explicit_args = !empty($args);
$default_booking_embed_url = 'https://scheduler.zoom.us/lighttn/initial_consult?embedStyle=%7B%22buttonColor%22%3A%22%23ff9257%22%7D&embed=true';

$section_heading = array_key_exists('section_heading', $args)
    ? trim((string) $args['section_heading'])
    : trim((string) (function_exists('get_field') ? (get_field('consultation_split_heading', 'option') ?: __('Schedule Your On-Site Design Consultation', 'atomic-design')) : __('Schedule Your On-Site Design Consultation', 'atomic-design')));
$intro = array_key_exists('intro', $args)
    ? trim((string) $args['intro'])
    : trim((string) (function_exists('get_field') ? (get_field('consultation_split_intro', 'option') ?: '') : ''));
$form_id = array_key_exists('form_id', $args)
    ? (int) $args['form_id']
    : (int) (function_exists('get_field') ? (get_field('consultation_split_form_id', 'option') ?: 386) : 386);
$booking_embed_url = array_key_exists('booking_embed_url', $args)
    ? trim((string) $args['booking_embed_url'])
    : ($has_explicit_args ? '' : trim((string) (function_exists('get_field') ? (get_field('consultation_split_booking_embed_url', 'option') ?: $default_booking_embed_url) : $default_booking_embed_url)));
$booking_embed_html = isset($args['booking_embed_html']) ? (string) $args['booking_embed_html'] : '';
$image           = isset($args['image']) && is_array($args['image']) ? $args['image'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$image_id  = !empty($image['ID']) ? (int) $image['ID'] : 0;
$image_url = !empty($image['url']) ? (string) $image['url'] : '';
$image_alt = !empty($image['alt']) ? (string) $image['alt'] : '';
$has_image = $image_id > 0 || $image_url !== '';
$has_booking_embed = trim(wp_strip_all_tags($booking_embed_html)) !== '' || $booking_embed_url !== '';

if ($section_heading === '' && trim(wp_strip_all_tags($intro)) === '' && $form_id <= 0 && !$has_image && !$has_booking_embed) {
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
                <?php if (trim(wp_strip_all_tags($booking_embed_html)) !== '') : ?>
                    <div class="consultation-split__booking-embed">
                        <?php echo $booking_embed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                <?php elseif ($booking_embed_url !== '') : ?>
                    <iframe
                        class="consultation-split__booking-frame"
                        src="<?php echo esc_url($booking_embed_url); ?>"
                        title="<?php echo esc_attr__('Schedule a consultation', 'atomic-design'); ?>"
                        loading="lazy"
                    ></iframe>
                <?php elseif ($image_id) : ?>
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
