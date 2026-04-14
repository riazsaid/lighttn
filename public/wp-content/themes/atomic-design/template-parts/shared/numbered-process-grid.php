<?php
/**
 * Shared numbered process grid renderer.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID.
 * - section_heading (string) Optional.
 * - section_description (string) Optional.
 * - layout (string) Optional. two-column or three-column.
 * - items (array) Optional.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$process_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

$section_heading = isset($args['section_heading'])
    ? (string) $args['section_heading']
    : (function_exists('get_field') ? (string) get_field('numbered_process_heading', $process_post_id) : '');

$section_description = isset($args['section_description'])
    ? (string) $args['section_description']
    : (function_exists('get_field') ? (string) get_field('numbered_process_description', $process_post_id) : '');

$layout = isset($args['layout'])
    ? (string) $args['layout']
    : (function_exists('get_field') ? ((string) get_field('numbered_process_layout', $process_post_id) ?: 'three-column') : 'three-column');

$items = isset($args['items']) && is_array($args['items'])
    ? $args['items']
    : (function_exists('get_field') ? (get_field('numbered_process_items', $process_post_id) ?: []) : []);

$align      = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

if ($section_heading === '' || empty($items) || !is_array($items)) {
    return;
}

$items = array_values(
    array_filter(
        $items,
        static function ($item) {
            $title       = isset($item['numbered_process_item_title']) ? trim((string) $item['numbered_process_item_title']) : '';
            $description = isset($item['numbered_process_item_description']) ? trim(wp_strip_all_tags((string) $item['numbered_process_item_description'])) : '';

            return $title !== '' && $description !== '';
        }
    )
);

if (empty($items)) {
    return;
}

$layout = in_array($layout, ['two-column', 'three-column'], true) ? $layout : 'three-column';
$section_class = trim('numbered-process-grid layout-' . $layout . ' align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="numbered-process-grid__inner">
            <h2 class="numbered-process-grid__heading"><?php echo esc_html($section_heading); ?></h2>

            <?php if (trim(wp_strip_all_tags($section_description)) !== '') : ?>
                <div class="numbered-process-grid__description">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>

            <div class="numbered-process-grid__items">
                <?php foreach ($items as $index => $item) :
                    $title       = (string) $item['numbered_process_item_title'];
                    $description = (string) $item['numbered_process_item_description'];
                    ?>
                    <article class="numbered-process-grid__item">
                        <div class="numbered-process-grid__number" aria-hidden="true">
                            <?php echo esc_html((string) ($index + 1)); ?>
                        </div>

                        <div class="numbered-process-grid__content">
                            <h3 class="numbered-process-grid__item-title"><?php echo esc_html($title); ?></h3>
                            <div class="numbered-process-grid__item-description">
                                <?php echo wp_kses_post($description); ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
