<?php
/**
 * Shared Design Process section.
 *
 * Args:
 * - section_heading (string) Required.
 * - steps (array) Required repeater rows.
 * - align (string) Optional Gutenberg alignment slug, defaults to full.
 * - class_name (string) Optional extra class names.
 */

if (!defined('ABSPATH')) {
    exit;
}

$section_heading = isset($args['section_heading']) ? trim((string) $args['section_heading']) : '';
$steps           = isset($args['steps']) && is_array($args['steps']) ? $args['steps'] : [];
$align           = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name      = isset($args['class_name']) ? (string) $args['class_name'] : '';

$steps = array_values(array_filter($steps, static function ($step) {
    $title       = isset($step['step_title']) ? trim((string) $step['step_title']) : '';
    $nav_label   = isset($step['step_nav_label']) ? trim((string) $step['step_nav_label']) : '';
    $description = isset($step['step_description']) ? trim(wp_strip_all_tags((string) $step['step_description'])) : '';
    $image       = isset($step['step_image']) && is_array($step['step_image']) ? $step['step_image'] : [];

    return $title !== '' || $nav_label !== '' || $description !== '' || !empty($image['url']);
}));

if ($section_heading === '' || empty($steps)) {
    return;
}

$instance_id   = wp_unique_id('design-process-');
$section_class = trim('design-process align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container design-process__inner">
        <header class="design-process__header scroll-reveal">
            <h2 class="design-process__heading"><?php echo esc_html($section_heading); ?></h2>
        </header>

        <div class="design-process__body" data-design-process data-instance="<?php echo esc_attr($instance_id); ?>">
            <div class="design-process__summary-stack">
                <?php foreach ($steps as $index => $step) :
                    $step_number = sprintf('%02d', $index + 1);
                    $title       = isset($step['step_title']) ? trim((string) $step['step_title']) : '';
                    $description = isset($step['step_description']) ? trim((string) $step['step_description']) : '';
                    $icon        = isset($step['step_icon']) && is_array($step['step_icon']) ? $step['step_icon'] : [];
                    $is_active   = $index === 0;
                    ?>
                    <article
                        class="design-process__summary<?php echo $is_active ? ' is-active' : ''; ?>"
                        id="<?php echo esc_attr($instance_id . '-summary-' . $index); ?>"
                        data-step-summary
                        <?php echo $is_active ? '' : 'hidden'; ?>
                    >
                        <?php if (!empty($icon['url'])) : ?>
                            <div class="design-process__icon-wrap">
                                <img
                                    class="design-process__icon"
                                    src="<?php echo esc_url($icon['url']); ?>"
                                    alt="<?php echo esc_attr($icon['alt'] ?? ''); ?>"
                                    loading="lazy"
                                >
                            </div>
                        <?php endif; ?>

                        <?php if ($title !== '') : ?>
                            <h3 class="design-process__step-title"><?php echo esc_html($title); ?></h3>
                        <?php endif; ?>

                        <?php if (trim(wp_strip_all_tags($description)) !== '') : ?>
                            <div class="design-process__step-description">
                                <?php echo wp_kses_post(wpautop($description)); ?>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="design-process__visual-stack">
                <?php foreach ($steps as $index => $step) :
                    $step_number = sprintf('%02d', $index + 1);
                    $image       = isset($step['step_image']) && is_array($step['step_image']) ? $step['step_image'] : [];
                    $badge_title = isset($step['step_badge_title']) ? trim((string) $step['step_badge_title']) : '';
                    $nav_label   = isset($step['step_nav_label']) ? trim((string) $step['step_nav_label']) : '';
                    $title       = isset($step['step_title']) ? trim((string) $step['step_title']) : '';
                    $badge_title = $badge_title !== '' ? $badge_title : $title;
                    $tab_label   = $nav_label !== '' ? $nav_label : $title;
                    $is_active   = $index === 0;
                    ?>
                    <div
                        class="design-process__visual<?php echo $is_active ? ' is-active' : ''; ?>"
                        id="<?php echo esc_attr($instance_id . '-visual-' . $index); ?>"
                        data-step-visual
                    >
                        <div class="design-process__visual-tab" aria-hidden="true">
                            <span class="design-process__visual-tab-number"><?php echo esc_html($step_number); ?></span>
                            <?php if ($tab_label !== '') : ?>
                                <span class="design-process__visual-tab-label"><?php echo esc_html($tab_label); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($image['url'])) : ?>
                            <div class="design-process__image-wrap">
                                <img
                                    class="design-process__image"
                                    src="<?php echo esc_url($image['url']); ?>"
                                    alt="<?php echo esc_attr($image['alt'] ?? $badge_title); ?>"
                                    loading="lazy"
                                >

                                <div class="design-process__badge">
                                    <span class="design-process__badge-number"><?php echo esc_html($step_number); ?></span>
                                    <?php if ($badge_title !== '') : ?>
                                        <span class="design-process__badge-title"><?php echo esc_html($badge_title); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="design-process__rail" role="tablist" aria-label="<?php echo esc_attr($section_heading); ?>">
                <?php foreach ($steps as $index => $step) :
                    $step_number = sprintf('%02d', $index + 1);
                    $nav_label   = isset($step['step_nav_label']) ? trim((string) $step['step_nav_label']) : '';
                    $title       = isset($step['step_title']) ? trim((string) $step['step_title']) : '';
                    $nav_label   = $nav_label !== '' ? $nav_label : $title;
                    $is_active   = $index === 0;
                    ?>
                    <button
                        type="button"
                        class="design-process__rail-button<?php echo $is_active ? ' is-active' : ''; ?>"
                        data-step-button
                        data-step-index="<?php echo esc_attr((string) $index); ?>"
                        aria-controls="<?php echo esc_attr($instance_id . '-summary-' . $index . ' ' . $instance_id . '-visual-' . $index); ?>"
                        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                        role="tab"
                    >
                        <span class="design-process__rail-number"><?php echo esc_html($step_number); ?></span>
                        <span class="design-process__rail-label"><?php echo esc_html($nav_label); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
