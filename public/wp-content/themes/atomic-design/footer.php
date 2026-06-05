<?php
$footer_services = [
    ['label' => 'Lighting Design', 'url' => '/services/lighting-design/'],
    ['label' => 'Landscape Lighting', 'url' => '/services/landscape-lighting/'],
    ['label' => 'Architectural Lighting', 'url' => '/services/architectural-lighting/'],
    ['label' => 'Roof Line System', 'url' => '/services/roof-line-system/'],
    ['label' => 'Smart Automation Systems', 'url' => '/services/smart-automation-systems/'],
    ['label' => 'Outdoor Audio', 'url' => '/services/outdoor-audio/'],
];

$footer_service_areas = [
    ['label' => 'Nashville', 'url' => '/locations/nashville/'],
    ['label' => 'Franklin', 'url' => '/locations/franklin/'],
    ['label' => 'Murfreesboro', 'url' => '/locations/murfreesboro/'],
    ['label' => 'Clarksville', 'url' => '/locations/clarksville/'],
    ['label' => 'Hendersonville', 'url' => '/locations/hendersonville/'],
    ['label' => 'Brentwood', 'url' => '/locations/brentwood/'],
    ['label' => 'Mt Juliet', 'url' => '/locations/mt-juliet/'],
    ['label' => 'Gallatin', 'url' => '/locations/gallatin/'],
    ['label' => 'Columbia', 'url' => '/locations/columbia/'],
    ['label' => 'Arrington', 'url' => '/locations/arrington/'],
    ['label' => 'Fairview', 'url' => '/locations/fairview/'],
    ['label' => 'Spring Hill', 'url' => '/locations/spring-hill/'],
    ['label' => 'Dickson', 'url' => '/locations/dickson/'],
    ['label' => 'Primm Springs', 'url' => '/locations/primm-springs/'],
    ['label' => 'Liepers Fork', 'url' => '/locations/liepers-fork/'],
];

$footer_popular_links = [
    ['label' => 'Nashville Landscape Lighting', 'url' => '/locations/nashville/landscape-lighting/'],
    ['label' => 'Franklin Landscape Lighting', 'url' => '/locations/franklin/landscape-lighting/'],
    ['label' => 'Brentwood Lighting Design', 'url' => '/locations/brentwood/lighting-design/'],
    ['label' => 'Murfreesboro Outdoor Audio', 'url' => '/locations/murfreesboro/outdoor-audio/'],
    ['label' => 'Nashville Smart Automation', 'url' => '/locations/nashville/smart-automation-systems/'],
];

$footer_social_links = [
    ['label' => 'Facebook', 'url' => 'https://www.facebook.com/', 'icon' => 'facebook'],
    ['label' => 'Instagram', 'url' => 'https://www.instagram.com/', 'icon' => 'instagram'],
    ['label' => 'YouTube', 'url' => 'https://www.youtube.com/', 'icon' => 'youtube'],
];

$footer_phone = function_exists('atomic_design_get_contact_phone') ? atomic_design_get_contact_phone() : '(615) 808-8882';
$footer_phone_tel = function_exists('atomic_design_get_contact_phone_tel') ? atomic_design_get_contact_phone_tel() : preg_replace('/[^+\d]/', '', $footer_phone);
$footer_email = function_exists('atomic_design_get_contact_email') ? atomic_design_get_contact_email() : '';
$footer_address = function_exists('atomic_design_get_contact_address') ? atomic_design_get_contact_address() : "1982 Spencer Mill Rd\nBurns, TN 37029";
?>

