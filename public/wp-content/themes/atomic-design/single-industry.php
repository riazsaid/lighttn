<?php
/**
 * Single Industry template
 *
 * Structure:
 * Hero → Title Description 1 → Trust Bar → Title Description 2
 * → Industry Solutions → Service Links 1 → Numbered Process 1 → Mid-Page Image
 * → Why Choose 1 → Why Choose 2
 * → Testimonials → Title Description 3 → FAQs.
 */
get_header();
?>

<main id="site-content">
    <?php
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/hero', null, ['post_id' => get_queried_object_id()]);
    }

    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>

    <?php
    if (function_exists('get_field')) {
        $post_id = get_queried_object_id();

        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/trust-bar');
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
    }

    get_template_part('template-parts/shared/industry-solutions');
    if (function_exists('get_field')) {
        $industry_static_image_id = (int) get_field('industry_static_image', $post_id);

        get_template_part('template-parts/shared/service-links-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);



        get_template_part('template-parts/shared/numbered-process-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        ?>
        <section class="industry-static-image">
            <div class="container">
                <div class="industry-static-image__media">
                    <?php
                    if ($industry_static_image_id > 0) {
                        echo wp_get_attachment_image(
                            $industry_static_image_id,
                            'large',
                            false,
                            [
                                'class'    => 'industry-static-image__image',
                                'loading'  => 'lazy',
                                'decoding' => 'async',
                            ]
                        );
                    } else {
                        ?>
                        <img
                            class="industry-static-image__image"
                            src="<?php echo esc_url(content_url('/uploads/2026/04/electrical-contractor-labels.jpg')); ?>"
                            alt="<?php echo esc_attr__('Electrical contractor labels', 'atomic-design'); ?>"
                            loading="lazy"
                            decoding="async"
                        />
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <?php
        get_template_part('template-parts/shared/why-choose-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/why-choose-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
    }
    get_template_part('template-parts/shared/testimonials');
    if (function_exists('get_field')) {
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 3,
        ]);
        get_template_part('template-parts/shared/faqs', null, ['post_id' => $post_id]);
    }
    ?>
</main>

<?php
get_footer();
