<?php
/**
 * View: List View - Single Event Date
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/list/event/date.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @since 4.9.9
 * @since 5.1.1 Move icons into separate templates.
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 * @version 5.1.1
 */
use Tribe__Date_Utils as Dates;

$event_date_attr = $event->dates->start->format( Dates::DBDATEFORMAT );

?>
<div class="tribe-events-calendar-list__event-datetime-wrapper tribe-common-b2">
	<?php $this->template( 'list/event/date/featured' ); ?>
	<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
	</svg>

	<time class="tribe-events-calendar-list__event-datetime" datetime="<?php echo esc_attr( $event_date_attr ); ?>">
		<?php // echo $event->schedule_details->value(); ?>
		<?php 
			$date_format = 'F j';  // e.g., "February 28"
			$time_format = 'g:ia'; // e.g., "08:00am"

			// Get start and end date/time
			$start_date = $event->dates->start->format( $date_format );
			$start_time = $event->dates->start->format( $time_format );
			$end_date = $event->dates->end->format( $date_format );
			$end_time = $event->dates->end->format( $time_format );

			// Check if it's a multi-day event
			if ($start_date === $end_date) {
				// Single-day event format
				?>
				<span class="tribe-event-date-start"><?php echo esc_html( $start_date . ' @ ' . $start_time ); ?></span> - 
				<span class="tribe-event-time"><?php echo esc_html( $end_time ); ?></span>
				<?php
			} else {
				// Multi-day event format
				?>
				<span class="tribe-event-date-start"><?php echo esc_html( $start_date . ' @ ' . $start_time ); ?></span> - 
				<span class="tribe-event-date-end"><?php echo esc_html( $end_date . ' @ ' . $end_time ); ?></span>
				<?php
			}
		?>
	</time>
	<?php $this->template( 'list/event/date/meta', [ 'event' => $event ] ); ?>
</div>
