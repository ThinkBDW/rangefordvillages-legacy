<?php
/**
 * Property wishlist ("Save Property")
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- rv_wishlist_ids_from_request(), [whishlist_count] ---

/**
 * Read the visitor's wishlist out of the current AJAX request.
 *
 * The wishlist lives in the browser, in localStorage under "wishlistData".
 *
 * It used to live in two places at once. js/custom.js wrote localStorage to
 * drive the filled/unfilled "Save Property" icons, while handle_wishlist()
 * kept a parallel copy in $_SESSION that the header count and the "Saved
 * Properties" filter read from. The two copies had different lifetimes --
 * localStorage three months, the PHP session only until the browser closed or
 * session.gc_maxlifetime (24 minutes by default) collected it -- so they drifted
 * apart routinely. Reproduced 2026-09-02: drop PHPSESSID and the card still
 * reads "Remove Property" while the header count reads 0 and the Saved
 * Properties filter returns nothing.
 *
 * localStorage is the copy that actually survives, so it is now the only one.
 * The count is rendered client-side and the filter takes the list in the
 * request. Removing the session also removes a Set-Cookie: PHPSESSID from every
 * front-end response, and a per-visitor number out of server-rendered HTML --
 * both of which would have made the site uncacheable on SiteGround, or worse,
 * shown one visitor another's count out of the page cache.
 *
 * @return int[] Property IDs, deduplicated. Empty if none were sent.
 */
function rv_wishlist_ids_from_request() {
	if ( empty( $_POST['wishlist'] ) || ! is_array( $_POST['wishlist'] ) ) {
		return array();
	}

	$ids = array_map( 'absint', wp_unslash( $_POST['wishlist'] ) );

	return array_values( array_unique( array_filter( $ids ) ) );
}

/*Whishlist count shortcode  */
// Renders 0; js/custom.js fills it in from localStorage on DOM ready. The
// number cannot be rendered here any more -- see rv_wishlist_ids_from_request().
add_shortcode("whishlist_count", "whishlist_count_number");
function whishlist_count_number()
{ ?>
    <div class="wishlist-count-container">
        <span style="color:#fff;" class="wishlist-count">0</span>
    </div>
<?php }
