<?php
/**
 * Proof Points Block Template (acf/proof-points)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('proof_points_heading') ?: '';
$intro_title     = get_field('proof_points_intro_title') ?: '';
$intro_copy      = get_field('proof_points_intro_copy') ?: '';
$items           = get_field('proof_points_items') ?: [];

if ($is_preview && (empty($section_heading) || empty($items) || !is_array($items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Proof Points</strong><br>Add a heading, intro content, and proof cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || empty($items) || !is_array($items)) {
    return;
}

get_template_part(
    'template-parts/shared/proof-points',
    null,
    [
        'section_heading' => $section_heading,
        'intro_title'     => $intro_title,
        'intro_copy'      => $intro_copy,
        'items'           => $items,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
