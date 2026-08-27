<?php
/**
 * Single Event Meta (Organizer) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/organizer.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 */

$organizer_ids = tribe_get_organizer_ids();
$multiple = count( $organizer_ids ) > 1;

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
$website = tribe_get_organizer_website_link();
$website_title = tribe_events_get_organizer_website_title();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
	<h2 class="tribe-events-single-section-title"><?php echo tribe_get_organizer_label( ! $multiple ); ?></h2>
	<dl>
		<?php
		do_action( 'tribe_events_single_meta_organizer_section_start' );

		foreach ( $organizer_ids as $organizer ) {
			if ( ! $organizer ) {
				continue;
			}

			?>
			<dt
				class="tribe-common-a11y-visual-hide"
				aria-label="<?php echo sprintf(
					/* Translators: %1$s is the customizable organizer term, e.g. "Organizer". %2$s is the customizable event term in lowercase, e.g. "event". %3$s is the customizable organizer term in lowercase, e.g. "organizer". */
					esc_html_x( '%1$s name: This represents the name of the %2$s %3$s.', 'the-events-calendar' ),
					tribe_get_organizer_label_singular(),
					tribe_get_event_label_singular_lowercase(),
					tribe_get_organizer_label_singular_lowercase()
				) ; ?>"
			>
				<?php // This element is only present to ensure we have a valid HTML, it'll be hidden from browsers but visible to screenreaders for accessibility. ?>
			</dt>
			<dd class="tribe-organizer">
			<svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M17.295 15.3492V15.35V17.795H0.705V15.35C0.705 14.8406 0.834271 14.3831 1.09323 13.9621L0.49275 13.5927L1.09323 13.9621C1.35558 13.5357 1.69576 13.2183 2.12035 12.9968C3.23608 12.4394 4.36563 12.0234 5.50949 11.7467C6.65523 11.4696 7.81849 11.3307 9.00043 11.33C10.1818 11.3293 11.3445 11.4681 12.4897 11.7465C13.6342 12.0248 14.7643 12.441 15.8805 12.9972C16.3053 13.2179 16.6454 13.5349 16.9077 13.9618C17.1668 14.3836 17.2956 14.841 17.295 15.3492ZM18 15.35V17.795V15.35ZM9 8.795C7.95096 8.795 7.07048 8.42971 6.32039 7.67961C5.57029 6.92952 5.205 6.04904 5.205 5C5.205 3.95096 5.57029 3.07048 6.32039 2.32039C7.07048 1.57029 7.95096 1.205 9 1.205C10.049 1.205 10.9295 1.57029 11.6796 2.32039C12.4297 3.07048 12.795 3.95096 12.795 5C12.795 6.04904 12.4297 6.92952 11.6796 7.67961C10.9295 8.42971 10.049 8.795 9 8.795Z" stroke="#BAB480" stroke-width="1.41"/>
			</svg><span><?php echo tribe_get_organizer_link( $organizer ) ?></span>
			</dd>
			<?php
		}

		if ( ! $multiple ) { // only show organizer details if there is one
			if ( ! empty( $phone ) ) {
				?>
				<dt class="tribe-organizer-tel-label">
					<?php esc_html_e( 'Phone', 'the-events-calendar' ) ?>
				</dt>
				<dd class="tribe-organizer-tel">
				<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M11.1436 13.913C9.14139 12.7349 7.44791 11.0978 6.20281 9.13652L7.93264 7.16909L6.89119 1.63763H1.11769L1.09326 1.93519C1.03196 2.51497 1.00083 3.09755 1 3.68057C1 12.3275 7.70392 19.3623 15.9423 19.3623C16.3864 19.3623 17.1369 19.2957 17.6654 19.2446L17.9119 19.2224L19 13.893L14.2191 11.9389L11.1436 13.913Z" stroke="#BAB480" stroke-width="1.41"/>
				</svg>
				<span><?php echo esc_html( $phone ); ?></span>
				</dd>
				<?php
			}//end if
			/*
			if ( ! empty( $email ) ) {
				?>
				<dt class="tribe-organizer-email-label">
					<?php esc_html_e( 'Email', 'the-events-calendar' ) ?>
				</dt>
				<dd class="tribe-organizer-email">
					<?php echo esc_html( $email ); ?>
				</dd>
				<?php
			}//end if
			
			if ( ! empty( $website ) ) {
				?>
				<?php if ( ! empty( $website_title ) ): ?>
					<dt class="tribe-organizer-url-label">
						<?php echo esc_html( $website_title ) ?>
					</dt>
				<?php else: ?>
					<dt
						class="tribe-common-a11y-visual-hide"
						aria-label="<?php echo sprintf(
							// Translators: %1$s is the customizable organizer term, e.g. "Organizer". %2$s is the customizable event term in lowercase, e.g. "event". %3$s is the customizable organizer term in lowercase, e.g. "organizer". 
							esc_html_x( '%1$s website title: This represents the website title of the %2$s %3$s.', 'the-events-calendar' ),
							tribe_get_organizer_label_singular(),
							tribe_get_event_label_singular_lowercase(),
							tribe_get_organizer_label_singular_lowercase()
						) ; ?>"
					>
						<?php // This element is only present to ensure we have a valid HTML, it'll be hidden from browsers but visible to screenreaders for accessibility. ?>
					</dt>
				<?php endif; ?>
				<dd class="tribe-organizer-url">
					<?php echo $website; ?>
				</dd>
				<?php
			} */
			//end if
		}//end if

		do_action( 'tribe_events_single_meta_organizer_section_end' );
		?>
	</dl>
</div>
