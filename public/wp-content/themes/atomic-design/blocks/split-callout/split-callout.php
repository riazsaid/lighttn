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
$investment_ranges_content = get_field('split_callout_investment_ranges_content') ?: '';
$cards           = get_field('split_callout_cards') ?: [];

$has_ranges = trim(wp_strip_all_tags((string) $investment_ranges_content)) !== '';
$has_cards = is_array($cards) && !empty($cards);

if ($is_preview && (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || (!$has_ranges && !$has_cards))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>Split Callout</strong><br>Add heading/intro, investment ranges content, and cards in the block sidebar.';
    echo '</div>';
    return;
}

if (empty($section_heading) || trim(wp_strip_all_tags((string) $intro)) === '' || (!$has_ranges && !$has_cards)) {
    return;
}

get_template_part(
    'template-parts/shared/split-callout',
    null,
    [
        'section_heading' => $section_heading,
        'intro'           => $intro,
        'investment_ranges_content' => (string) $investment_ranges_content,
        'cards'           => is_array($cards) ? $cards : [],
        'align'           => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'      => !empty($block['className']) ? $block['className'] : '',
    ]
);
