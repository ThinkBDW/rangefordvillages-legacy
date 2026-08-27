<?php
/**
 * Custom Archive Template for Events Calendar
 * Displays the calendar view on the custom archive page.
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        // Display the calendar view using The Events Calendar
        echo tribe_get_view( 'month' ); // You can use 'month', 'list', 'day', etc., to display different views.
        ?>
    </main>
</div>

<?php get_footer(); ?>
