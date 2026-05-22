<?php
/**
 * Single Location template
 *
 * Structure:
 * Hero
 * → Title Description (row 1)
 * → Trust Bar
 * → Lighting & Audio Services
 * → Steps Grid (row 1)
 * → Title Description (row 2)
 * → Property Types Grid (row 1)
 * → Title Description (row 3)
 * → Testimonials
 * → FAQs
 * → Consultation Split (row 1)
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
        get_template_part('template-parts/shared/lighting-audio-services-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/steps-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
        get_template_part('template-parts/shared/property-types-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 3,
        ]);
        get_template_part('template-parts/shared/testimonials');
        get_template_part('template-parts/shared/faqs', null, ['post_id' => $post_id]);
        get_template_part('template-parts/shared/consultation-split');
    }
    ?>
</main>

<?php
get_footer();
