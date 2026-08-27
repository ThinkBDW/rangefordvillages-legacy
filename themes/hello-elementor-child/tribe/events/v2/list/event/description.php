<?php
/**
 * View: List Single Event Description
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/list/event/description.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( empty( (string) $event->excerpt ) ) {
	return;
}
?>
<div class="tribe-events-calendar-list__event-description tribe-common-b2 tribe-common-a11y-hidden">
	<?php if($intro = get_field('event_intro', $event->ID)): ?>
        <p><?= $intro ?></p>
    <?php else: ?>
        <?php echo (string) $event->excerpt; ?>
    <?php endif ?>

    
</div>
<div class="tribe-events-calendar-list__event-description tribe-common-b2 tribe-common-a11y-hidden">
<div class="elementor-button-wrapper">
					<span class="elementor-button elementor-button-link elementor-size-sm" target="_blank" rel="noopener">
						<span class="elementor-button-content-wrapper">
									<span class="elementor-button-text">Learn More</span>
					</span>
    </span>
				</div>
</div>
