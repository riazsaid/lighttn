<?php
/**
 * Single Service template
 *
 * Structure:
 * Hero
 * → Detail Card Grid (row 1)
 * → Steps Grid (row 1)
 * → Property Types Grid (row 1)
 * → Trust Bar
 * → Proof Points (row 1)
 * → Spotlight Cards (row 1)
 * → Testimonials
 * → About Light TN
 * → Split Callout Sections
 * → Detail Card Grid (row 2)
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

        get_template_part('template-parts/shared/detail-card-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/steps-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/property-types-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/trust-bar');
        get_template_part('template-parts/shared/proof-points-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/spotlight-cards-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
        get_template_part('template-parts/shared/testimonials');
        get_template_part('template-parts/shared/about-light-tn');
        get_template_part('template-parts/shared/split-callout-sections', null, [
            'post_id' => $post_id,
        ]);
        get_template_part('template-parts/shared/detail-card-grid-sections', null, [
            'post_id' => $post_id,
            'section_index' => 2,
        ]);
        get_template_part('template-parts/shared/faqs', null, ['post_id' => $post_id]);
        get_template_part('template-parts/shared/partners-affiliations');
        get_template_part('template-parts/shared/consultation-split-sections', null, [
            'post_id' => $post_id,
            'section_index' => 1,
        ]);
    }
    ?>
</main>

<?php
get_footer();
