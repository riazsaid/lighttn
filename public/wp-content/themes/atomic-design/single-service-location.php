<?php
/**
 * Single Service Location template
 *
 * e.g. /locations/{city-slug}/{service-slug}/
 * Structure:
 * Hero
 * → Title Description (row 1)
 * → Insight Columns (row 1)
 * → Title Description (row 2)
 * → Property Types Grid (row 1)
 * → Proof Points (row 1)
 * → Consultation CTA Bar (constant)
 * → Trust Bar
 * → Proof Points (row 2)
 * → Testimonials
 * → About Light TN
 * → Split Callout (row 2)
 * → FAQs
 * → Partners & Affiliations
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
        get_template_part('template-parts/shared/insight-columns-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/title-description-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
            'class_name' => 'title-description-columns--service-location-areas',
        ]);
        get_template_part('template-parts/shared/property-types-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/proof-points-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/consultation-cta-bar');
        get_template_part('template-parts/shared/trust-bar');
        get_template_part('template-parts/shared/proof-points-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
        get_template_part('template-parts/shared/testimonials');
        get_template_part('template-parts/shared/about-light-tn');
        get_template_part('template-parts/shared/split-callout-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/faqs', null, ['post_id' => $post_id]);
        get_template_part('template-parts/shared/partners-affiliations');
        get_template_part('template-parts/shared/consultation-split');
    }
    ?>
</main>

<?php
get_footer();
