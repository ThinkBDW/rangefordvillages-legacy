<?php
/**
 * Shortcode: hero slider
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 397-515). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 397-515: [hero_slider_shortcode] ---

function custom_html_shortcode()
{
    // Set up query parameters
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'feature_update',
                'value' => 'featureupdate',
                'compare' => 'LIKE',
            ),
        ),
    );

    // Execute the query
    $query = new WP_Query($args);

    // Start output buffering
    ob_start(); ?>

    <section class="slider-wrapper home-hero-slider">
        <div class="slider">

            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();

                    $event_background_image = get_field("event_background_image");
                    $slide_subtitle = get_field("slide_subtitle");
                    $feature_slider_content = get_field("feature_slider_content");
                    $link_button_url = get_field('link_button_url');

                    // Handle image (array or string)
                    $bg_url = '';
                    if (is_array($event_background_image)) {
                        $bg_url = $event_background_image['url'] ?? '';
                    } elseif (is_string($event_background_image)) {
                        $bg_url = $event_background_image;
                    }

                    // Handle link field (array or string)
                    $link_url = '';
                    $link_title = '';

                    if (is_array($link_button_url)) {
                        $link_url = $link_button_url['url'] ?? '';
                        $link_title = $link_button_url['title'] ?? '';
                    } elseif (is_string($link_button_url)) {
                        $link_url = $link_button_url;
                    }
                    ?>

                    <div>
                        <div class="section-story" style="background-image:url(<?php echo esc_url($bg_url); ?>);">
                            <div class="e-con e-flex">
                                <div class="e-con-inner">
                                    <div class="top-content">
                                        <h6>
                                            <?php echo esc_html__('Featured updates', 'text-domain'); ?>
                                        </h6>
                                        <h2>
                                            <?php echo esc_html(wp_trim_words(get_the_title(), 7, '...')); ?>
                                        </h2>
                                    </div>

                                    <div class="bottom-section">
                                        <?php if ($slide_subtitle) : ?>
                                            <h4>
                                                <?php echo esc_html(wp_trim_words($slide_subtitle, 7, '...')); ?>
                                            </h4>
                                        <?php endif; ?>

                                        <?php if ($feature_slider_content) :
                                            echo wp_kses_post($feature_slider_content);
                                        endif; ?>

                                        <?php if ($link_url) : ?>
                                            <a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo esc_url($link_url); ?>">
                                                <span class="elementor-button-content-wrapper">
                                                    <span class="elementor-button-text">
                                                        <?php echo $link_title ? esc_html($link_title) : 'Read more'; ?>
                                                    </span>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo esc_html__('No featured updates found.', 'text-domain');
            endif;
            ?>

        </div>

        <div class="e-con e-flex slider-bar">
            <div class="e-con-inner">
                <div class="slider-progress">
                    <div class="progress"></div>
                </div>
            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

add_shortcode('hero_slider_shortcode', 'custom_html_shortcode');

