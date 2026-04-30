<?php
/**
 * Split Callout Block Template (acf/split-callout)
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (unused for ACF blocks).
 * @param bool        $is_preview True during Gutenberg preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

if (!function_exists('get_field')) {
    return;
}

$section_heading = get_field('split_callout_heading') ?: '';
$intro           = get_field('split_callout_intro') ?: '';
$callout_link    = get_field('split_callout_link') ?: [];
$panel_title     = get_field('split_callout_panel_title') ?: '';
$panel_copy      = get_field('split_callout_panel_copy') ?: '';

$has_link  = is_array($callout_link) && !empty($callout_link['url']) && !empty($callout_link['title']);
$has_panel = trim((string) $panel_title) !== '' || trim(wp_strip_all_tags((string) $panel_copy)) !== '';

if ($is_preview && (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || (!$has_link && !$has_panel))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Split Callout</strong><br>Add the heading, intro copy, and right-side callout content in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || (!$has_link && !$has_panel)) {
    return;
}

get_template_part(
    'template-parts/shared/split-callout',
    null,
    [
        'section_heading' => $section_heading,
        'intro'           => $intro,
        'callout_link'    => $callout_link,
        'panel_title'     => $panel_title,
        'panel_copy'      => $panel_copy,
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
