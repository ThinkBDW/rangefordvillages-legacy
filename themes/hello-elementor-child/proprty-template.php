<?php /* Template Name: Properties Template */?>

<?php get_header(); ?>

<main id="content" class="site-main custom-container">


    <!-- properties banner  -->
    <div class="properties-banner">
        <?php
        $properties_main_image = get_field('properties_main_image');
        $banner_title = get_field('banner_title');
        $banner_description = get_field('banner_description');
        ?>
        <div class="baner-image" style="background-image:url(<?php echo $properties_main_image; ?>);">

            <div class="banner-conetent">

                <h1 class="elementor-heading-title elementor-size-default">
                    <?php echo $banner_title; ?>
                </h1>
                <?php echo $banner_description; ?>
            </div>
        </div>

    </div>
    </div>


    <div class="properties-filter-row">
        <div class="flex-row">
            <div class="col-lg-6">
                <?php
                // $post_type = 'properties';
                // $post_count = wp_count_posts($post_type);
                // echo '<h2>' . $post_count->publish . ' Properties' . '</h2>';

                echo '<h2 class="properties-totalfilter"></h2>'
                ?>
            </div>
            <div class="col-lg-6">

                <div class="filter">
                    <div class="saved-properties">
                        <label for="savedproperties"><!--<img src="/wp-content/uploads/2023/12/Vector-1.png">--> <input
                                type="checkbox" id="savedproperties" name="savedproperties" value="savedproperties">
                            Saved Properties</label>
                    </div>
                    <div class="sorting">
                        <select id="price_sorting" name="price_sorting">
                            <option value="">Sort by</option>
                            <option value="low_to_high">Price Low to High</option>
                            <option value="high_to_low">Price High to Low</option>
                        </select>
                    </div>
                    <div class="more-filter elementor-button elementor-button-link elementor-size-sm">
                        <span>Open Filter</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="more-filter-data" style="display:none;">
            <div class="all-filter">

                <?php
                $args = array(
                    'post_type' => 'properties', // replace with your actual post type
                    'posts_per_page' => -1,
                );
                $query = new WP_Query($args);
                $locations = array();

                while ($query->have_posts()) {
                    $query->the_post();
                    $location = get_field('location');
                    $properties_type = get_field('properties_type');

                    if ($location) {
                        $locations[] = $location;
                    }
                    if ($properties_type) {
                        $properties_types[] = $properties_type;
                    }

                }

                $unique_locations = array_unique($locations);
                $unique_properties_types = array_unique($properties_types);
                echo '<div class="filter-select-row">'; //filter row start
                echo '<div class="filter-select-left">';
                echo '<h6>All villages</h6>';
                echo '<select name="location_dropdown">';
                echo '<option value="">Select Location</option>';
                foreach ($unique_locations as $location) {
                    echo '<option value="' . esc_attr($location) . '">' . esc_html($location) . '</option>';
                }
                echo '</select>';
                echo '</div>';



                echo '<div class="filter-select-right">';
                echo '<h6>Price range</h6>';
                echo '<div class="min-max-price">';
                 ?>
                <div class="range-slider">
                    <span class="rangeValues"></span>
                    <input id="minPriceSlider" value="100000" min="100000" max="1000000" step="50000" type="range">
                    <input id="maxPriceSlider" value="1000000" min="200000" max="1000000" step="50000" type="range">
                </div>
<script>
    function getVals(){
        // Get slider values
        let parent = this.parentNode;
        let slides = parent.getElementsByTagName("input");
        let slide1 = parseFloat(slides[0].value);
        let slide2 = parseFloat(slides[1].value);
        // Neither slider will clip the other, so make sure we determine which is larger
        if (slide1 > slide2) { let tmp = slide2; slide2 = slide1; slide1 = tmp; }
        
        let displayElement = parent.getElementsByClassName("rangeValues")[0];
        displayElement.innerHTML = "£" + slide1.toLocaleString() + " - £" + slide2.toLocaleString();
    }

    window.onload = function(){
        // Initialize Sliders
        let sliderSections = document.getElementsByClassName("range-slider");
        for (let x = 0; x < sliderSections.length; x++) {
            let sliders = sliderSections[x].getElementsByTagName("input");
            for (let y = 0; y < sliders.length; y++) {
                if (sliders[y].type ==="range") {
                    sliders[y].oninput = getVals;
                    // Manually trigger event first time to display values
                    sliders[y].oninput();
                }
            }
        }
    }
