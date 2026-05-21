<?php
/**
 * Consultation Split Block Template (acf/consultation-split)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('consultation_split_heading') ?: '';
$intro           = get_field('consultation_split_intro') ?: '';
$form_id         = (int) (get_field('consultation_split_form_id') ?: 147);
$image           = get_field('consultation_split_image') ?: [];
$has_image       = is_array($image) && (!empty($image['ID']) || !empty($image['url']));
$has_content     = ($section_heading !== '') || (trim(wp_strip_all_tags((string) $intro)) !== '') || ($form_id > 0) || $has_image;

if ($is_preview && !$has_content) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Consultation Split</strong><br>Add a heading, intro, image, and form id in the block sidebar.';
    echo '</div>';
    return;
}

if (!$has_content) {
    return;
}

get_template_part(
    'template-parts/shared/consultation-split',
    null,
    [
        'section_heading' => $section_heading,
        'intro'           => $intro,
        'form_id'         => $form_id,
        'image'           => is_array($image) ? $image : [],
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
