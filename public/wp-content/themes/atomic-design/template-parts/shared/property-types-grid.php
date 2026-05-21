<?php
/**
 * Shared Property Types Grid section.
 *
 * Args:
 * - section_heading (string) Required.
 * - items (array) Required repeater rows: image, label.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(array_filter($items, static function ($item) {
    $label = isset($item['label']) ? trim((string) $item['label']) : '';
    $image = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];

    return $label !== '' || !empty($image['url']);
}));

if ($section_heading === '' || empty($items)) {
    return;
}

$section_class = trim('property-types-grid align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container property-types-grid__inner">
        <h2 class="property-types-grid__heading scroll-reveal"><?php echo esc_html($section_heading); ?></h2>

        <div class="property-types-grid__items">
            <?php foreach ($items as $index => $item) :
                $label = isset($item['label']) ? trim((string) $item['label']) : '';
                $image = isset($item['image']) && is_array($item['image']) ? $item['image'] : [];
                $delay = 80 + ((int) $index * 60);
                ?>
                <article class="property-types-grid__item scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if (!empty($image['url'])) : ?>
                        <div class="property-types-grid__image-wrap">
                            <img
                                class="property-types-grid__image"
                                src="<?php echo esc_url($image['url']); ?>"
                                alt="<?php echo esc_attr($image['alt'] ?? $label); ?>"
                                loading="lazy"
                            >
                        </div>
                    <?php endif; ?>

                    <?php if ($label !== '') : ?>
                        <h3 class="property-types-grid__label"><?php echo esc_html($label); ?></h3>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
