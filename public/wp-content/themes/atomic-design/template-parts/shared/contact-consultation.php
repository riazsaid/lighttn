<?php
/**
 * Contact-page consultation layout.
 *
 * Reuses the global Consultation Split form and booking options, but renders a
 * contact-page specific stacked layout for static pages.
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = isset($args) && is_array($args) ? $args : [];

$contact_consultation_arg = static function (array $args, string $key, string $fallback): string {
    $value = isset($args[$key]) ? trim((string) $args[$key]) : '';
    return $value !== '' ? $value : $fallback;
};

$heading = $contact_consultation_arg($args, 'heading', __('WE’RE READY TO EXCEED YOUR EXPECTATIONS', 'atomic-design'));
$subheading = $contact_consultation_arg($args, 'subheading', __('Your partner for professional outdoor lighting design, installation, and long-term performance', 'atomic-design'));
$form_heading = $contact_consultation_arg($args, 'form_heading', __('What to expect', 'atomic-design'));
$form_intro = $contact_consultation_arg($args, 'form_intro', __('Fill out the form or give us a call. A fast, transparent quote is just around the corner.', 'atomic-design'));
$booking_heading = $contact_consultation_arg($args, 'booking_heading', __('START WITH A ZOOM CONSULTATION', 'atomic-design'));
$booking_subheading = $contact_consultation_arg($args, 'booking_subheading', __('Book Your Call Now', 'atomic-design'));
$booking_points = isset($args['booking_points']) ? trim((string) $args['booking_points']) : '';
$map_image = isset($args['map_image']) && is_array($args['map_image']) ? $args['map_image'] : [];
$map_embed_url = isset($args['map_embed_url']) ? trim((string) $args['map_embed_url']) : '';
$align = !empty($args['align']) ? (string) $args['align'] : 'full';
$class_name = isset($args['class_name']) ? (string) $args['class_name'] : '';

$default_booking_embed_url = 'https://scheduler.zoom.us/lighttn/initial_consult?embedStyle=%7B%22buttonColor%22%3A%22%23ff9257%22%7D&embed=true';
$form_id = (int) (function_exists('get_field') ? (get_field('consultation_split_form_id', 'option') ?: 386) : 386);
$booking_embed_url = trim((string) (function_exists('get_field') ? (get_field('consultation_split_booking_embed_url', 'option') ?: $default_booking_embed_url) : $default_booking_embed_url));
$phone_number = trim((string) (function_exists('get_field') ? (get_field('phone_number', 'option') ?: '(615) 808-8882') : '(615) 808-8882'));
$email_address = trim((string) (function_exists('get_field') ? (get_field('email_address', 'option') ?: '') : ''));
$business_address = trim((string) (function_exists('get_field') ? (get_field('business_address', 'option') ?: "1802 Spencer Mill Rd\nBurns, TN 37029") : "1802 Spencer Mill Rd\nBurns, TN 37029"));
$phone_tel = preg_replace('/[^+\d]/', '', $phone_number);
$map_image_id = !empty($map_image['ID']) ? (int) $map_image['ID'] : 0;
$map_image_url = !empty($map_image['url']) ? (string) $map_image['url'] : '';
$map_image_alt = !empty($map_image['alt']) ? (string) $map_image['alt'] : __('Map to Light TN', 'atomic-design');
$section_class = trim('contact-consultation align' . $align . ' ' . $class_name);
?>

<section class="<?php echo esc_attr($section_class); ?>">
    <div class="container contact-consultation__inner">
        <header class="contact-consultation__header">
            <?php if ($heading !== '') : ?>
                <h2 class="contact-consultation__heading"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($subheading !== '') : ?>
                <p class="contact-consultation__subheading"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </header>

        <div class="contact-consultation__top">
            <div class="contact-consultation__panel contact-consultation__panel--form">
                <?php if ($form_heading !== '') : ?>
                    <h3 class="contact-consultation__panel-title"><?php echo esc_html($form_heading); ?></h3>
                <?php endif; ?>

                <?php if ($form_intro !== '') : ?>
                    <p class="contact-consultation__panel-copy"><?php echo esc_html($form_intro); ?></p>
                <?php endif; ?>

                <?php if ($form_id > 0) : ?>
                    <?php echo do_shortcode('[forminator_form id="' . absint($form_id) . '"]'); ?>
                <?php endif; ?>
            </div>

            <aside class="contact-consultation__details" aria-label="<?php esc_attr_e('Contact details', 'atomic-design'); ?>">
                <h3 class="contact-consultation__details-title"><?php esc_html_e('Light TN', 'atomic-design'); ?></h3>

                <?php if ($business_address !== '') : ?>
                    <address class="contact-consultation__detail contact-consultation__detail--address">
                        <?php echo nl2br(esc_html($business_address)); ?>
                    </address>
                <?php endif; ?>

                <?php if ($phone_number !== '') : ?>
                    <a class="contact-consultation__detail contact-consultation__detail--phone" href="tel:<?php echo esc_attr($phone_tel); ?>">
                        <?php echo esc_html($phone_number); ?>
                    </a>
                <?php endif; ?>

                <?php if ($email_address !== '') : ?>
                    <a class="contact-consultation__detail contact-consultation__detail--email" href="mailto:<?php echo esc_attr($email_address); ?>">
                        <?php esc_html_e('Email Us!', 'atomic-design'); ?>
                    </a>
                <?php endif; ?>

                <div class="contact-consultation__detail contact-consultation__detail--hours">
                    <strong><?php esc_html_e('Hours', 'atomic-design'); ?></strong>
                    <span><?php esc_html_e('Monday-Friday: 8:00 AM - 5:00 PM', 'atomic-design'); ?></span>
                    <span><?php esc_html_e('Saturday & Sunday: Closed', 'atomic-design'); ?></span>
                </div>
            </aside>
        </div>

        <div class="contact-consultation__panel contact-consultation__booking">
            <div class="contact-consultation__booking-copy">
                <?php if ($booking_heading !== '') : ?>
                    <h3 class="contact-consultation__booking-title"><?php echo esc_html($booking_heading); ?></h3>
                <?php endif; ?>

                <?php if ($booking_subheading !== '') : ?>
                    <p class="contact-consultation__booking-subtitle"><?php echo esc_html($booking_subheading); ?></p>
                <?php endif; ?>

                <?php if (trim(wp_strip_all_tags($booking_points)) !== '') : ?>
                    <div class="contact-consultation__booking-points">
                        <?php echo wp_kses_post(wpautop($booking_points)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($booking_embed_url !== '') : ?>
                <iframe
                    class="contact-consultation__booking-frame"
                    src="<?php echo esc_url($booking_embed_url); ?>"
                    title="<?php echo esc_attr__('Schedule a consultation', 'atomic-design'); ?>"
                    loading="lazy"
                ></iframe>
            <?php endif; ?>
        </div>

        <?php if ($map_image_id || $map_image_url !== '' || $map_embed_url !== '') : ?>
            <div class="contact-consultation__map">
                <?php if ($map_image_id) : ?>
                    <?php echo wp_get_attachment_image($map_image_id, 'large', false, [
                        'class' => 'contact-consultation__map-image',
                        'alt'   => $map_image_alt,
                    ]); ?>
                <?php elseif ($map_image_url !== '') : ?>
                    <img
                        class="contact-consultation__map-image"
                        src="<?php echo esc_url($map_image_url); ?>"
                        alt="<?php echo esc_attr($map_image_alt); ?>"
                        loading="lazy"
                    />
                <?php else : ?>
                    <iframe
                        class="contact-consultation__map-frame"
                        src="<?php echo esc_url($map_embed_url); ?>"
                        title="<?php echo esc_attr__('Map to Light TN', 'atomic-design'); ?>"
                        loading="lazy"
                    ></iframe>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
