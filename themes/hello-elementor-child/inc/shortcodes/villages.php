<?php
/**
 * Shortcodes: village contact CTAs
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [village_contact], [arrange_contact], [request_contact], [village_form_contact] ---
/*Village contact page link */
add_shortcode('village_contact', 'vilages_conatct_link');
function vilages_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id = get_the_ID();
    // Get the post title using the post ID
    $post_title = get_the_title($post_id);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url = add_query_arg(
        array(
            'village' => urlencode($post_title),
            'type' => 'book-appointment'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    // Output the button
    echo '<a href="' . esc_url($contact_page_url) . '" class="btn btn-primary book-appointment">Book Appointment</a>';
    return ob_get_clean();
}
/*Arrange a visit contact page link */
add_shortcode('arrange_contact', 'arrange_conatct_link');
function arrange_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id_arrange = get_the_ID();
    $post_title_arrange = get_the_title($post_id_arrange);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url_arrange = add_query_arg(
        array(
            'village' => urlencode($post_title_arrange),
            'type' => 'arrange-a-visit'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );
    // Output the button
    echo '<a class="book-appointment" href="' . esc_url($contact_page_url_arrange) . '" class="btn btn-primary">Arrange A Visit</a>';
    return ob_get_clean();
}

/*Request a call back page link */
add_shortcode('request_contact', 'request_conatct_link');
function request_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id_reques = get_the_ID();
    $post_title_reques = get_the_title($post_id_reques);

    $contact_page_url_reques = add_query_arg(
        array(
            'village' => urlencode($post_title_reques),
            'type' => 'request-a-call-back'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    // Output the button
    echo '<a class="book-appointment" href="' . esc_url($contact_page_url_reques) . '" class="btn btn-primary">Request A Call Back</a>';
    return ob_get_clean();
}

add_shortcode('village_form_contact', function(){
    ob_start();
    ?>
    <a class="book-appointment btn btn-primary" href="#" onclick="event.preventDefault(); document.querySelector('.village-contact-form').scrollIntoView({ behavior: 'smooth' })">Get in touch</a>
    <?php
    return ob_get_clean();
});


// --- [village_thank] ---
add_shortcode('village_thank', 'vilages_thank_link');

function vilages_thank_link()
{
    ob_start();
    if (isset($_GET['village'])) {
        $ref_post_title = sanitize_text_field($_GET['village']);
        // Look up the village by title. Previously get_page_by_title() (deprecated
        // in WP 6.2) with an unguarded ->ID, so any ?village= value that did not
        // match a village title was a fatal on PHP 8.
        $ref_post = rv_get_village_by_title($ref_post_title);
        $post_id_village = $ref_post ? $ref_post->ID : 0;

        ?>
        <input type="hidden" name="referred_post_id" value="<?php echo $post_id_village; ?>">
        <?php $appointment_detail = get_field('thank_you_page_url', $post_id_village); ?>
        <input type="hidden" name="referred_post_id_new" value="<?php echo $appointment_detail; ?>">
        <?php
    }
    return ob_get_clean();
}