</script>
                <?php

                echo '</div>';
                echo '</div>';
                echo '</div>'; //filter row end
                
                echo '<div class="filter-select-row">'; //filter row start
                echo '<div class="filter-select-left">';
                echo '<h6>Property type</h6>';
                echo '<select name="property_type_dropdown">';
                echo '<option value="">All</option>';
                foreach ($unique_properties_types as $properties_type) {
                    echo '<option value="' . esc_attr($properties_type) . '">' . esc_html($properties_type) . '</option>';
                }
                echo '</select>';
                echo '</div>';

                echo '<div class="filter-select-right">';
                echo '<h6>Bedrooms</h6>';
                echo '<div class="min-max-price">';
                echo '<div class="all-price">';
                echo '<select name="min_bed" id="min_bed">';
                echo '<option value="">No Min</option>';
                echo '<option value="1">1</option>';
                echo '<option value="2">2</option>';
                echo '<option value="3">3</option>';
                echo '<option value="4">4</option>';
                echo '</select>';
                echo '</div>';
                echo '<div class="all-price">';
                echo '<select name="max_bed" id="max_bed">';
                echo '<option value="">No Max</option>';
                echo '<option value="1">1</option>';
                echo '<option value="2">2</option>';
                echo '<option value="3">3</option>';
                echo '<option value="4">4</option>';
                echo '</select>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '</div>'; //filter row end
                ?>
                <div class="main-condition">
                    <div class="inner-condition">
                        <div>
                            <h6>Condition</h6>
                        </div>
                        <input type="radio" id="new" name="condition" value="new">
                        <label for="new">New</label>
                        <input type="radio" id="resale" name="condition" value="resale">
                        <label for="resale">Resale</label><br>
                    </div>
                    <div class="inner-condition hide-reserved">
                        <input type="checkbox" id="reserved" name="reserved" value="reserved">
                        <label for="reserved">Hide Reserved</label>

                    </div>
                    <div class="inner-condition">
                        <div class="amenities">
                            <?php
                            // Get the terms from the "amenities" taxonomy
                            $terms = get_terms(
                                array(
                                    'taxonomy' => 'amenities',
                                    'hide_empty' => false,
                                )
                            );

                            // Check if any terms were found
                            if (!empty($terms)) {
                                // Loop through each term and display it as a checkbox
                                $count = 0; // Variable to track the number of displayed categories
                                foreach ($terms as $term) {
                                    $count++;
                                    ?>
                                    <label class="<?php echo ($count > 3) ? 'hidden-category' : ''; ?>">
                                        <input type="checkbox" name="amenities[]" value="<?php echo esc_attr($term->slug); ?>"
                                            id="<?php echo esc_attr($term->slug); ?>">
                                        <?php echo esc_html($term->name); ?>
                                    </label><br>
                                    <?php
                                }

                                // Check if there are more categories to show
                                if (count($terms) > 3) {
                                    ?>
                                    <span style="text-decoration:underline;" class="read-more">Show more</span>
                                    <?php
                                }
                            } else {
                                echo 'No terms found.';
                            }
                            ?>
                        </div>
                    </div>
                </div>



                <div class="reset-filter">
                    <hr>
                    <button id="resetFiltersBtn">Reset Filters</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="property-tiles-container">
        </div>
        <div id="loader-wrapper" class="loader" style="display: none;">
            <div id="loader"></div>
        </div>
</main>
<?php get_footer();