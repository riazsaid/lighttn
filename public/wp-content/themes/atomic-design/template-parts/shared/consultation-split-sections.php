<?php
/**
 * Shared loop for repeatable Consultation Split sections on CPT templates.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - sections (array) Optional. Defaults to ACF field consultation_split_sections.
 * - section_index (int) Optional. 1-based row number to render a single section.
 */

if (!defined('ABSPATH')) {
    exit;
}

$consultation_split_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$sections = isset($args['sections']) && is_array($args['sections'])
    ? $args['sections']
    : (function_exists('get_field') ? (get_field('consultation_split_sections', $consultation_split_post_id) ?: []) : []);
$section_index = isset($args['section_index']) ? (int) $args['section_index'] : 0;

if (empty($sections) || !is_array($sections)) {
    return;
}

if ($section_index > 0) {
    $target_index = $section_index - 1;
    $sections = isset($sections[$target_index]) ? [$sections[$target_index]] : [];
}

foreach ($sections as $section) {
    $section_heading = isset($section['consultation_split_heading']) ? (string) $section['consultation_split_heading'] : '';
    $intro           = isset($section['consultation_split_intro']) ? (string) $section['consultation_split_intro'] : '';
    $form_id         = isset($section['consultation_split_form_id']) ? (int) $section['consultation_split_form_id'] : 147;
    $image           = isset($section['consultation_split_image']) && is_array($section['consultation_split_image'])
        ? $section['consultation_split_image']
        : [];

    if (trim($section_heading) === '') {
        continue;
    }

    get_template_part(
        'template-parts/shared/consultation-split',
        null,
        [
            'section_heading' => $section_heading,
            'intro'           => $intro,
            'form_id'         => $form_id,
            'image'           => $image,
        ]
    );
}
