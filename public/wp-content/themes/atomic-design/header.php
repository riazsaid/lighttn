<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <div class="site-shell">

        <header class="site-header" role="banner">

            <?php
            // Phone number — set once in WP Admin → Site Settings → Phone Number.
            // Change it there and it updates across the whole site.
            $phone_number = function_exists('get_field')
                ? get_field('phone_number', 'option')
                : '';
            if (empty($phone_number)) {
                $phone_number = get_option('atomic_phone_number', '');
            }
            // Hard fallback so the header always renders during development.
            if (empty($phone_number)) {
                $phone_number = '(615) 808-8882';
            }
            $phone_tel = preg_replace('/[^+\d]/', '', $phone_number);
            ?>

            <div class="container site-header__inner">

                <!-- Brand / Logo -->
                <div class="site-branding">
                    <a class="site-branding__link" href="<?php echo esc_url(home_url('/')); ?>"
                        aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        <?php
                        $header_logo_id = function_exists('atomic_design_get_brand_logo_id')
                            ? atomic_design_get_brand_logo_id('header')
                            : 0;
                        ?>
                        <?php if ($header_logo_id > 0) : ?>
                            <?php echo wp_get_attachment_image($header_logo_id, 'full', false, [
                                'class'   => 'site-logo',
                                'loading' => 'eager',
                                'decoding' => 'async',
                                'alt'     => get_bloginfo('name'),
                            ]); ?>
                        <?php else : ?>
                            <span class="site-brand-mark" aria-hidden="true">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                            <span class="site-branding__wordmark"><?php esc_html_e('LIGHT TN', 'atomic-design'); ?></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Primary navigation -->
                <nav id="primary-navigation" class="site-nav"
                    aria-label="<?php esc_attr_e('Primary menu', 'atomic-design'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'menu',
                        'submenu_class' => 'sub-menu',
                        'fallback_cb' => false,
                    ]);
                    ?>
                </nav>

                <div class="site-header__actions">
                    <a class="site-header__phone" href="tel:<?php echo esc_attr($phone_tel); ?>">
                        <?php echo esc_html($phone_number); ?>
                    </a>
                    <a class="site-header__contact" href="<?php echo esc_url(home_url('/contact-us/')); ?>">
                        <span class="span"><?php esc_html_e('Contact Us', 'atomic-design'); ?></span>
                        <span class="site-header__contact-icon" aria-hidden="true"></span>
                    </a>
                </div>

                <!-- Mobile hamburger button -->
                <button class="site-header__toggle" type="button" aria-expanded="false"
                    aria-controls="primary-navigation"
                    aria-label="<?php esc_attr_e('Toggle navigation', 'atomic-design'); ?>">
                    <span aria-hidden="true"></span>
                </button>

            </div><!-- /.container.site-header__inner -->
        </header><!-- /.site-header -->
