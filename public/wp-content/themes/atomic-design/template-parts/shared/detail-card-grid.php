<?php
/**
 * Shared Detail Card Grid section.
 *
 * Args:
 * - section_heading (string) Required.
 * - content (string) Optional HTML.
 * - items (array) Required repeater rows: title, description.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$content         = isset($args['content']) ? trim((string) $args['content']) : '';
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    return $title !== '' || $description !== '';
}));

if ($section_heading === '' || trim(wp_strip_all_tags($content)) === '' || empty($items)) {
    return;
}

$section_class = trim('detail-card-grid align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container detail-card-grid__inner">
        <div class="detail-card-grid__layout">
            <div class="detail-card-grid__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <h2 class="detail-card-grid__heading"><?php echo esc_html($section_heading); ?></h2>

                <div class="detail-card-grid__content">
                    <?php echo wp_kses_post(wpautop($content)); ?>
                </div>
            </div>

            <div class="detail-card-grid__cards">
                <?php foreach ($items as $index => $item) :
                    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
                    $description = isset($item['description']) ? trim((string) $item['description']) : '';
                    $delay       = 110 + ((int) $index * 70);
                    ?>
                    <article class="detail-card-grid__card scroll-reveal" style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                        <?php if ($title !== '') : ?>
                            <h3 class="detail-card-grid__card-title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>

                        <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                            <div class="detail-card-grid__card-description">
                                <?php echo wp_kses_post(wpautop($description)); ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
