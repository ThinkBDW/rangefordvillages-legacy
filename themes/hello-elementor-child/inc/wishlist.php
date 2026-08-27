<?php
/**
 * Property wishlist (session-backed)
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 354-396). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 354-396: session bootstrap, handle_wishlist, whishlist_count ---

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Function to handle adding/removing properties to/from the wishlist
function handle_wishlist()
{
    $property_id = isset($_POST['property_id']) ? $_POST['property_id'] : 0;
    $wishlist = isset($_SESSION['wishlist']) ? $_SESSION['wishlist'] : array();

    // Toggle property in the wishlist
    if (in_array($property_id, $wishlist)) {
        $wishlist = array_diff($wishlist, array($property_id));
    } else {
        $wishlist[] = $property_id;
    }

    $_SESSION['wishlist'] = $wishlist;

    // Return updated wishlist count
    echo count($wishlist);

    wp_die(); // Always include this line to terminate immediately and return a proper response
}

add_action('wp_ajax_handle_wishlist', 'handle_wishlist');
add_action('wp_ajax_nopriv_handle_wishlist', 'handle_wishlist');

/*Whishlist count shortcode  */
add_shortcode("whishlist_count", "whishlist_count_number");
function whishlist_count_number()
{ ?>
    <div class="wishlist-count-container">
        <span style="color:#fff;" class="wishlist-count">
            <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
        </span>
    </div>
<?php }


