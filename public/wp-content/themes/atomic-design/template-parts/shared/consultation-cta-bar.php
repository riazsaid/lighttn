<?php
/**
 * Constant Consultation CTA Bar (option-driven).
 */

if (!defined('ABSPATH')) {
    exit;
}

$cta_text = function_exists('get_field') ? (string) (get_field('consultation_cta_bar_text', 'option') ?: '') : '';
$cta_link = function_exists('get_field') ? (get_field('consultation_cta_bar_link', 'option') ?: []) : [];

if (trim($cta_text) === '') {
    $cta_text = 'Call (615) 808-8882 OR Request Your FREE Consultation';
}

$link_url = is_array($cta_link) && !empty($cta_link['url']) ? (string) $cta_link['url'] : '';
$link_target = is_array($cta_link) && !empty($cta_link['target']) ? (string) $cta_link['target'] : '_self';

if ($link_url === '') {
    $link_url = 'tel:+16158088882';
}
?>

<section class="consultation-cta-bar">
    <div class="container consultation-cta-bar__inner">
        <a class="consultation-cta-bar__link" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
            <span class="consultation-cta-bar__text"><?php echo esc_html($cta_text); ?></span>
            <span class="consultation-cta-bar__icon" aria-hidden="true">↗</span>
        </a>
    </div>
</section>

