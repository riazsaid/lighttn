<?php
/**
 * Shared Detail Card Grid section.
 *
 * Args:
 * - section_heading (string) Required.
 * - content (string) Optional HTML.
 * - ideal_title (string) Optional.
 * - ideal_items (array) Optional repeater rows: item.
 * - included_title (string) Optional.
 * - included_description (string) Optional HTML.
 * - items (array) Required repeater rows: title, description.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$content         = isset($args['content']) ? trim((string) $args['content']) : '';
$ideal_title     = isset($args['ideal_title']) ? trim((string) $args['ideal_title']) : '';
$ideal_items     = isset($args['ideal_items']) && is_array($args['ideal_items']) ? $args['ideal_items'] : [];
$included_title  = isset($args['included_title']) ? trim((string) $args['included_title']) : '';
$included_description = isset($args['included_description']) ? trim((string) $args['included_description']) : '';
$items           = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$ideal_items = array_values(array_filter($ideal_items, static function ($item) {
    return isset($item['item']) && trim((string) $item['item']) !== '';
}));

$items = array_values(array_filter($items, static function ($item) {
    $title       = isset($item['title']) ? trim((string) $item['title']) : '';
    $description = isset($item['description']) ? trim(wp_strip_all_tags((string) $item['description'])) : '';
    return $title !== '' || $description !== '';
}));

$has_content = trim(wp_strip_all_tags($content)) !== ''
    || !empty($ideal_items)
    || trim(wp_strip_all_tags($included_description)) !== '';

if ($section_heading === '' || !$has_content || empty($items)) {
    return;
}

$ideal_title = $ideal_title !== '' ? $ideal_title : __('This service is ideal for:', 'atomic-design');
$included_title = $included_title !== '' ? $included_title : __("What's Included", 'atomic-design');
$section_class = trim('detail-card-grid align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container detail-card-grid__inner">
        <div class="detail-card-grid__intro scroll-reveal" style="--reveal-delay: 70ms;">
            <h2 class="detail-card-grid__heading"><?php echo esc_html($section_heading); ?></h2>

            <?php if (trim(wp_strip_all_tags($content)) !== '') : ?>
                <div class="detail-card-grid__content">
                    <?php echo wp_kses_post(wpautop($content)); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="detail-card-grid__layout">
            <div class="detail-card-grid__support scroll-reveal" style="--reveal-delay: 100ms;">
                <?php if (!empty($ideal_items)) : ?>
                    <div class="detail-card-grid__support-group detail-card-grid__ideal">
                        <h3 class="detail-card-grid__support-title"><?php echo esc_html($ideal_title); ?></h3>
                        <ul class="detail-card-grid__ideal-list">
                            <?php foreach ($ideal_items as $ideal_item) :
                                $ideal_text = isset($ideal_item['item']) ? trim((string) $ideal_item['item']) : '';
                                if ($ideal_text === '') {
                                    continue;
                                }
                                ?>
                                <li><?php echo esc_html($ideal_text); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (trim(wp_strip_all_tags($included_description)) !== '') : ?>
                    <div class="detail-card-grid__support-group detail-card-grid__included">
                        <h3 class="detail-card-grid__support-title"><?php echo esc_html($included_title); ?></h3>
                        <div class="detail-card-grid__included-description">
                            <?php echo wp_kses_post(wpautop($included_description)); ?>
                        </div>
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
