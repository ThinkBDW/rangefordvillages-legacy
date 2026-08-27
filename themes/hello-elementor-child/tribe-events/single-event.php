<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = Tribe__Events__Main::postIdHelper( get_the_ID() );

/**
 * Allows filtering of the event ID.
 *
 * @since 6.0.1
 *
 * @param numeric $event_id
 */
$event_id = apply_filters( 'tec_events_single_event_id', $event_id );

/**
 * Allows filtering of the single event template title classes.
 *
 * @since 5.8.0
 *
 * @param array   $title_classes List of classes to create the class string from.
 * @param numeric $event_id      The ID of the displayed event.
 */
$title_classes = apply_filters( 'tribe_events_single_event_title_classes', [ 'tribe-events-single-event-title' ], $event_id );
$title_classes = implode( ' ', tribe_get_classes( $title_classes ) );

/**
 * Allows filtering of the single event template title before HTML.
 *
 * @since 5.8.0
 *
 * @param string  $before   HTML string to display before the title text.
 * @param numeric $event_id The ID of the displayed event.
 */
$before = apply_filters( 'tribe_events_single_event_title_html_before', '<h1 class="' . $title_classes . '">', $event_id );

/**
 * Allows filtering of the single event template title after HTML.
 *
 * @since 5.8.0
 *
 * @param string  $after    HTML string to display after the title text.
 * @param numeric $event_id The ID of the displayed event.
 */
$after = apply_filters( 'tribe_events_single_event_title_html_after', '</h1>', $event_id );

/**
 * Allows filtering of the single event template title HTML.
 *
 * @since 5.8.0
 *
 * @param string  $after    HTML string to display. Return an empty string to not display the title.
 * @param numeric $event_id The ID of the displayed event.
 */
$title = apply_filters( 'tribe_events_single_event_title_html', the_title( $before, $after, false ), $event_id );
$cost  = tribe_get_formatted_cost( $event_id );

?>

<div id="tribe-events-content" class="tribe-events-single">

	<?php /*
	<p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'the-events-calendar' ), $events_label_plural ); ?></a>
	</p> 
	
	<!-- Notices -->
	<?php tribe_the_notices() ?>

	<?php echo $title; ?>
	

	<div class="tribe-events-schedule tribe-clearfix">
		<?php echo tribe_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); ?>
		<?php if ( ! empty( $cost ) ) : ?>
			<span class="tribe-events-cost"><?php echo esc_html( $cost ) ?></span>
		<?php endif; ?>
	</div>
	*/?>
	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>
			
			<div class="single-event-content-block">
				<div class="single-event-content">
					<!-- Event header -->
					<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
						<!-- Navigation -->
						<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
							<ul class="tribe-events-sub-nav">
								<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
								<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
							</ul>
							<!-- .tribe-events-sub-nav -->
						</nav>
					</div>
					<!-- #tribe-events-header -->
					<?php 
					$venue_id = tribe_get_venue_id(); // Get the venue ID
					$venue_name = get_the_title($venue_id);
					$start_date = tribe_get_start_date( null, false, 'F j' );
					$start_time = tribe_get_start_date( null, false, 'g:ia' );
					$end_time = tribe_get_end_date( null, false, 'g:ia' );
					$words = explode(' ', $venue_name); 
					$short_name = '';

					foreach ($words as $word) {
						$short_name .= mb_substr($word, 0, 1); 
						if (mb_strlen($short_name) >= 2) { 
							break;
						}
					}
					?>
					<div class="single-event-meta">
						<?php if($venue_name){?>
						<div class="single-event-venue-meta">
							<svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.0159 22.9527C10.0152 22.9535 10.0146 22.9542 10.0139 22.9549L9.52675 23.4719C8.30772 22.1799 7.13287 20.8527 6.00394 19.4902L5.25815 18.5634C3.70915 16.597 2.5642 14.8546 1.80891 13.3336C1.05039 11.8061 0.705882 10.5438 0.705882 9.52637C0.705882 4.96656 4.61931 1.20588 9.52941 1.20588C14.4395 1.20588 18.3529 4.96656 18.3529 9.52637C18.3529 10.5438 18.0084 11.8061 17.2499 13.3336C16.4946 14.8546 15.3497 16.597 13.8007 18.5634L13.054 19.4913C12.1555 20.5843 11.1436 21.736 10.0159 22.9527Z" stroke="#BAB480" stroke-width="1.41176" stroke-linecap="round" stroke-linejoin="round"/>
								<text x="10" y="12" font-size="10" fill="#BAB480"  text-anchor="middle" dominant-baseline="middle"><?php echo esc_html($short_name); ?></text>
							</svg>
							<span class="single-event-venue-title"><?php echo get_the_term_list( $event_id, 'tribe_events_cat', '', ', ' ); //echo $venue_name; ?></span>
						</div>
						<?php } ?>
						<?php if($start_date || $start_time || $end_time){?>
						<div class="single-event-datetime-meta">
							<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.30884 0.5V5M12.8088 0.5V5M0.808838 7.25H17.3088M4.55884 10.25V11.75M9.05884 10.25V11.75M13.5588 10.25V11.75M13.5588 13.25V14.75M9.05884 13.25V14.75M4.55884 13.25V14.75M0.808838 2.75H17.3088V17.75H0.808838V2.75Z" stroke="#BAB480" stroke-width="1.5" stroke-linejoin="round"/>
							</svg>
							<span class="single-event-date-time"><?php echo esc_html( $start_date ) ?> @ <?php echo esc_html( $start_time ); ?>  -  <?php echo esc_html( $end_time ); ?></span>
						</div>
						<?php } ?>
					</div>
					<?php echo $title; ?>
					<!-- Event content -->
					<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
					<div class="tribe-events-single-event-description tribe-events-content">
						<?php the_content(); ?>
						<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
					</div>
					<!-- .tribe-events-single-event-description -->
					<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
				</div>
				<div class="single-event-meta-block">
					<!-- Event meta -->
					<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
					<?php tribe_get_template_part( 'modules/meta' ); ?>
				</div>
			</div>
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<?php /*
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	*/ ?>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->
<?php echo do_shortcode('[elementor-template id="22413"]'); ?>
<?php echo do_shortcode('[elementor-template id="21161"]'); ?>

<script>
    console.log(jQuery('input[name="residentContactFirstName"]').length); // no ()
    console.log('event loaded');

    if (jQuery('input[name="event-title"]').length) {
        jQuery('input[name="event-title"]').val(
            jQuery('h1.tribe-events-single-event-title').text()
        );
    }

    if (jQuery('input[name="venue-title"]').length) {
        jQuery('input[name="venue-title"]').val(
            jQuery('.single-event-venue-title').text()
        );
    }

    // Remove extra ) at the end
    // copy entered first/last name into hidden fields
    $('input[name="text-178"]').on('input', function(){
        $('input[name="residentContactFirstName"]').val($(this).val());
    });

    $('input[name="text-370"]').on('input', function(){
        $('input[name="residentContactLastName"]').val($(this).val());
    });
</script>
