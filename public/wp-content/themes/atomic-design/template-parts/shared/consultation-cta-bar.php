<?php
/**
 * Constant Consultation CTA Bar (option-driven).
 */

if (!defined('ABSPATH')) {
    exit;
}

$cta_text = function_exists('get_field') ? (string) (get_field('consultation_cta_bar_text', 'option') ?: '') : '';

if (trim($cta_text) === '') {
    $cta_text = 'Call (615) 808-8882 OR Request Your FREE Consultation';
}

$form_id = (int) (function_exists('get_field') ? (get_field('consultation_split_form_id', 'option') ?: 386) : 386);
$modal_id = 'consultation-cta-modal-' . wp_unique_id();
$modal_title_id = $modal_id . '-title';
?>

<section class="consultation-cta-bar">
    <div class="container consultation-cta-bar__inner">
        <button
            class="consultation-cta-bar__link"
            type="button"
            data-consultation-modal-open
            aria-haspopup="dialog"
            aria-controls="<?php echo esc_attr($modal_id); ?>"
        >
            <span class="consultation-cta-bar__text"><?php echo esc_html($cta_text); ?></span>
            <span class="consultation-cta-bar__icon" aria-hidden="true">↗</span>
        </button>
    </div>

    <div
        class="consultation-cta-modal"
        id="<?php echo esc_attr($modal_id); ?>"
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?php echo esc_attr($modal_title_id); ?>"
        hidden
        data-consultation-modal
    >
        <div class="consultation-cta-modal__overlay" data-consultation-modal-close></div>
        <div class="consultation-cta-modal__panel" role="document">
            <button
                class="consultation-cta-modal__close"
                type="button"
                aria-label="<?php esc_attr_e('Close consultation form', 'atomic-design'); ?>"
                data-consultation-modal-close
            >
                <span aria-hidden="true">×</span>
            </button>

            <div class="consultation-cta-modal__header">
                <h2 class="consultation-cta-modal__title" id="<?php echo esc_attr($modal_title_id); ?>">
                    <?php esc_html_e('Request Your FREE Consultation', 'atomic-design'); ?>
                </h2>
            </div>

            <div class="consultation-cta-modal__form">
                <?php if ($form_id > 0) : ?>
                    <?php echo do_shortcode('[forminator_form id="' . absint($form_id) . '"]'); ?>
                <?php else : ?>
                    <div class="consultation-cta-modal__placeholder">
                        <?php esc_html_e('Select a Forminator form for this consultation popup.', 'atomic-design'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
