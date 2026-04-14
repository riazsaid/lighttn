<?php
/**
 * Shared area coverage grid renderer.
 *
 * Args:
 * - section_heading (string) Optional.
 * - section_description (string) Optional WYSIWYG content.
 * - areas (array) Optional area rows.
 * - cta (array) Optional ACF link array.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$section_description = isset($args['section_description']) ? (string) $args['section_description'] : '';
$areas = isset($args['areas']) && is_array($args['areas']) ? $args['areas'] : [];
$cta = isset($args['cta']) && is_array($args['cta']) ? $args['cta'] : [];
$align = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

$areas = array_values(
    array_filter(
        $areas,
        static function ($item) {
            $label = isset($item['area_coverage_label']) ? trim((string) $item['area_coverage_label']) : '';

            return $label !== '';
        }
    )
);

if ($section_heading === '' || empty($areas)) {
    return;
}

$section_class = trim('area-coverage-grid align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container">
        <div class="area-coverage-grid__inner">
            <div class="area-coverage-grid__header">
                <h2 class="area-coverage-grid__heading"><?php echo esc_html($section_heading); ?></h2>

                <?php if (trim(wp_strip_all_tags($section_description)) !== '') : ?>
                    <div class="area-coverage-grid__description">
                        <?php echo wp_kses_post($section_description); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="area-coverage-grid__areas">
                <?php foreach ($areas as $item) :
                    $label = isset($item['area_coverage_label']) ? (string) $item['area_coverage_label'] : '';
                    ?>
                    <div class="area-coverage-grid__area">
                        <?php echo nl2br(esc_html($label)); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!empty($cta['url']) && !empty($cta['title'])) : ?>
                <div class="area-coverage-grid__actions">
                    <a
                        class="btn btn-primary area-coverage-grid__cta"
                        href="<?php echo esc_url($cta['url']); ?>"
                        target="<?php echo esc_attr(!empty($cta['target']) ? $cta['target'] : '_self'); ?>"
                    >
                        <?php echo esc_html($cta['title']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
