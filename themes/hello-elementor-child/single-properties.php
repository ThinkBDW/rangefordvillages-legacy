<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package HelloElementor
 */
get_header();

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

while (have_posts()):
    the_post();
    ?>

    <main id="content" <?php post_class('site-main'); ?>>

        <?php
        $price = get_field('price');
        $location = get_field('location');
        $bed = get_field('bed');
        $properties_type = get_field('properties_type');
        $bathroom = get_field('bathroom');
        $location_icon = get_field('location_icon');
        $bed_icon = get_field('bed_icon');
        $properties_type_icon = get_field('properties_type_icon');
        $bathroom_icon = get_field('bathroom_icon');
        $properties_ft_icon = get_field('properties_ft_icon');
        $properties_sq_ft = get_field('properties_sq_ft');
        $dormer_bungalow_icon = get_field('dormer_bungalow_icon');
        $dormer_bungalow_text = get_field('dormer_bungalow_text');
        

        $properties_sale_icon = get_field('properties_sale_icon');
        $properties_sale_text = get_field('properties_sale_text');
        $brochure = get_field('brochure');
        ?>
        <section class="properties-content">
            <div class="main-top-sec">
                <div class="container-set">
                    <div class="masonry-gallery main-hero-images-set">
                        <?php
                        $properties_gallery = get_field('properties_gallery');
                        if ($properties_gallery) {
                            $counter = 0; // Initialize counter
                    
                            foreach ($properties_gallery as $image) {
                                // For the first image
                                if ($counter == 0) { ?>
                                    <div class="first-image">
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
                                        <div class="all-main-btn-set">
                                            <div class="light-box-btn"></div>
											<div class="property-amenities">
                                            <?php
                                            // Get the current property post ID
                                            $property_id = get_the_ID();

                                            // Get the assigned amenities for the current property
                                            $amenities = get_the_terms($property_id, 'amenities');

                                            // Check if amenities are found
                                            if ($amenities && !is_wp_error($amenities)) {
                                                ?>
                                                
                                                    <ul>
                                                        <?php
                                                        // Loop through each amenity and display it
                                                        foreach ($amenities as $amenity) {
                                                            echo '<li>' . esc_html($amenity->name) . '</li>';
                                                        }
                                                        ?>
                                                    </ul>
                                                
                                                <?php
                                            }
                                            ?>
											</div>		
                                            <div class="all-photos">
                                                <div class="property-title">
                                                    <div class="wishlist-icon-section-single" data-property-id="<?php echo get_the_ID(); ?>">
                                                        <img class="without-fill" src="/wp-content/uploads/2023/12/Vector-2.png"
                                                            alt="Wishlist">
                                                        <img style="display:none;" class="fill-whish-list"
                                                            src="/wp-content/uploads/2023/12/Vector-1.png" alt="Wishlist">
                                                            <span class="wishlist-text without-fill">Save Property</span>
                                                            <span style="display:none;" class="fill-whish-list wishlist-text">Remove Property</span>
                                                    </div>
                                                    
                                                    
                                                </div>
                                                <button class="open-lightbox">View all photos</button>
                                                <?php
                                                    $post_id_arrange = get_the_ID();
                                                    $post_title_arrange = get_the_title($post_id_arrange);
                                                    $post_slug = get_post_field('post_name', $post_id_arrange);
                                                    $contact_page_url_broucher = add_query_arg(
                                                        array(
                                                            'propertiesvillage' => urlencode($location),
                                                            'property' => urlencode($post_title_arrange),
                                                            'contactType' => 'request-brochure',
                                                            
                                                        ),
                                                        get_permalink(get_page_by_path('contact-us'))
                                                    );
                                                ?>
                                                <a data-term-redirectproperty="<?php echo $contact_page_url_broucher; ?>" target="_blank" rel="nofollow" href="#" class="bruchure-btn single-brochure-view-button" data-term-url="<?php echo $brochure; ?>" data-term-id="<?php echo get_the_ID(); ?>">
                                                    Brochure
                                                </a>
                                               
                                            </div>
                                        </div>
                                    </div>
                                <?php }

                                // For the second and third images
                                if ($counter >= 1 && $counter <= 2) {
                                    // If it's the second image, open the div
                                    if ($counter == 1) { ?>
                                        <div class="second-image">
                                        <?php }

                                    // For second and third images
                                    echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '" />';

                                    // If it's the third image, close the div
                                    if ($counter == 2) {
                                        echo '</div>';
                                    }
                                }

                                $counter++; // Increment counter
                            }
                        }
                        ?>
                        </div>
                    </div>
                </div>

                <div class="lightbox" style="display: none;">
                        <!-- <span class="close-icon">Close</span> -->
                        <span class="back-property"><img src="/wp-content/uploads/2024/01/Vector-4.svg"> Back to property</span>
                        <div class="slider-popup-image property-slider-popup-image">
                            <?php
                            $properties_gallery = get_field('properties_gallery');
                            foreach ($properties_gallery as $image): ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            <?php endforeach; ?>
                        </div>
                        <div class="property-slider-thumbs">
                        <?php
                            $properties_gallery = get_field('properties_gallery');
                            foreach ($properties_gallery as $image): ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                            <?php endforeach; ?>
                        </div>
                </div>

                <div class="container-set">
                    <div class="flex-row">
                        <div class="left-part-content col-lg-7">
                            <div class="inner-content-main">
                                <?php
                                if ($location) { ?>
                                    <p class="location-map"><img src="<?php echo $location_icon; ?>">
                                        <?php echo $location; ?>
                                    </p>
                                <?php } ?>
                                <h3>
                                    <?php the_title(); ?>
                                </h3>
                                <?php $reserved = get_field('reserved'); ?>
                                <?php if ($reserved && in_array('yes', $reserved)) {
                                    echo '<h5 class="reserved">Reserved</h5>';
                                } else{ ?>
                                <h5>£
                                    <?php
                                    if (strpos($price, ',') !== false) {
                                        echo $price;
                                        
                                    } else{
                                        $formatted_price = number_format($price);
                                        echo $formatted_price;
                                    }
                                } ?>
                                </h5>
                               
                                <div class="content">
                                    <?php the_content(); ?>
                                </div>
                                <div class="services">
                                    <?php if ($bed) { ?>
                                        <p><img src="<?php echo $bed_icon; ?>">
                                            <?php echo $bed; ?> Bedroom(s)
                                        </p>
                                    <?php } ?>
                                    <?php if ($properties_type) { ?>
                                        <p><img src="<?php echo $properties_type_icon; ?>">
                                            <?php echo $properties_type; ?>
                                        </p>
                                    <?php } ?>
                                    <?php if ($bathroom) { ?>
                                        <p><img src="<?php echo $bathroom_icon; ?>">
                                            <?php echo $bathroom; ?> Bathroom(s)
                                        </p>
                                    <?php } ?>
                                    <?php if ($properties_sq_ft) { ?>
                                        <p><img src="<?php echo $properties_ft_icon; ?>">
                                            <?php echo $properties_sq_ft; ?> sq ft
                                        </p>
                                    <?php } ?>
                                    
                                    <?php if ($properties_sale_text) { ?>
                                        <p><img src="<?php echo $properties_sale_icon; ?>">
                                            <?php echo $properties_sale_text; ?>
                                        </p>
                                    <?php } ?>
                                    <?php if ($dormer_bungalow_text) { ?>
                                        <p><img src="<?php echo $dormer_bungalow_icon; ?>">
                                            <?php echo $dormer_bungalow_text; ?>
                                        </p>
                                    <?php } ?>
                                </div>
                                <?php $properties_extra_info = get_field('properties_extra_info'); 
                                    if($properties_extra_info){ ?>
                                <div class="property-extra">
                                    <span><?php echo $properties_extra_info; ?></span>
                                </div>
                                <?php } ?>
                            </div>

                        </div>

                    </div>
                </div>
        </section>
        <section class="properties-page-contnet">

            <div class="container-set">
                <div class="flex-row top-row">
                    <div class="left-part-content col-lg-7">
                        <div class="inner-content-main">


                            <?php $feature_short_description = get_field('feature_short_description');
                            $feature_title = get_field('feature_title');
                            if ($feature_title) {
                                ?>
                                <div class="inner-content-features">
                                    <div class="feature">
                                        <h5>
                                            <?php echo $feature_title; ?>
                                        </h5>
                                        <?php
                                        if ($feature_short_description) { ?>
                                            <p>
                                                <?php echo $feature_short_description; ?>
                                            </p>
                                            <?php
                                        }
                                        ?>
                                        <?php if (have_rows('feature')): ?>
                                            <ul class="feature-list">
                                                <?php while (have_rows('feature')):
                                                    the_row();
                                                    $feature_list = get_sub_field('feature_list');
                                                    ?>
                                                    <li>
                                                        <p>
                                                            <?php echo $feature_list; ?>
                                                        </p>
                                                    </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php } ?>



                            <?php $about_property = get_field('about_property');
                            $about_property_title = get_field('about_property_title');
                            if ($about_property_title) {
                                ?>
                                <div class="about-property mt-5">
                                    <h5>
                                        <?php echo $about_property_title; ?>
                                    </h5>
                                    <?php


                                    if ($about_property) {
                                        echo $about_property;
                                    }
                                    ?>
                                </div>
                            <?php } ?>

                            <?php
                            $download_title = get_field('download_title');
                            if (have_rows('property_download')): ?>
                                <div class="property-download mt-5">
                                    <h5>
                                        <?php echo $download_title; ?>
                                    </h5>
                                    <div class="download-listing">
                                        <?php while (have_rows('property_download')):
                                            the_row();
                                            $property_download_description_icon = get_sub_field('property_download_description_icon');
                                            $property_download_title = get_sub_field('property_download_title');
                                            $property_download_file = get_sub_field('property_download_file');
                                             
                                            $download_popup = get_sub_field('download_popup');
                                            
                                            if($download_popup == 'yes' ) {
                                                $contact_page_url_broucher = add_query_arg(
                                                    array(
                                                        'propertiesvillage' => urlencode($location),
                                                        'property' => urlencode($post_title_arrange),
                                                        'contactType' => 'request-brochure',
                                                        
                                                    ),
                                                    get_permalink(get_page_by_path('contact-us'))
                                                );
                                                ?>
                                                <a target="_blank" href="#" data-term-redirectproperty="<?php echo $contact_page_url_broucher; ?>" data-term-url="<?php echo $property_download_file['url']; ?>" data-term-id="<?php echo get_the_ID(); ?>"
                                                class="download-link single-brochure-view-button">
                                                <div class="download">
                                                    <div class="download-image">
                                                        <img src="<?php echo $property_download_description_icon; ?>">
                                                        <p>
                                                            <?php echo $property_download_title; ?>
                                                        </p>
                                                    </div>
                                                    <div class="download-arrow">
                                                        <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                                    </div>
                                                </div>
                                            </a>
                                                <?php 
                                            } else if($download_popup == 'no') { ?>
                                               <a target="_blank" href="<?php echo $property_download_file['url']; ?>"
                                                class="download-link">
                                                <div class="download">
                                                    <div class="download-image">
                                                        <img src="<?php echo $property_download_description_icon; ?>">
                                                        <p>
                                                            <?php echo $property_download_title; ?>
                                                        </p>
                                                    </div>
                                                    <div class="download-arrow">
                                                        <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                                    </div>
                                                </div>
                                            </a>

                                          <?php }
                                            
                                            ?>
                                         
                                        <?php endwhile; ?>
                                    </div>

                                </div>
                            <?php endif; ?>


                            <?php $accordion_sections = get_field('property_specifications');
                            $specification_title = get_field('specification_title');
                            if ($specification_title) {
                                ?>
                                <div class="property-download mt-5">
                                    <h5>
                                        <?php echo $specification_title; ?>
                                    </h5>
                                    <?php
                                    if ($accordion_sections):
                                        ?>
                                        <div class="accordion">
                                            <?php foreach ($accordion_sections as $section): ?>
                                                <div class="accordion-section">
                                                    <div class="accordion-header">
                                                        <?php echo esc_html($section['property_specifications_text']); ?> <span
                                                            class="toggle-icon">+</span>
                                                    </div>
                                                    <div class="accordion-content">
                                                        <?php echo wp_kses_post($section['property_specifications_description']); ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php } ?>
                            <?php $homewood_text = get_field('homewood_text');
                            $life_title = get_field('life_title');
                            if ($homewood_text) {
                                ?>
                                <div class="home-wood mt-5">
                                    <h5>
                                        <?php echo $life_title; ?>
                                    </h5>
                                    <?php

                                    if ($homewood_text) {
                                        echo $homewood_text;
                                    }
                                    ?>
                                </div>
                            <?php } ?>

                            <?php
                            $homewood_title = get_field('homewood_title');
                            if (have_rows('homewood_groove_information')): ?>
                                <div class="property-download mt-5">
                                    <h5>
                                        <?php echo $homewood_title; ?>
                                    </h5>
                                    <div class="download-listing">


                                        <?php while (have_rows('homewood_groove_information')):
                                            the_row();
                                            $homewood_groove_icon = get_sub_field('homewood_groove_icon');
                                            $homewood_groove_title = get_sub_field('homewood_groove_title');
                                            $homewood_groove_download_file = get_sub_field('homewood_groove_download_file');
                                            $homewood_link = get_sub_field('homewood_link');
                                            ?>
                                           <?php if($homewood_link) { ?>
                                            <a target="_blank" href="<?php echo $homewood_link; ?>"
                                                class="download-link">
                                                <div class="download">
                                                    <div class="download-image">
                                                        <img src="<?php echo $homewood_groove_icon; ?>">
                                                        <p>
                                                            <?php echo $homewood_groove_title; ?>
                                                        </p>
                                                    </div>
                                                    <div class="download-arrow">
                                                        <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                                    </div>
                                                </div>
                                            </a>
                                            <?php } else { ?>
                                                
                                            <div class="download-link">
                                                <div class="download">
                                                    <div class="download-image">
                                                        <img src="<?php echo $homewood_groove_icon; ?>">
                                                        <p>
                                                            <?php echo $homewood_groove_title; ?>
                                                        </p>
                                                    </div>
                                                    <div class="download-arrow">
                                                        <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                                    </div>
                                                </div>
                                            </div>
                                           <?php } ?>

                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>
                    </div>
                    <div class="right-section  col-lg-5">
                        <div class="make-inquiry">
                            <?php
                            $inquiry_description = get_field('inquiry_description');
                            $opening_time = get_field('opening_time');
                            $phone = get_field('phone');
                            $email_us = get_field('email_us');
                            $download_brochure = get_field('download_brochure');
                            $make_enquiry = get_field('make_enquiry');
                            ?>
                            <h4>Make an enquiry</h4>
                            <?php echo $inquiry_description; ?>
                            <p class="time">
                                <?php echo $opening_time; ?>
                            </p>
                            <p class="number"><a href="tel:<?php echo $phone; ?>">
                                    <?php echo $phone; ?>
                                </a>
                            </p>
                            <div class="download-list">
                                <?php $email_show = get_field('email_show');
                                if ($email_show == 'yes') {
                                    ?>
                                    <div class="download">
                                        <div class="download-image">
                                            <a href="mailto:<?php echo $email_us; ?>">Email Us</a>
                                        </div>

                                        <div class="download-arrow">
                                            <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php $make_enquiry_show = get_field('make_enquiry_show');
                                if ($make_enquiry_show == 'yes') {
                                    ?>
                                    <div class="download">
                                        <div class="download-image">
                                            <?php 
                                                $post_id_arrange = get_the_ID();
                                                $post_title_arrange = get_the_title($post_id_arrange);
                                                $post_slug = get_post_field('post_name', $post_id_arrange);
                                                
                                                // URL to the contact page with query parameters for the post ID and type
                                                $contact_page_url_arrange = add_query_arg(
                                                    array(
                                                        'propertiesvillage' => urlencode($location),
                                                        'property' => urlencode($post_title_arrange),
                                                        'contactType' => 'make-enquiry',
                                                    ),
                                                    get_permalink(get_page_by_path('contact-us'))
                                                );
                                            ?>
                                            <a href="<?php echo $contact_page_url_arrange; ?>">Make Enquiry</a>
                                        </div>

                                        <div class="download-arrow">
                                            <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php $download_brochure_show = get_field('download_brochure_show');
                              
                                if ($download_brochure_show == 'yes') {
                                    // print_r($brochure);
                                    ?>
                                    <div class="download">
                                        <?php //$brochure = get_sub_field('brochure'); 
                                           $contact_page_url_broucher = add_query_arg(
                                            array(
                                                'propertiesvillage' => urlencode($location),
                                                'property' => urlencode($post_title_arrange),
                                                'contactType' => 'request-brochure',
                                                
                                            ),
                                            get_permalink(get_page_by_path('contact-us'))
                                        );
                                        ?>
                                        <div class="download-image">
                                            <!-- <a rel="nofollow" class="brochure-view-button" target="_blank" href="<?php echo $brochure;  ?>">Download brochure</a> -->
                                            <a rel="nofollow" class="single-brochure-view-button" target="_blank" href="#" data-term-redirectproperty="<?php echo $contact_page_url_broucher; ?>" data-term-url="<?php echo $brochure; ?>" data-term-id="<?php echo get_the_ID(); ?>">Download Brochure</a>
                                        </div>
                                        <?php
                                        //   echo '<script>';
                                        //   echo 'jQuery(document).ready(function($) {';
                                        //   echo '$(".single-brochure-view-button").on("click", function() {';
                                        //   echo 'window.lastClickedBrochureButton = $(this);'; // Store reference to last clicked brochure button.
                                        //   echo '});';
                                        //   echo '});';
                                        //   echo '</script>';
                                        ?>

                                        <div class="download-arrow">
                                            <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                $download_listing_rows = get_field('homewood_groove_information');
                                if (!empty($download_listing_rows) && is_array($download_listing_rows)) {
                                    $first_download_listing = $download_listing_rows[0];
                                    $homewood_link = isset($first_download_listing['homewood_link']) ? $first_download_listing['homewood_link'] : '';
                                    $homewood_groove_title = isset($first_download_listing['homewood_groove_title']) ? $first_download_listing['homewood_groove_title'] : '';
                                    $fees_included_url = '';
                                    $fees_included_target = '';

                                    if (is_array($homewood_link)) {
                                        $fees_included_url = isset($homewood_link['url']) ? $homewood_link['url'] : '';
                                        $fees_included_target = isset($homewood_link['target']) ? $homewood_link['target'] : '';
                                    } else {
                                        $fees_included_url = $homewood_link;
                                    }

                                    if ($fees_included_url && $homewood_groove_title) {
                                        ?>
                                    <div class="download">
                                        <div class="download-image">
                                            <a href="<?php echo esc_url($fees_included_url); ?>"<?php echo $fees_included_target ? ' target="' . esc_attr($fees_included_target) . '"' : ' target="_blank"'; ?>><?php echo esc_html($homewood_groove_title); ?></a>
                                        </div>

                                        <div class="download-arrow">
                                            <img src="/wp-content/uploads/2024/01/Vector-3.png">
                                        </div>
                                    </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php echo do_shortcode('[elementor-template id="2987"]'); ?>

        <?php echo do_shortcode('[elementor-template id="19543"]'); ?>

        <?php
        echo '<div class="strawberry-field-section">';
        $main_section_title = get_field('main_section_title');
        $gallery_images = get_field('gallery_images');
        $view_gallery_button = get_field('view_gallery_button');
        $button_link = get_field('button_link');
        
        echo '<h2 class="top-main-title">' . $main_section_title . '</h2>';

        echo '<div class="strawberry-field-gallery">';
        echo '<div class="custom-slider-park">';
        $gallery_images = get_field('gallery_images');
        foreach ($gallery_images as $image_gallery):
            echo '<img src="' . esc_url($image_gallery['url']) . '"
                                alt="' . esc_attr($image_gallery['alt']) . '" />';
        endforeach;

        echo '</div>';
        echo '</div>';
        echo '<div class="row-btn">';
        echo '<a class="elementor-button elementor-button-link elementor-size-sm
        " href="' . $button_link . '" class="gallery-btn">' . $view_gallery_button . '</a>';

        echo '</div>';
        echo '</div>';
        ?>




        
        <?php echo do_shortcode('[elementor-template id="2971"]'); ?>
        <?php echo do_shortcode('[elementor-template id="864"]'); ?>

    </main>


    <?php
endwhile;
?>
<script>
    jQuery(window).on('load', function(){
        jQuery(".property-slider-popup-image").slick({
            // Slick slider settings go here
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 6000,
            arrows: true,
            prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2024/01/left-slide.svg'>",
            nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2024/01/right-slide.svg'>",
            pauseOnHover:true,
        });

        jQuery(".property-slider-thumbs").slick({
            // Slick slider settings go here
            slidesToShow: 6,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 6000,
            arrows: true,
            pauseOnHover:true,
            prevArrow: "<img class='a-left control-c prev slick-prev' src='/wp-content/uploads/2024/01/left-slide.svg'>",
            nextArrow: "<img class='a-right control-c next slick-next' src='/wp-content/uploads/2024/01/right-slide.svg'>",
            asNavFor: '.property-slider-popup-image',
            focusOnSelect: true,
            responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 5
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 2
                }
            }
        ]
        });
        
        jQuery('.open-lightbox').on('click', function(){
            setTimeout(function(){
                jQuery(".property-slider-thumbs").slick('refresh');
                jQuery(".property-slider-popup-image").slick('refresh');
            }, 500);
        })


        jQuery(".property-slider-popup-image").on('afterChange', function(event, slick, currentSlide){
            jQuery(".property-slider-thumbs .slick-slide").removeClass('slick-current');
            jQuery(".property-slider-thumbs .slick-slide").eq(currentSlide).addClass('slick-current');  
        });

        // jQuery(".property-slider-thumbs").on('afterChange', function(event, slick, currentSlide){
        //     jQuery(".property-slider-popup-image").slick('slickGoTo', parseInt(currentSlide));
        // });
    })
</script>
<?php
get_footer();
