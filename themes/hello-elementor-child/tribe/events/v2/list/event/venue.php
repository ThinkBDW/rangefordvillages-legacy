<?php
/**
 * View: List Single Event Venue
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/list/event/venue.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 6.2.0
 * @since 6.2.0 Added the `tec_events_view_venue_after_address` action.
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 * @var string  $slug  The slug of the view.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( ! $event->venues->count() ) {
	return;
}

$separator            = esc_html_x( ', ', 'Address separator', 'the-events-calendar' );
$venue                = $event->venues[0];
$append_after_address = array_filter( array_map( 'trim', [ $venue->state_province, $venue->state, $venue->province ] ) );
$address              = $venue->address . ( $venue->address && ( $append_after_address || $venue->city ) ? $separator : '' );
?>
<address class="tribe-events-calendar-list__event-venue tribe-common-b2">
	<?php
	$venue_name = wp_kses_post( $venue->post_title );
	$words = explode(' ', $venue_name); 
	$short_name = '';

	foreach ($words as $word) {
		$short_name .= mb_substr($word, 0, 1); 
		if (mb_strlen($short_name) >= 2) { 
			break;
		}
	}

	?>

	<svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M10.0159 22.9527C10.0152 22.9535 10.0146 22.9542 10.0139 22.9549L9.52675 23.4719C8.30772 22.1799 7.13287 20.8527 6.00394 19.4902L5.25815 18.5634C3.70915 16.597 2.5642 14.8546 1.80891 13.3336C1.05039 11.8061 0.705882 10.5438 0.705882 9.52637C0.705882 4.96656 4.61931 1.20588 9.52941 1.20588C14.4395 1.20588 18.3529 4.96656 18.3529 9.52637C18.3529 10.5438 18.0084 11.8061 17.2499 13.3336C16.4946 14.8546 15.3497 16.597 13.8007 18.5634L13.054 19.4913C12.1555 20.5843 11.1436 21.736 10.0159 22.9527Z" stroke="#BAB480" stroke-width="1.41176" stroke-linecap="round" stroke-linejoin="round"/>
		<text x="10" y="12" font-size="10" fill="#BAB480"  text-anchor="middle" dominant-baseline="middle"><?php echo esc_html($short_name); ?></text>
	</svg>
	<span class="tribe-events-calendar-list__event-venue-title tribe-common-b2--bold">
		<?php echo wp_kses_post( $venue->post_title ); ?>
	</span>
	<span class="tribe-events-calendar-list__event-venue-address">
		<?php
		echo esc_html( $address );

		if ( ! empty( $venue->city ) ) :
			echo esc_html( $venue->city );
			if ( $append_after_address ) :
				echo $separator;
			endif;
		endif;

		if ( $append_after_address ) :
			echo esc_html( reset( $append_after_address ) );
		endif;

		if ( ! empty( $venue->country ) ):
			echo $separator . esc_html( $venue->country );
		endif;
		?>
	</span>
	<?php
	/**
	 * Fires after the full venue has been displayed.
	 *
	 * @since 6.2.0
	 *
	 * @param WP_Post $event Event post object.
	 * @param string  $slug  Slug of the view.
	 */
	do_action( 'tec_events_view_venue_after_address', $event, $slug );
	?>
</address>
