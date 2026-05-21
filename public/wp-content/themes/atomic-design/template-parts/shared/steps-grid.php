<?php
/**
 * Shared Steps Grid section.
 *
 * Args:
 * - section_heading (string) Required.
 * - heading_alignment (string) Optional. center|left.
 * - cta (array|string) Optional link field for the section button.
 * - items (array) Required repeater rows: image, title, timeline, description.
 * - portfolio_link (array) Optional ACF link array. Falls back to /gallery/.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading   = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$heading_alignment = isset($args['heading_alignment']) ? trim((string) $args['heading_alignment']) : 'center';
$items             = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$portfolio_link    = isset($args['portfolio_link']) && is_array($args['portfolio_link']) ? $args['portfolio_link'] : [];
$align             = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name        = isset($args['class_name']) ? (string) $args['class_name'] : '';

$heading_alignment = $heading_alignment === 'left' ? 'left' : 'center';

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $timeline    = isset($item['timeline']) ? trim((string) $item['timeline']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    $image       = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];

    return $title !== '' || $timeline !== '' || $description !== '' || !empty($image['url']);
}));

if ($section_heading === '' || empty($items)) {
    return;
}

$section_class = trim('steps-grid has-cta align' . $align . ' steps-grid--heading-' . $heading_alignment . ' ' . $class_name);

$portfolio_url = !empty($portfolio_link['url']) ? (string) $portfolio_link['url'] : home_url('/gallery/');
$portfolio_title = !empty($portfolio_link['title']) ? (string) $portfolio_link['title'] : __('View Portfolio', 'atomic-design');
$portfolio_target = !empty($portfolio_link['target']) ? (string) $portfolio_link['target'] : '_self';
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container steps-grid__inner">
        <header class="steps-grid__header scroll-reveal">
            <h2 class="steps-grid__heading"><?php echo esc_html($section_heading); ?></h2>
            <div class="steps-grid__actions">
                <a class="steps-grid__cta"
                   href="<?php echo esc_url($portfolio_url); ?>"
                   target="<?php echo esc_attr($portfolio_target); ?>">
                    <span><?php echo esc_html($portfolio_title); ?></span>
                    <span class="steps-grid__cta-arrow" aria-hidden="true">&rsaquo;</span>
                </a>
            </div>
        </header>

        <div class="steps-grid__items">
            <?php foreach ($items as $index => $item) :
                $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                $timeline    = isset($item['timeline']) ? trim((string) $item['timeline']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $image       = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];
                $delay       = 90 + ((int) $index * 70);
                ?>
                <article class="steps-grid__item scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if (!empty($image['url'])) : ?>
                        <div class="steps-grid__image-wrap">
                            <img
                                class="steps-grid__image"
                                src="<?php echo esc_url($image['url']); ?>"
                                alt="<?php echo esc_attr($image['alt'] ?? $title); ?>"
                                loading="lazy"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ($title !== '') : ?>
                        <h3 class="steps-grid__title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($timeline !== '') : ?>
                        <div class="steps-grid__timeline"><?php echo esc_html($timeline); ?></div>
                    <?php endif; ?>

                    <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                        <div class="steps-grid__description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
