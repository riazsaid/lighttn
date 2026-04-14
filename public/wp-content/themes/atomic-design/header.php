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
        // Hard fallback so the top bar always renders during development.
        if (empty($phone_number)) {
            $phone_number = '(000) 000-0000';
        }
        $phone_tel = preg_replace('/[^+\d]/', '', $phone_number);
        ?>

        <!-- TOP BAR: phone + CTA (hidden on mobile) -->
        <div class="site-header__topbar">
            <div class="container site-header__topbar-inner">
                <a class="site-header__phone"
                   href="tel:<?php echo esc_attr($phone_tel); ?>">
                    <?php echo esc_html($phone_number); ?>
                </a>
                <a class="btn btn-primary site-header__topbar-cta"
                   href="<?php echo esc_url(home_url('/request-quote/')); ?>">
                    <?php esc_html_e('Get a Quote', 'atomic-design'); ?>
                </a>
            </div>
        </div>

        <div class="container site-header__inner">

            <!-- Brand / Logo -->
            <div class="site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img
                        class="site-logo"
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>"
                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        width="420"
                        height="80"
                        loading="eager"
                        decoding="async"
                    />
                </a>
            </div>

            <!-- Primary navigation -->
            <nav id="primary-navigation"
                 class="site-nav"
                 aria-label="<?php esc_attr_e('Primary menu', 'atomic-design'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'menu',
                    'fallback_cb'    => false,
                ]);
                ?>
            </nav>

            <!-- Mobile hamburger button -->
            <button class="site-header__toggle"
                    type="button"
                    aria-expanded="false"
                    aria-controls="primary-navigation"
                    aria-label="<?php esc_attr_e('Toggle navigation', 'atomic-design'); ?>">
                <span aria-hidden="true"></span>
            </button>

        </div><!-- /.container.site-header__inner -->
    </header><!-- /.site-header -->
