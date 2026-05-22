<?php
/**
 * Contact Consultation Block Template (acf/contact-consultation)
 */

$heading = function_exists('get_field') ? (get_field('contact_consultation_heading') ?: '') : '';
$subheading = function_exists('get_field') ? (get_field('contact_consultation_subheading') ?: '') : '';
$form_heading = function_exists('get_field') ? (get_field('contact_consultation_form_heading') ?: '') : '';
$form_intro = function_exists('get_field') ? (get_field('contact_consultation_form_intro') ?: '') : '';
$booking_heading = function_exists('get_field') ? (get_field('contact_consultation_booking_heading') ?: '') : '';
$booking_subheading = function_exists('get_field') ? (get_field('contact_consultation_booking_subheading') ?: '') : '';
$booking_points = function_exists('get_field') ? (get_field('contact_consultation_booking_points') ?: '') : '';
$map_image = function_exists('get_field') ? (get_field('contact_consultation_map_image') ?: []) : [];
$map_embed_url = function_exists('get_field') ? (get_field('contact_consultation_map_embed_url') ?: '') : '';

get_template_part(
    'template-parts/shared/contact-consultation',
    null,
    [
        'heading'            => $heading,
        'subheading'         => $subheading,
        'form_heading'       => $form_heading,
        'form_intro'         => $form_intro,
        'booking_heading'    => $booking_heading,
        'booking_subheading' => $booking_subheading,
        'booking_points'     => $booking_points,
        'map_image'          => is_array($map_image) ? $map_image : [],
        'map_embed_url'      => $map_embed_url,
        'align'              => !empty($block['align']) ? $block['align'] : 'full',
        'class_name'         => !empty($block['className']) ? $block['className'] : '',
    ]
);
