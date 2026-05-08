<?php
/**
 * Detail Card Grid Block Template (acf/detail-card-grid)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('detail_card_grid_heading') ?: '';
$content         = get_field('detail_card_grid_content') ?: '';
$left_items      = get_field('detail_card_grid_left_items') ?: [];
$items           = get_field('detail_card_grid_items') ?: [];
$has_content     = trim(wp_strip_all_tags((string) $content)) !== '' || (!empty($left_items) && is_array($left_items));

if ($is_preview && (empty($section_heading) || !$has_content || empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Detail Card Grid</strong><br>Add the heading, content, left-side rows, and right-side cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || !$has_content || empty($items) || !is_array($items)) {
    return;
}

get_template_part(
    'template-parts/shared/detail-card-grid',
    null,
    [
        'section_heading' => $section_heading,
        'content'         => $content,
        'left_items'      => is_array($left_items) ? $left_items : [],
        'items'           => $items,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
