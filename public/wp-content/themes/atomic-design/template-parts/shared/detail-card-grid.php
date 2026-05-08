<?php
/**
 * Shared Detail Card Grid section.
 *
 * Args:
 * - section_heading (string) Required.
 * - content (string) Optional HTML.
 * - left_items (array) Optional repeater rows: title, body.
 * - items (array) Required repeater rows: title, description.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$content         = isset($args['content']) ? trim((string) $args['content']) : '';
$left_items      = isset($args['left_items']) && is_array($args['left_items']) ? $args['left_items'] : [];
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$left_items = array_values(array_filter($left_items, static function ($item) {
    $title = isset($item['title']) ? trim((string) $item['title']) : '';
    $body  = isset($item['body']) ? trim(wp_strip_all_tags((string) $item['body'])) : '';
    return $title !== '' || $body !== '';
}));

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    return $title !== '' || $description !== '';
}));

$has_content = trim(wp_strip_all_tags($content)) !== '' || !empty($left_items);

if ($section_heading === '' || !$has_content || empty($items)) {
    return;
}

$section_class = trim('detail-card-grid align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container detail-card-grid__inner">
        <div class="detail-card-grid__layout">
            <div class="detail-card-grid__intro scroll-reveal" style="--reveal-delay: 70ms;">
                <h2 class="detail-card-grid__heading"><?php echo esc_html($section_heading); ?></h2>

                <?php if (trim(wp_strip_all_tags($content)) !== '') : ?>
                    <div class="detail-card-grid__content">
                        <?php echo wp_kses_post(wpautop($content)); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($left_items)) : ?>
                    <div class="detail-card-grid__left-items">
                        <?php foreach ($left_items as $left_item) :
                            $left_title = isset($left_item['title']) ? trim((string) $left_item['title']) : '';
                            $left_body  = isset($left_item['body']) ? trim((string) $left_item['body']) : '';
                            ?>
                            <div class="detail-card-grid__left-item">
                                <?php if ($left_title !== '') : ?>
                                    <h3 class="detail-card-grid__left-item-title"><?php echo esc_html($left_title); ?></h3>
                                <?php endif; ?>

                                <?php if (trim(wp_strip_all_tags($left_body)) !== '') : ?>
                                    <div class="detail-card-grid__left-item-body">
                                        <?php echo wp_kses_post(wpautop($left_body)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
