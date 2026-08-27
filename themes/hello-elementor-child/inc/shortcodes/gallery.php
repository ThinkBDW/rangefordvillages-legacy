<?php
/**
 * Shortcodes: galleries
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 1377-1473). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 1377-1473: [gallery_box], [village_category] ---

// / Gallery Lightbox
function gallery_light_box()
{
    ob_start();
    ?>
    <div class="properties-content single-gallery-popup">
        <div class="lightbox" style="display: none;">
            <span class="back-property"><img src="/wp-content/uploads/2024/01/Vector-4.svg"> Back to galleries</span>
            <div class="slider-popup-image">
                <?php
                $gallery_single = get_field('gallery_single');
                while (have_rows('gallery_and_video')) : the_row();
                    $image_gallery = get_sub_field('image_gallery');
                    $gallery_video = get_sub_field('gallery_video');
                    ?>
                    <?php if ($image_gallery) { ?>
                        <img src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php }
                    if ($gallery_video) { ?>
                        <video width="100%" height="100%" controls>
                            <source src="<?php echo $gallery_video['url']; ?>" type="video/mp4">
                        </video>
                    <?php  } ?>
                <?php endwhile; ?>
            </div>
            <div class="property-slider-thumbs">
                <?php
                while (have_rows('gallery_and_video')) : the_row(); ?>
                    <?php
                    $image_gallery = get_sub_field('image_gallery');
                    $gallery_video = get_sub_field('gallery_video');
                    ?>
                    <img src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('gallery_box', 'gallery_light_box');


/*Properties list in the villages */
add_shortcode('village_category', 'vilages_category_list');
function vilages_category_list()
{
    ob_start();
    // Fetch all terms for 'gallerytype' taxonomy
    $terms = get_terms(array(
        'taxonomy' => 'gallerytype',
        'hide_empty' => false,
    ));

    // Check if any terms are found
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="container-set">';
        echo '<div class="flex-row">';

        foreach ($terms as $term) {
            // Retrieve the image field from ACF. Replace 'your_image_field_name' with the actual field name.
            $image = get_field('gallery_type_image', $term); // Make sure to adjust with your actual field name. The return type can be an array, URL or ID based on your ACF settings.

            // Check if the image is actually set
            if (!empty($image)) {
                // Use the appropriate array keys if your image field returns an array
                $image_url = isset($image['url']) ? $image['url'] : $image; // If your return type is URL or ID, adjust this line accordingly.

                // Term link (archive page)
                $term_link = get_term_link($term);

                echo '<div class="col-lg-6">';
                echo '<div class="gallery-box">';

                // If image is an array, use $image['sizes']['large'] for example, or $image['url'] if it's a URL return type.
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="gallery-image" />';

                echo '<h4>' . esc_html($term->name) . '</h4>';
                echo '<a class="elementor-button elementor-button-link elementor-size-sm" href="' . esc_url($term_link) . '" rel="nofollow noreferrer noopener">';
                echo '<span class="elementor-button-content-wrapper">';
                echo '<span class="elementor-button-text">Read more</span>';
                echo '</span></a>';

                echo '</div>'; // .gallery-box
                echo '</div>'; // .col-lg-6
            }
        }

        echo '</div>'; // .flex-row
        echo '</div>'; // .container-set
    }

    // Return the buffered content
    return ob_get_clean();
}
