<?php
/**
 * The template for displaying the footer.
 *
 * Contains the body & html closing tags.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-footer' );
	} else {
		get_template_part( 'template-parts/footer' );
	}
}
?>

<?php wp_footer(); ?>

<script>
function toggleTooltip(tooltip) {
    var isActive = tooltip.classList.contains('active');

    // close all tooltips first
    document.querySelectorAll('.tooltip.active').forEach(function(active) {
        active.classList.remove('active');
    });

    // if this one wasn’t active, activate it
    if (!isActive) {
        tooltip.classList.add('active');
    }
}
</script>


</body>
</html>
