<?php
/**
 * Shared Why Choose Grid renderer.
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

$why_choose_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();

$section_heading = isset($args['section_heading'])
    ? (string) $args['section_heading']
    : (function_exists('get_field') ? (string) get_field('why_choose_heading', $why_choose_post_id) : '');

$section_description = isset($args['section_description'])
    ? (string) $args['section_description']
    : (function_exists('get_field') ? (string) get_field('why_choose_description', $why_choose_post_id) : '');

$layout = isset($args['layout'])
    ? (string) $args['layout']
    : (function_exists('get_field') ? ((string) get_field('why_choose_layout', $why_choose_post_id) ?: 'two-column') : 'two-column');

$items = isset($args['items']) && is_array($args['items'])
    ? $args['items']
    : (function_exists('get_field') ? (get_field('why_choose_items', $why_choose_post_id) ?: []) : []);

$align      = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

if ($section_heading === '' || empty($items) || !is_array($items)) {
    return;
}

$items = array_values(
    array_filter(
        $items,
        static function ($item) {
            $title       = isset($item['why_choose_item_title']) ? trim((string) $item['why_choose_item_title']) : '';
            $description = isset($item['why_choose_item_description']) ? trim(wp_strip_all_tags((string) $item['why_choose_item_description'])) : '';

            return $title !== '' && $description !== '';
        }
    )
);

if (empty($items)) {
    return;
}

$layout = in_array($layout, ['two-column', 'three-column'], true) ? $layout : 'two-column';
$section_class = trim('why-choose-grid layout-' . $layout . ' align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="why-choose-grid__inner">
            <h2 class="why-choose-grid__heading"><?php echo esc_html($section_heading); ?></h2>

            <?php if (trim(wp_strip_all_tags($section_description)) !== '') : ?>
                <div class="why-choose-grid__description">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>

            <div class="why-choose-grid__items">
                <?php foreach ($items as $item) :
                    $title       = (string) $item['why_choose_item_title'];
                    $description = (string) $item['why_choose_item_description'];
                    ?>
                    <article class="why-choose-grid__item">
                        <h3 class="why-choose-grid__item-title"><?php echo nl2br(esc_html($title)); ?></h3>
                        <div class="why-choose-grid__item-description">
                            <?php echo wp_kses_post($description); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
