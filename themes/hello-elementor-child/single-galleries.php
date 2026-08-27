<?php
/**
 * The template for displaying single posts of post type 'galleries'.
 *
 * @package HelloElementor
 */

get_header();

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<section class="gallery-listing">
<?php // The WordPress Loop: Start
while (have_posts()): the_post(); ?>
  <main id="content" <?php post_class('site-main'); ?>>
<?php
    // Check if the current post is a child (has a parent)
    $parent_id = wp_get_post_parent_id(get_the_ID());
    if($parent_id): 
        // This is a child post, so just display its own title and content
        ?>
        <div class="container-set">
            <!-- Repeater for Video and Image -->
            <div class="gallery-images">
                <?php
                   
                    // Check if there's a parent post ID
                    if ($parent_id != 0) { // Ensure there is a parent ID. If it's 0, there's no parent.
                        $parent_permalink = get_permalink($parent_id); // This gets the URL of the parent post.
                        $parent_title = get_the_title($parent_id);
                        echo '<a class="back-btn" href="'.$parent_permalink.'">Back to all '.$parent_title.' Galleries</a>';
                    } else {
                        // Handle the case when there is no parent.
                        echo "This page has no parent.";
                    }
                ?>
                <h2><?php the_title(); ?></h2>
                <?php if( have_rows('gallery_and_video') ): ?>
                <div class="gallery-item">
                    <div class="gallery-child-main flex-row">
                    <?php while( have_rows('gallery_and_video') ): the_row(); 
                        $image_gallery = get_sub_field('image_gallery');
                        $gallery_video = get_sub_field('gallery_video');
                        $external_video = get_sub_field('external_video');
                        $gallery_child_images = get_sub_field('video_and_image'); 
                        if($image_gallery ||  $gallery_video){
                            if( $gallery_child_images && in_array('image', $gallery_child_images) ) {
                                if($image_gallery){ ?>
                                    <div class="gallery-child-images col-lg-6">
                                        <img class="gallery-items" src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                    </div>
                                <?php } 
                            }
                            if( $gallery_child_images && in_array('video', $gallery_child_images) ) {
                                if($gallery_video){ ?>
                                    <div class="gallery-child-images col-lg-6">
                                        <video class="gallery-items" width="100%" height="100%" controls><source src="<?php echo $gallery_video['url']; ?>" type="video/mp4"></video>
                                    </div>
                                <?php } 
                            }
                        }
                        if($external_video){ 
                            echo '<div class="gallery-child-images col-lg-6">';
                            echo $external_video;
                            echo '</div>';
                        }
                    endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>   
       
        <?php
        echo do_shortcode('[gallery_box]');
    else: 
        // This is a parent post, so fetch and display its child posts
        $args = array(
            'post_type'      => 'galleries',
            'post_parent'    => get_the_ID(),
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'order'          => 'ASC',
        );

        $child_posts = new WP_Query($args);   
        if ($child_posts->have_posts()) : ?>
            <div class="container-set">
                <a class="back-btn parent-page-gallery" href="/galleries/">Back to galleries</a>
                <div class="flex-row">
                    <?php while ($child_posts->have_posts()) : $child_posts->the_post(); ?>
                    <div class="col-lg-6">
                        <div class="gallery-box">
                            <?php $url = wp_get_attachment_url( get_post_thumbnail_id($child_posts->ID), 'thumbnail' ); ?>
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo $url; ?>" alt="" class="gallery-image"></a>
                            <a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
                            <a class="elementor-button elementor-button-link elementor-size-sm"
                                href="<?php the_permalink(); ?>"
                                tabindex="0">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-text">
                                    View </span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
                </div>
            </div>
        <?php else: ?>
            <div class="container-set">
                <a class="back-btn parent-page-gallery" href="/galleries/">Back to galleries</a>
                <h2><?php the_title(); ?></h2>
                <?php the_content(); ?>
                <?php if( have_rows('gallery_and_video') ): ?>
                    <div class="gallery-item">
                        <div class="gallery-child-main flex-row">
                            <?php while( have_rows('gallery_and_video') ): the_row(); 
                                $image_gallery = get_sub_field('image_gallery');
                                $gallery_video = get_sub_field('gallery_video');
                                $external_video = get_sub_field('external_video');
                                $gallery_child_images = get_sub_field('video_and_image'); 
                                if($image_gallery ||  $gallery_video){
                                    if( $gallery_child_images && in_array('image', $gallery_child_images) ) {
                                        if($image_gallery){ ?>
                                            <div class="gallery-child-images col-lg-6">
                                                <img class="gallery-items" src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                            </div>
                                        <?php } 
                                    }
                                    if( $gallery_child_images && in_array('video', $gallery_child_images) ) {
                                        if($gallery_video){ ?>
                                            <div class="gallery-child-images col-lg-6">
                                                <video class="gallery-items" width="100%" height="100%" controls><source src="<?php echo $gallery_video['url']; ?>" type="video/mp4"></video>
                                            </div>
                                        <?php } 
                                    }
                                }
                                if($external_video){ 
                                    echo '<div class="gallery-child-images col-lg-6">';
                                    echo $external_video;
                                    echo '</div>';
                                }
                            endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php
        endif;
    endif; // Endif to check for parent or child.
endwhile; ?> 
</main>
</section>
<?php
// The WordPress Loop: End

get_footer();