<footer class="site-footer" role="contentinfo">
    <div class="container site-footer__inner">
        <div class="site-footer__brand-row">
            <a class="site-footer__brand-link" href="<?php echo esc_url(home_url('/')); ?>"
                aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                <?php
                $footer_logo_id = function_exists('atomic_design_get_brand_logo_id')
                    ? atomic_design_get_brand_logo_id('footer')
                    : 0;
                ?>
                <?php if ($footer_logo_id > 0) : ?>
                    <?php echo wp_get_attachment_image($footer_logo_id, 'full', false, [
                        'class'   => 'site-footer__logo',
                        'loading' => 'lazy',
                        'decoding' => 'async',
                        'alt'     => get_bloginfo('name'),
                    ]); ?>
                <?php else : ?>
                    <span class="site-footer__brand-mark" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="site-footer__wordmark"><?php esc_html_e('LIGHT TN', 'atomic-design'); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <div class="site-footer__content">
            <section class="site-footer__contact" aria-labelledby="footer-contact-heading">
                <h2 id="footer-contact-heading" class="site-footer__heading">
                    <?php esc_html_e('Light TN', 'atomic-design'); ?></h2>
                <address class="site-footer__address">
                    <?php echo nl2br(esc_html($footer_address)); ?><br /><br />
                    <?php esc_html_e('TN License (CE-D): 76580', 'atomic-design'); ?>
                </address>
                <?php if ($footer_phone !== '') : ?>
                    <a class="site-footer__link"
                        href="tel:<?php echo esc_attr($footer_phone_tel); ?>"><?php echo esc_html($footer_phone); ?></a>
                <?php endif; ?>
                <?php if ($footer_email !== '') : ?>
                    <a class="site-footer__link"
                        href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a>
                <?php endif; ?>
            </section>

            <nav class="site-footer__group" aria-labelledby="footer-services-heading">
                <h2 id="footer-services-heading" class="site-footer__heading">
                    <?php esc_html_e('What We Do', 'atomic-design'); ?></h2>
                <ul class="site-footer__list">
                    <?php foreach ($footer_services as $footer_service): ?>
                        <li>
                            <a href="<?php echo esc_url(home_url($footer_service['url'])); ?>">
                                <?php echo esc_html($footer_service['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <nav class="site-footer__group site-footer__areas" aria-labelledby="footer-areas-heading">
                <h2 id="footer-areas-heading" class="site-footer__heading">
                    <?php esc_html_e('Service Area', 'atomic-design'); ?></h2>
                <ul class="site-footer__list site-footer__area-list">
                    <?php foreach ($footer_service_areas as $footer_area): ?>
                        <li>
                            <a href="<?php echo esc_url(home_url($footer_area['url'])); ?>">
                                <?php echo esc_html($footer_area['label']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>

            <div class="site-footer__social-column">
                <!-- <nav class="site-footer__group site-footer__popular" aria-labelledby="footer-popular-heading">
                    <h2 id="footer-popular-heading" class="site-footer__heading">
                        <?php esc_html_e('Popular Links', 'atomic-design'); ?></h2>
                    <ul class="site-footer__list">
                        <?php foreach ($footer_popular_links as $footer_popular_link): ?>
                            <li>
                                <a href="<?php echo esc_url(home_url($footer_popular_link['url'])); ?>">
                                    <?php echo esc_html($footer_popular_link['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav> -->

                <ul class="site-footer__social-icons"
                    aria-label="<?php esc_attr_e('Social links', 'atomic-design'); ?>">
                    <?php foreach ($footer_social_links as $footer_social_link): ?>
                        <li>
                            <a class="site-footer__social-icon site-footer__social-icon--<?php echo esc_attr($footer_social_link['icon']); ?>"
                                href="<?php echo esc_url($footer_social_link['url']); ?>"
                                aria-label="<?php echo esc_attr($footer_social_link['label']); ?>" target="_blank"
                                rel="noopener">
                                <?php if ($footer_social_link['icon'] === 'facebook') : ?>
                                    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                                        <path d="M14.2 8.1h2.1V4.7c-.4-.1-1.6-.2-3-.2-3 0-5 1.8-5 5.1v2.9H5v3.8h3.3V24h4v-7.7h3.3l.5-3.8h-3.8V10c0-1.1.3-1.9 1.9-1.9Z" />
                                    </svg>
                                <?php elseif ($footer_social_link['icon'] === 'instagram') : ?>
                                    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                                        <path d="M12 7.4A4.6 4.6 0 1 0 12 16.6 4.6 4.6 0 0 0 12 7.4Zm0 7.6a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                                        <path d="M17.9 7.2a1.1 1.1 0 1 0 0-2.2 1.1 1.1 0 0 0 0 2.2Z" />
                                        <path d="M16.5 1.6h-9A5.9 5.9 0 0 0 1.6 7.5v9a5.9 5.9 0 0 0 5.9 5.9h9a5.9 5.9 0 0 0 5.9-5.9v-9a5.9 5.9 0 0 0-5.9-5.9Zm4.1 14.9a4.1 4.1 0 0 1-4.1 4.1h-9a4.1 4.1 0 0 1-4.1-4.1v-9a4.1 4.1 0 0 1 4.1-4.1h9a4.1 4.1 0 0 1 4.1 4.1v9Z" />
                                    </svg>
                                <?php else : ?>
                                    <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                                        <path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31.2 31.2 0 0 0 0 12a31.2 31.2 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31.2 31.2 0 0 0 24 12a31.2 31.2 0 0 0-.5-5.8ZM9.6 15.6V8.4L15.8 12l-6.2 3.6Z" />
                                    </svg>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="site-footer__bbb"
                    aria-label="<?php esc_attr_e('BBB accreditation information', 'atomic-design'); ?>">
                    <div class="site-footer__bbb-badge">
               <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/bbb-logo.png" alt="bbb logo" loading="lazy" decoding="async"></a>
                    </div>
                    <!-- <div class="site-footer__bbb-meta">
                        <strong><?php esc_html_e('BBB Rating: A+', 'atomic-design'); ?></strong>
                        <span><?php esc_html_e('As of 2/7/2024', 'atomic-design'); ?></span>
                        <a
                            href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Click for Profile', 'atomic-design'); ?></a>
                            
                    </div> -->
                    
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p class="site-footer__copy">
                &copy; <?php echo esc_html('2020-' . date('Y')); ?> <?php esc_html_e('Light TN', 'atomic-design'); ?>
            </p>
            <nav class="site-footer__legal" aria-label="<?php esc_attr_e('Legal links', 'atomic-design'); ?>">
                <a
                    href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'atomic-design'); ?></a>
                <span aria-hidden="true">|</span>
                <a
                    href="<?php echo esc_url(home_url('/terms-of-service/')); ?>"><?php esc_html_e('Terms of Service', 'atomic-design'); ?></a>
            </nav>
            <p class="site-footer__credit">
                <?php
                printf(
                    '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                    esc_url('https://atomicdesign.net'),
                    esc_html__('Nashville web design and SEO by Atomic Design', 'atomic-design')
                );
                ?>
            </p>
        </div>
    </div>
</footer>

</div><!-- /.site-shell -->

<?php wp_footer(); ?>
</body>

</html>
