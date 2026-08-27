<?php
/**
 * The template for displaying the header
 *
 * This is the template that displays all of the <head> section, opens the <body> tag and adds the site's header.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
$enable_skip_link = apply_filters( 'hello_elementor_enable_skip_link', true );
$skip_link_url = apply_filters( 'hello_elementor_skip_link_url', '#content' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	<script src="https://c4b.online/embed/c4b-embed.js.php?licence=5d3776d9-cf7b-41e1-8c77-6f5d51537ef7" defer></script>
	<!-- Header Menu Hover overlay script Start -->
	<script>
		jQuery(document).ready(function ($) {
				jQuery(document).ready(function ($) {
					jQuery("#mega-menu-menu-1>li.mega-menu-item-has-children").on({
						mouseenter: function () {
							jQuery(this).addClass('mega-toggle-on');
							jQuery('body').addClass('menu-open-overlay');
					},
					mouseleave: function () {
						jQuery(this).removeClass('mega-toggle-on');
                     // Check if any other menu item is still hovered
				        if (!jQuery('#mega-menu-menu-1>li').hasClass('mega-toggle-on')) {
				            jQuery('body').removeClass('menu-open-overlay');
			        }
				}
			});
		});
	});
</script>
<!-- Header Menu Hover overlay script End -->
</head>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<a class="skip-link screen-reader-text" href="<?php echo esc_url( $skip_link_url ); ?>"><?php echo esc_html__( 'Skip to content', 'hello-elementor' ); ?></a>
<?php } ?>

<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) {
	if ( did_action( 'elementor/loaded' ) && hello_header_footer_experiment_active() ) {
		get_template_part( 'template-parts/dynamic-header' );
	} else {
		get_template_part( 'template-parts/header' );
	}
}
