<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @link http://evnt.is/1aiy
 *
 * @package TribeEventsCalendar
 *
 * @version 4.6.19
 */


$event_id             = Tribe__Main::post_id_helper();
// $time_format          = get_option( 'time_format', Tribe__Date_Utils::TIMEFORMAT );
// $time_format          = get_option( 'time_format', 'g:ia' );
$time_format          = 'g:ia';
$time_range_separator = tec_events_get_time_range_separator();
$show_time_zone       = tribe_get_option( 'tribe_events_timezones_show_zone', false );
$local_start_time     = tribe_get_start_date( $event_id, true, Tribe__Date_Utils::DBDATETIMEFORMAT );
$time_zone_label      = Tribe__Events__Timezones::is_mode( 'site' ) ? Tribe__Events__Timezones::wp_timezone_abbr( $local_start_time ) : Tribe__Events__Timezones::get_event_timezone_abbr( $event_id );

// $start_datetime = tribe_get_start_date();
$start_datetime = tribe_get_start_date( null, false, 'F j, Y @ ' . $time_format );
$start_date = tribe_get_start_date( null, false ,'F j, Y');
$start_time = tribe_get_start_date( null, false, $time_format );
$start_ts = tribe_get_start_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

// $end_datetime = tribe_get_end_date();
$end_datetime = tribe_get_end_date( null, false, 'F j, Y @ ' . $time_format ); 
$end_date = tribe_get_display_end_date( null, false,'F j, Y' );
$end_time = tribe_get_end_date( null, false, $time_format );
$end_ts = tribe_get_end_date( null, false, Tribe__Date_Utils::DBDATEFORMAT );

$time_formatted = null;
if ( $start_time == $end_time ) {
	$time_formatted = esc_html( $start_time );
} else {
	$time_formatted = esc_html( $start_time . $time_range_separator . $end_time );
}

/**
 * Returns a formatted time for a single event
 *
 * @var string Formatted time string
 * @var int Event post id
 */
$time_formatted = apply_filters( 'tribe_events_single_event_time_formatted', $time_formatted, $event_id );

/**
 * Returns the title of the "Time" section of event details
 *
 * @var string Time title
 * @var int Event post id
 */
$time_title = apply_filters( 'tribe_events_single_event_time_title', __( 'Time:', 'the-events-calendar' ), $event_id );

