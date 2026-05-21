<?php
/**
 * Title + Description Columns Block Template (acf/title-description-columns)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('title_description_heading') ?: '';
$heading_alignment = get_field('title_description_heading_alignment') ?: 'left';
$description     = get_field('title_description_content') ?: '';
$cta             = get_field('title_description_cta');

if ($is_preview && (empty($section_heading) || trim(wp_strip_all_tags((string) $description)) === '')) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Title + Description Columns</strong><br>Add a title and rich text content in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || trim(wp_strip_all_tags((string) $description)) === '') {
    return;
}

$class_name = '';
if (is_singular('service-location')) {
    $class_name = 'title-description-columns--service-location-areas';
}

get_template_part(
    'template-parts/shared/title-description-columns',
    null,
    [
        'section_heading' => $section_heading,
        'heading_alignment' => $heading_alignment,
        'description'     => $description,
        'cta'             => $cta,
        'class_name'      => $class_name,
    ]
);
