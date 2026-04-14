<?php
/**
 * Shared FAQ template.
 *
 * Args:
 * - post_id (int) Optional. Defaults to current post ID when field data is not passed.
 * - section_heading (string) Optional.
 * - faq_layout (string) Optional.
 * - faq_items (array) Optional.
 * - section_id (string) Optional.
 */

$faq_post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$section_id  = isset($args['section_id']) ? (string) $args['section_id'] : 'faqs-' . $faq_post_id;

$section_heading = $args['section_heading'] ?? null;
$faq_layout      = $args['faq_layout'] ?? null;
$faq_items       = $args['faq_items'] ?? null;

if ($section_heading === null) {
    $section_heading = function_exists('get_field') ? get_field('faqs_section_heading', $faq_post_id) : '';
}

if ($faq_layout === null) {
    $faq_layout = function_exists('get_field') ? (get_field('faq_layout', $faq_post_id) ?: 'two-column') : 'two-column';
}

if ($faq_items === null) {
    $faq_items = function_exists('get_field') ? get_field('faq_items', $faq_post_id) : [];
}

if (empty($faq_items) || !is_array($faq_items)) {
    return;
}
?>

<section class="faq-accordion-block layout-<?php echo esc_attr($faq_layout); ?>" id="<?php echo esc_attr($section_id); ?>">
    <div class="container faq-container">
        <?php if (!empty($section_heading)) : ?>
            <h2 class="faq-heading"><?php echo esc_html($section_heading); ?></h2>
        <?php endif; ?>

        <div class="faq-grid">
            <?php
            $total_faqs = count($faq_items);
            $half       = (int) ceil($total_faqs / 2);

            foreach ($faq_items as $index => $faq) :
                $question     = isset($faq['faq_question']) ? $faq['faq_question'] : '';
                $answer       = isset($faq['faq_answer']) ? $faq['faq_answer'] : '';
                $default_open = !empty($faq['default_open']);
                $faq_id       = $section_id . '-faq-' . $index;
                $column_class = '';

                if ($faq_layout === 'two-column') {
                    $column_class = ($index < $half) ? 'column-left' : 'column-right';
                }
                ?>
                <div class="faq-item <?php echo esc_attr($column_class); ?> <?php echo $default_open ? 'active' : ''; ?>" data-faq-item>
                    <button class="faq-question"
                        aria-expanded="<?php echo $default_open ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($faq_id); ?>">
                        <span class="question-text"><?php echo esc_html($question); ?></span>
                        <span class="faq-icon" aria-hidden="true">
                            <svg class="icon-plus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            <svg class="icon-minus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                    </button>

                    <div class="faq-answer" id="<?php echo esc_attr($faq_id); ?>" <?php echo $default_open ? '' : 'hidden'; ?>>
                        <div class="faq-answer-content">
                            <?php echo wp_kses_post(wpautop($answer)); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