$cost    = tribe_get_formatted_cost();
$website = tribe_get_event_website_link( $event_id );
$website_title = tribe_events_get_event_website_title();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<h2 class="tribe-events-single-section-title"> <?php esc_html_e( 'Details', 'the-events-calendar' ); ?> </h2>
	<dl>

		<?php
		do_action( 'tribe_events_single_meta_details_section_start' );

		// All day (multiday) events
		if ( tribe_event_is_all_day() && tribe_event_is_multiday() ) :
			?>

			<dt class="tribe-events-start-date-label"> <?php esc_html_e( 'Start:', 'the-events-calendar' ); ?> </dt>
			<dd>
				<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
				</svg>
				<span>
					<abbr class="tribe-events-abbr tribe-events-start-date published dtstart" title="<?php echo esc_attr( $start_ts ); ?>"> <?php echo esc_html( $start_date ); ?> </abbr> — <abbr class="tribe-events-abbr tribe-events-end-date dtend" title="<?php echo esc_attr( $end_ts ); ?>"> <?php echo esc_html( $end_date ); ?> </abbr>
				</span>
			</dd>

		<?php
		// All day (single day) events
		elseif ( tribe_event_is_all_day() ):
			?>

			<dt class="tribe-events-start-date-label"> <?php esc_html_e( 'Date:', 'the-events-calendar' ); ?> </dt>
			<dd>
				<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
				</svg> 
				<span>
					<abbr class="tribe-events-abbr tribe-events-start-date published dtstart" title="<?php echo esc_attr( $start_ts ); ?>"> <?php echo esc_html( $start_date ); ?> </abbr>
				</span>
			</dd>

		<?php
		// Multiday events
		elseif ( tribe_event_is_multiday() ) :
			?>

			<dt class="tribe-events-start-datetime-label"> <?php esc_html_e( 'Start:', 'the-events-calendar' ); ?> </dt>
			<dd>
				<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
				</svg>
				<span>
				<abbr class="tribe-events-abbr tribe-events-start-datetime updated published dtstart" title="<?php echo esc_attr( $start_ts ); ?>"> <?php echo esc_html( $start_datetime ); ?> </abbr>
				<?php if ( $show_time_zone ) : ?>
					<span class="tribe-events-abbr tribe-events-time-zone published "><?php echo esc_html( $time_zone_label ); ?></span>
				<?php endif; ?> — <abbr class="tribe-events-abbr tribe-events-end-datetime dtend" title="<?php echo esc_attr( $end_ts ); ?>"> <?php echo esc_html( $end_datetime ); ?> </abbr>
				<?php if ( $show_time_zone ) : ?>
					<span class="tribe-events-abbr tribe-events-time-zone published "><?php echo esc_html( $time_zone_label ); ?></span>
				<?php endif; ?>
				</span>
			</dd>

		<?php
		// Single day events
		else :
			?>

			<dt class="tribe-events-start-date-label"> <?php esc_html_e( 'Date:', 'the-events-calendar' ); ?> </dt>
			<dd>
				<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
				</svg>
				<span>
					<abbr class="tribe-events-abbr tribe-events-start-date published dtstart" title="<?php echo esc_attr( $start_ts ); ?>"> <?php echo esc_html( $start_date ); ?> </abbr>
				</span>
			</dd>

			<dt class="tribe-events-start-time-label"> <?php echo esc_html( $time_title ); ?> </dt>
			<dd>
				<div class="tribe-events-abbr tribe-events-start-time published dtstart" title="<?php echo esc_attr( $end_ts ); ?>">
					<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 18.5C4.0293 18.5 0 14.4707 0 9.5C0 4.5293 4.0293 0.5 9 0.5C13.9707 0.5 18 4.5293 18 9.5C18 14.4707 13.9707 18.5 9 18.5ZM9 16.7C10.9096 16.7 12.7409 15.9414 14.0912 14.5912C15.4414 13.2409 16.2 11.4096 16.2 9.5C16.2 7.59044 15.4414 5.75909 14.0912 4.40883C12.7409 3.05857 10.9096 2.3 9 2.3C7.09044 2.3 5.25909 3.05857 3.90883 4.40883C2.55857 5.75909 1.8 7.59044 1.8 9.5C1.8 11.4096 2.55857 13.2409 3.90883 14.5912C5.25909 15.9414 7.09044 16.7 9 16.7ZM9.9 9.5H13.5V11.3H8.1V5H9.9V9.5Z" fill="#BAB480"/>
					</svg><span><?php echo $time_formatted; ?></span>
					<?php if ( $show_time_zone ) : ?>
						<span class="tribe-events-abbr tribe-events-time-zone published "><?php echo esc_html( $time_zone_label ); ?></span>
					<?php endif; ?>
				</div>
			</dd>

		<?php endif ?>

		<?php
		/**
		 * Included an action where we inject Series information about the event.
		 *
		 * @since 6.0.0
		 */
		do_action( 'tribe_events_single_meta_details_section_after_datetime' );
		?>

		<?php /*
		// Event Cost
		if ( ! empty( $cost ) ) : ?>

			<dt class="tribe-events-event-cost-label"> <?php esc_html_e( 'Cost:', 'the-events-calendar' ); ?> </dt>
			<dd class="tribe-events-event-cost"> <?php echo esc_html( $cost ); ?> </dd>
		<?php endif */ ?>

		<?php /*
		echo tribe_get_event_categories(
			get_the_id(),
			[
				'before'       => '',
				'sep'          => ', ',
				'after'        => '',
				'label'        => null, // An appropriate plural/singular label will be provided
				'label_before' => '<dt class="tribe-events-event-categories-label">',
				'label_after'  => '</dt>',
				'wrap_before'  => '<dd class="tribe-events-event-categories">',
				'wrap_after'   => '</dd>',
			]
		);
		*/ ?>

		<?php /*
		tribe_meta_event_archive_tags(
			// Translators: %s: Event (singular) 
			sprintf(
				esc_html__( '%s Tags:', 'the-events-calendar' ),
				tribe_get_event_label_singular()
			),
			', ',
			true
		);
		*/ ?>

		<?php /*
		// Event Website
		if ( ! empty( $website ) ) : ?>
			<?php if ( ! empty( $website_title ) ): ?>
				<dt class="tribe-events-event-url-label"> <?php echo esc_html( $website_title ); ?> </dt>
			<?php endif; ?>
			<dd class="tribe-events-event-url"> <?php echo $website; ?> </dd>
		<?php endif */ ?>

		<?php do_action( 'tribe_events_single_meta_details_section_end' ); ?>
	</dl>
</div>
