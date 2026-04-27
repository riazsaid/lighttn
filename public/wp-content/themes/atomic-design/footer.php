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
        ['label' => 'Facebook', 'url' => 'https://www.facebook.com/', 'icon' => 'f'],
        ['label' => 'Instagram', 'url' => 'https://www.instagram.com/', 'icon' => 'ig'],
        ['label' => 'Youtube', 'url' => 'https://www.youtube.com/', 'icon' => 'play'],
    ];
    ?>

    <footer class="site-footer" role="contentinfo">
        <div class="container site-footer__inner">
            <div class="site-footer__brand-row">
                <a class="site-footer__brand-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <span class="site-footer__brand-mark" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="site-footer__wordmark"><?php esc_html_e('LIGHT TN', 'atomic-design'); ?></span>
                </a>
            </div>

            <div class="site-footer__content">
                <section class="site-footer__contact" aria-labelledby="footer-contact-heading">
                    <h2 id="footer-contact-heading" class="site-footer__heading"><?php esc_html_e('Light TN', 'atomic-design'); ?></h2>
                    <address class="site-footer__address">
                        <?php esc_html_e('1802 Spencer Mill Rd', 'atomic-design'); ?><br />
                        <?php esc_html_e('Burns, TN 37029', 'atomic-design'); ?><br /><br />
                        <?php esc_html_e('TN License (CE-D): 76580', 'atomic-design'); ?>
                    </address>
                    <a class="site-footer__link" href="tel:6158088882"><?php esc_html_e('(615) 808.8882', 'atomic-design'); ?></a>
                    <a class="site-footer__link" href="<?php echo esc_url(home_url('/contact-us/')); ?>"><?php esc_html_e('Email Us!', 'atomic-design'); ?></a>
                </section>

                <nav class="site-footer__group" aria-labelledby="footer-services-heading">
                    <h2 id="footer-services-heading" class="site-footer__heading"><?php esc_html_e('What We Do', 'atomic-design'); ?></h2>
                    <ul class="site-footer__list">
                        <?php foreach ($footer_services as $footer_service) : ?>
                            <li>
                                <a href="<?php echo esc_url(home_url($footer_service['url'])); ?>">
                                    <?php echo esc_html($footer_service['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <nav class="site-footer__group site-footer__areas" aria-labelledby="footer-areas-heading">
                    <h2 id="footer-areas-heading" class="site-footer__heading"><?php esc_html_e('Service Area', 'atomic-design'); ?></h2>
                    <ul class="site-footer__list site-footer__area-list">
                        <?php foreach ($footer_service_areas as $footer_area) : ?>
                            <li>
                                <a href="<?php echo esc_url(home_url($footer_area['url'])); ?>">
                                    <?php echo esc_html($footer_area['label']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="site-footer__social-column">
                    <nav class="site-footer__group site-footer__popular" aria-labelledby="footer-popular-heading">
                        <h2 id="footer-popular-heading" class="site-footer__heading"><?php esc_html_e('Popular Links', 'atomic-design'); ?></h2>
                        <ul class="site-footer__list">
                            <?php foreach ($footer_popular_links as $footer_popular_link) : ?>
                                <li>
                                    <a href="<?php echo esc_url(home_url($footer_popular_link['url'])); ?>">
                                        <?php echo esc_html($footer_popular_link['label']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>

                    <ul class="site-footer__social-icons" aria-label="<?php esc_attr_e('Social links', 'atomic-design'); ?>">
                        <?php foreach ($footer_social_links as $footer_social_link) : ?>
                            <li>
                                <a class="site-footer__social-icon site-footer__social-icon--<?php echo esc_attr($footer_social_link['icon']); ?>"
                                   href="<?php echo esc_url($footer_social_link['url']); ?>"
                                   aria-label="<?php echo esc_attr($footer_social_link['label']); ?>"
                                   target="_blank"
                                   rel="noopener">
                                    <span aria-hidden="true"><?php echo esc_html($footer_social_link['icon']); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="site-footer__bbb" aria-label="<?php esc_attr_e('BBB accreditation information', 'atomic-design'); ?>">
                        <div class="site-footer__bbb-badge">
                            <span class="site-footer__bbb-logo"><?php esc_html_e('BBB', 'atomic-design'); ?></span>
                            <span><?php esc_html_e('Accredited Business', 'atomic-design'); ?></span>
                        </div>
                        <div class="site-footer__bbb-meta">
                            <strong><?php esc_html_e('BBB Rating: A+', 'atomic-design'); ?></strong>
                            <span><?php esc_html_e('As of 2/7/2024', 'atomic-design'); ?></span>
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Click for Profile', 'atomic-design'); ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="site-footer__bottom">
                <p class="site-footer__copy">
                    &copy; <?php echo esc_html('2020-' . date('Y')); ?> <?php esc_html_e('Light TN', 'atomic-design'); ?>
                </p>
                <nav class="site-footer__legal" aria-label="<?php esc_attr_e('Legal links', 'atomic-design'); ?>">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'atomic-design'); ?></a>
                    <span aria-hidden="true">|</span>
                    <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>"><?php esc_html_e('Terms of Service', 'atomic-design'); ?></a>
                </nav>
                <p class="site-footer__credit">
                    <?php esc_html_e('Nashville web design and SEO by Atomic Design', 'atomic-design'); ?>
                </p>
            </div>
        </div>
    </footer>

</div><!-- /.site-shell -->

<?php wp_footer(); ?>
</body>
</html>
