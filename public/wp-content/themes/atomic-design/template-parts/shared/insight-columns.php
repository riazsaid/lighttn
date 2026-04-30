<?php
/**
 * Shared Insight Columns section.
 *
 * Args:
 * - section_heading (string) Required.
 * - intro (string) Optional HTML.
 * - items (array) Required repeater rows: title, description.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$intro           = isset($args['intro']) ? trim((string) $args['intro']) : '';
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';

    return $title !== '' || $description !== '';
}));

if ($section_heading === '' || empty($items)) {
    return;
}

$section_class = trim('insight-columns align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container insight-columns__inner">
        <header class="insight-columns__header scroll-reveal">
            <h2 class="insight-columns__heading scroll-reveal" style="--reveal-delay: 70ms;"><?php echo esc_html($section_heading); ?></h2>

            <?php if (trim(wp_strip_all_tags($intro)) !== '') : ?>
                <div class="insight-columns__intro scroll-reveal" style="--reveal-delay: 150ms;">
                    <?php echo wp_kses_post(wpautop($intro)); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="insight-columns__grid">
            <?php foreach ($items as $index => $item) :
                $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                $description = isset($item['description']) ? trim((string) $item['description']) : '';
                $delay       = 120 + ((int) $index * 100);
                ?>
                <article class="insight-columns__item scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                    <?php if ($title !== '') : ?>
                        <h3 class="insight-columns__item-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                        <div class="insight-columns__item-description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
