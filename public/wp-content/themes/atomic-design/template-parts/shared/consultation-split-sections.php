<?php
/**
 * Shared loop for repeatable Consultation Split sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field consultation_split_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 * - form_id (int) Optional constant fallback Formidable form ID. Defaults to 386.
 * - section_heading (string) Optional constant fallback heading.
 * - intro (string) Optional constant fallback intro copy.
 * - booking_embed_html (string) Optional constant fallback booking embed/script HTML.
 */

if (!defined('ABSPATH')) {
    exit;
}

$consultation_split_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('consultation_split_sections', $consultation_split_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;
$fallback_form_id = isset($args['form_id']) ? (int) $args['form_id'] : 386;
$fallback_heading = isset($args['section_heading']) ? (string) $args['section_heading'] : __('Schedule Your On-Site Design Consultation', 'atomic-design');
$fallback_intro = isset($args['intro']) ? (string) $args['intro'] : '';
$fallback_booking_embed_html = isset($args['booking_embed_html']) ? (string) $args['booking_embed_html'] : '';

/**
 * Allow developers to inject a global booking embed/script without ACF fields.
 *
 * Example usage in a mu-plugin/theme:
 * add_filter('atomic_design_consultation_booking_embed_html', fn() => '<div>...</div><script>...</script>');
 */
$fallback_booking_embed_html = (string) apply_filters('atomic_design_consultation_booking_embed_html', $fallback_booking_embed_html);

if (empty($sections) || !is_array($sections)) {
    get_template_part(
        'template-parts/shared/consultation-split',
        null,
        [
            'section_heading'    => $fallback_heading,
            'intro'              => $fallback_intro,
            'form_id'            => $fallback_form_id,
            'booking_embed_html' => $fallback_booking_embed_html,
            'image'              => [],
        ]
    );
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['consultation_split_heading']) ? (string) $section['consultation_split_heading'] : '';
    $intro           = isset($section['consultation_split_intro']) ? (string) $section['consultation_split_intro'] : '';
    $form_id         = isset($section['consultation_split_form_id']) ? (int) $section['consultation_split_form_id'] : $fallback_form_id;
    $image           = isset($section['consultation_split_image']) && is_array($section['consultation_split_image'])
        ? $section['consultation_split_image']
        : [];

    get_template_part(
        'template-parts/shared/consultation-split',
        null,
        [
            'section_heading'    => trim($section_heading) !== '' ? $section_heading : $fallback_heading,
            'intro'              => $intro,
            'form_id'            => $form_id,
            'booking_embed_html' => $fallback_booking_embed_html,
            'image'              => $image,
        ]
    );
}
