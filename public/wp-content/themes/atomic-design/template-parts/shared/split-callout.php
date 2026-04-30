<?php
/**
 * Shared Split Callout section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro (string) Optional HTML.
 * - callout_link (array) Optional ACF link array.
 * - panel_title (string) Optional.
 * - panel_copy (string) Optional HTML.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro           = isset($args['intro']) ? trim((string) $args['intro']) : '';
$callout_link    = isset($args['callout_link']) && is_array($args['callout_link']) ? $args['callout_link'] : [];
$panel_title     = isset($args['panel_title']) ? trim((string) $args['panel_title']) : '';
$panel_copy      = isset($args['panel_copy']) ? trim((string) $args['panel_copy']) : '';
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$link_url    = !empty($callout_link['url']) ? (string) $callout_link['url'] : '';
$link_title  = !empty($callout_link['title']) ? (string) $callout_link['title'] : '';
$link_target = !empty($callout_link['target']) ? (string) $callout_link['target'] : '_self';

$has_intro = trim(wp_strip_all_tags($intro)) !== '';
$has_link  = $link_url !== '' && $link_title !== '';
$has_panel = $panel_title !== '' || trim(wp_strip_all_tags($panel_copy)) !== '';

if ($section_heading === '' || !$has_intro || (!$has_link && !$has_panel)) {
    return;
}

$section_class = trim('split-callout align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container split-callout__inner">
        <div class="split-callout__grid">
            <div class="split-callout__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <h2 class="split-callout__heading"><?php echo esc_html($section_heading); ?></h2>

                <div class="split-callout__intro-copy">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            </div>

            <div class="split-callout__aside">
                <?php if ($has_link) : ?>
                    <a
                        class="split-callout__link scroll-reveal"
                        style="--reveal-delay: 120ms;"
                        href="<?php echo esc_url($link_url); ?>"
                        target="<?php echo esc_attr($link_target); ?>"
                    >
                        <span><?php echo esc_html($link_title); ?></span>
                        <span aria-hidden="true">→</span>
                    </a>
                <?php endif; ?>

                <?php if ($has_panel) : ?>
                    <article class="split-callout__panel scroll-reveal" style="--reveal-delay: 170ms;">
                        <?php if ($panel_title !== '') : ?>
                            <h3 class="split-callout__panel-title"><?php echo esc_html($panel_title); ?></h3>
                        <?php endif; ?>

                        <?php if (trim(wp_strip_all_tags($panel_copy)) !== '') : ?>
                            <div class="split-callout__panel-copy">
                                <?php echo wp_kses_post(wpautop($panel_copy)); ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
