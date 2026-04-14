<?php
/**
 * Shared service links grid renderer.
 *
 * Args:
 * - overview_heading (string) Optional.
 * - overview_content (string) Optional WYSIWYG content.
 * - links_heading (string) Optional secondary heading above the grid.
 * - layout (string) Optional. two-column or three-column.
 * - items (array) Optional service cards.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$overview_heading = isset($args['overview_heading']) ? trim((string) $args['overview_heading']) : '';
$overview_content = isset($args['overview_content']) ? (string) $args['overview_content'] : '';
$links_heading    = isset($args['links_heading']) ? trim((string) $args['links_heading']) : '';
$layout           = isset($args['layout']) ? (string) $args['layout'] : 'three-column';
$items            = isset($args['items']) && is_array($args['items']) ? $args['items'] : [];
$align            = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name       = isset($args['class_name']) ? (string) $args['class_name'] : '';

$items = array_values(
    array_filter(
        $items,
        static function ($item) {
            $title = isset($item['service_link_title']) ? trim((string) $item['service_link_title']) : '';

            return $title !== '';
        }
    )
);

if (empty($items)) {
    return;
}

$section_heading = $overview_heading !== '' ? $overview_heading : $links_heading;
$layout          = in_array($layout, ['two-column', 'three-column'], true) ? $layout : 'three-column';
$section_class   = trim('service-links-grid layout-' . $layout . ' align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="service-links-grid__inner">
            <?php if ($section_heading !== '') : ?>
                <div class="service-links-grid__header">
                    <h2 class="service-links-grid__heading"><?php echo esc_html($section_heading); ?></h2>

                    <?php if (trim(wp_strip_all_tags($overview_content)) !== '') : ?>
                        <div class="service-links-grid__overview">
                            <?php echo wp_kses_post($overview_content); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($links_heading !== '' && $links_heading !== $section_heading) : ?>
                <h3 class="service-links-grid__subheading"><?php echo esc_html($links_heading); ?></h3>
            <?php endif; ?>

            <div class="service-links-grid__items">
                <?php foreach ($items as $item) :
                    $image_id  = isset($item['service_link_image']) ? (int) $item['service_link_image'] : 0;
                    $title     = isset($item['service_link_title']) ? (string) $item['service_link_title'] : '';
                    $body      = isset($item['service_link_body']) ? (string) $item['service_link_body'] : '';
                    $link_text = isset($item['service_link_text']) ? trim((string) $item['service_link_text']) : '';
                    $link_url  = isset($item['service_link_url']) ? trim((string) $item['service_link_url']) : '';
                    ?>
                    <article class="service-links-grid__item">
                        <?php if ($image_id > 0) : ?>
                            <div class="service-links-grid__media">
                                <?php echo wp_get_attachment_image($image_id, 'large', false, ['class' => 'service-links-grid__image']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="service-links-grid__content">
                            <h3 class="service-links-grid__item-title"><?php echo esc_html($title); ?></h3>

                            <?php if (trim(wp_strip_all_tags($body)) !== '') : ?>
                                <div class="service-links-grid__item-body">
                                    <?php echo wp_kses_post($body); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($link_text !== '' && $link_url !== '') : ?>
                                <a class="service-links-grid__link" href="<?php echo esc_url($link_url); ?>">
                                    <span><?php echo esc_html($link_text); ?></span>
                                    <span aria-hidden="true">&rarr;</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
