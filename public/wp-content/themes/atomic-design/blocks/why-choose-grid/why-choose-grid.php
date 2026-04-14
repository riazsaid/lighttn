<?php
/**
 * Why Choose Grid Block Template (acf/why-choose-grid)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('why_choose_heading') ?: '';
$section_description = get_field('why_choose_description') ?: '';
$layout          = get_field('why_choose_layout') ?: 'two-column';
$items           = get_field('why_choose_items');

if ($is_preview && (empty($section_heading) || empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Why Choose Grid</strong><br>Add a heading and reason cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || empty($items) || !is_array($items)) {
    return;
}

get_template_part(
    'template-parts/shared/why-choose-grid',
    null,
    [
        'section_heading'     => $section_heading,
        'section_description' => $section_description,
        'layout'              => $layout,
        'items'               => $items,
        'align'               => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'          => !empty($block['className']) ? $block['className'] : '',
    ]
);
