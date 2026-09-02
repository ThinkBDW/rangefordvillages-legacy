<?php
/**
 * Shortcodes: brochures
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [broucher_category] ---
/*Broucher category list */
add_shortcode('broucher_category', 'broucher_category_list');
function broucher_category_list()
{
    ob_start();
    $terms_per_page = 6; // Number of terms to display per page
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page number
    $terms = get_terms(array(
        'taxonomy' => 'brochureytype',
        'hide_empty' => false,
        'number' => $terms_per_page,
        'offset' => ($paged - 1) * $terms_per_page // Calculate the offset based on the current page
    ));

    // Check if any terms are found
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="container-set">';
        echo '<div class="flex-row g-15">';

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
                // echo '<a class="elementor-button elementor-button-link elementor-size-sm" href="' . esc_url($term_link) . '" rel="nofollow noreferrer noopener">';
                echo '<a class="elementor-button elementor-button-link elementor-size-sm brochure-view-button" href="#" data-pdf-url="' . esc_attr($term_link) . '" data-term-id="' . esc_attr($term->term_id) . '">';

                echo '<span class="elementor-button-content-wrapper">';
                echo '<span class="elementor-button-text">View Brochure</span>';
                echo '</span></a>';

                echo '</div>'; // .gallery-box
                echo '</div>'; // .col-lg-6
            }
        }

        echo '</div>'; // .flex-row
        echo '</div>'; // .container-set
        // Pagination
        $total_terms = wp_count_terms('brochureytype');
        $total_pages = ceil($total_terms / $terms_per_page);
        $pagination_args = array(
            'base'         => get_pagenum_link(1) . '%_%',
            'format'       => '/page/%#%',
            'total'        => $total_pages,
            'current'      => $paged,
            'prev_text' => __(''),
            'next_text' => __(''),
            // 'type'         => 'list',
        );
        echo '<div class="pagination1">';
        echo paginate_links($pagination_args);
        echo '</div>';
        echo '<script>';
        echo 'jQuery(document).ready(function($) {';
        echo '$(".brochure-view-button").on("click", function() {';
        echo 'window.lastClickedBrochureButton = $(this);'; // Store reference to last clicked brochure button.
        echo '});';
        echo '});';
        echo '</script>';
        ?>

        <?php
    }

    // Return the buffered content
    return ob_get_clean();
}


// --- [brochure_contact], [secondary_brochure_contact] ---
/*Download brochure  page link */
add_shortcode('brochure_contact', 'brochure_conatct_link');
function brochure_conatct_link($atts)
{
    ob_start();

    // Get the current post ID
    $post_id_brochure = get_the_ID();
    if(get_field('villages_download_brochure_type',$post_id_brochure) == "file" ){
        $villages_download_brochure = get_field('villages_download_brochure', $post_id_brochure);
        $url_brochure = $villages_download_brochure['url'];
    }else{
        $url_brochure = get_field('villages_download_brochure_link',$post_id_brochure);
    }

    $post_id = get_the_ID();
    // Get the post title using the post ID
    $post_title = get_the_title($post_id);

    // Shortcode attributes
    $shortcode_atts = shortcode_atts(array(
        'button_text' => 'Download Lifestyle Brochure', // Default button text
        'class'        => 'pum-trigger popmake-10766',
    ), $atts);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url_broucher = add_query_arg(
        array(
            'village' => urlencode($post_title),
            'type' => 'request-brochure'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    ?>
    <?php if ($url_brochure) { ?>
    <a class="book-appointment btn btn-primary single-brochure-view-button <?php echo esc_attr($shortcode_atts['class']); ?>" href="#" data-popmake="10766" data-term-url="<?php echo $url_brochure; ?>" data-term-redirect="<?php echo $contact_page_url_broucher; ?>" data-term-id="<?php echo $post_id_brochure; ?>"><?php echo esc_html($shortcode_atts['button_text']); ?></a>

<?php } ?>
    <!-- <a class="book-appointment btn btn-primary  <?php echo esc_attr($shortcode_atts['class']); ?>" href="<?php echo $url_brochure; ?>"><?php echo esc_html($shortcode_atts['button_text']); ?></a> -->
    <?php
    return ob_get_clean();
}

add_shortcode('secondary_brochure_contact', 'secondary_brochure_contact_link');
function secondary_brochure_contact_link($atts)
{
    ob_start();

    $shortcode_atts = shortcode_atts(array(
        'button_text' => '',
        'class'       => 'pum-trigger popmake-32688',
        'field'       => 'villages_secondary_brochure_link',
        'label_field' => 'villages_secondary_brochure_label',
        'post_id'     => 0,
    ), $atts);

    $post_id_brochure = $shortcode_atts['post_id'] ? intval($shortcode_atts['post_id']) : get_the_ID();
    $url_brochure = get_field($shortcode_atts['field'], $post_id_brochure);

    if (is_array($url_brochure)) {
        $url_brochure = $url_brochure['url'] ?? '';
    }

    $label = get_field($shortcode_atts['label_field'], $post_id_brochure);
    $button_text = $shortcode_atts['button_text'] ?: ($label ?: 'Download Walnut Lane Brochure');

    $contact_page_url_broucher = add_query_arg(
        array(
            'village' => urlencode(get_the_title($post_id_brochure)),
            'type' => 'request-brochure',
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    if ($url_brochure) {
        ?>
        <a class="book-appointment btn btn-primary single-brochure-view-button <?php echo esc_attr($shortcode_atts['class']); ?>" href="#" data-popmake="32688" data-term-url="<?php echo esc_url($url_brochure); ?>" data-term-redirect="<?php echo esc_url($contact_page_url_broucher); ?>" data-term-id="<?php echo esc_attr($post_id_brochure); ?>"><?php echo esc_html($button_text); ?></a>
        <?php
    }

    return ob_get_clean();
}


