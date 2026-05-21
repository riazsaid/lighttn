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

$faq_columns = [$faq_items];

if ($faq_layout === 'two-column') {
    $total_faqs  = count($faq_items);
    $half        = (int) ceil($total_faqs / 2);
    $faq_columns = [
        array_slice($faq_items, 0, $half),
        array_slice($faq_items, $half),
    ];
}
?>

<section class="faq-accordion-block layout-<?php echo esc_attr($faq_layout); ?> scroll-reveal" id="<?php echo esc_attr($section_id); ?>">
    <div class="container faq-container faq-container--<?php echo esc_attr($faq_layout); ?>">
        <div class="faq-heading-wrap scroll-reveal" style="--reveal-delay: 70ms;">
            <?php if (!empty($section_heading)) : ?>
                <h2 class="faq-heading"><?php echo esc_html($section_heading); ?></h2>
            <?php endif; ?>
        </div>

        <div class="faq-grid">
            <?php foreach ($faq_columns as $column_index => $column_items) : ?>
                <div class="faq-column faq-column-<?php echo esc_attr((string) ($column_index + 1)); ?>">
                    <?php foreach ($column_items as $item_index => $faq) :
                        $global_index = $faq_layout === 'two-column' && $column_index > 0
                            ? count($faq_columns[0]) + $item_index
                            : $item_index;
                        $question     = isset($faq['faq_question']) ? $faq['faq_question'] : '';
                        $answer       = isset($faq['faq_answer']) ? $faq['faq_answer'] : '';
                        $default_open = !empty($faq['default_open']);
                        $faq_id       = $section_id . '-faq-' . $global_index;
                        $delay        = 110 + ((int) $global_index * 70);
                        $question_markup = wp_kses((string) $question, [
                            'strong' => [],
                            'b'      => [],
                            'em'     => [],
                            'i'      => [],
                            'u'      => [],
                            'br'     => [],
                            'span'   => ['class' => []],
                            'a'      => [
                                'href'   => [],
                                'target' => [],
                                'rel'    => [],
                            ],
                        ]);
                        ?>
                        <div class="faq-item scroll-reveal <?php echo $default_open ? 'active' : ''; ?>" data-faq-item style="--reveal-delay: <?php echo esc_attr((string) $delay); ?>ms;">
                            <button class="faq-question"
                                aria-expanded="<?php echo $default_open ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($faq_id); ?>">
                                <span class="question-text"><?php echo $question_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                <span class="faq-icon" aria-hidden="true">
                                    <svg class="icon-plus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                    </svg>
                                    <svg class="icon-minus" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
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
            <?php endforeach; ?>
        </div>
    </div>
</section>
