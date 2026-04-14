<?php
/**
 * FAQ Accordion Block Template
 *
 * Used as a Gutenberg block (acf/faq-accordion).
 * Each block instance has independent FAQ items — insert it anywhere,
 * on any page or CPT template, multiple times if needed.
 *
 * The same CSS (faq-accordion.css) and JS (faq-accordion.js) that
 * power the template-part partial also apply here — one shared design.
 *
 * @param array       $block      Block settings and attributes.
 * @param string      $content    Block inner HTML (empty for ACF blocks).
 * @param bool        $is_preview True during Gutenberg AJAX preview.
 * @param int|string  $post_id    The post ID this block is saved to.
 */

$section_heading = get_field('faqs_section_heading');
$faq_layout      = get_field('faq_layout') ?: 'two-column';
$faq_items       = get_field('faq_items');

// Unique ID per block instance so multiple blocks on one page never clash.
$block_id = 'faq-block-' . ($block['id'] ?? uniqid());

// Show a placeholder while the block has no content yet (editor preview only).
if ($is_preview && (empty($faq_items) || !is_array($faq_items))) {
    echo '<div style="padding:2rem;border:2px dashed #ccc;text-align:center;color:#888;">';
    echo '<strong>FAQ Accordion</strong><br>Click the block and add FAQ items in the sidebar fields.';
    echo '</div>';
    return;
}

if (empty($faq_items) || !is_array($faq_items)) {
    return;
}

get_template_part(
    'template-parts/shared/faqs',
    null,
    [
        'section_heading' => $section_heading,
        'faq_layout'      => $faq_layout,
        'faq_items'       => $faq_items,
        'section_id'      => $block_id,
    ]
);
