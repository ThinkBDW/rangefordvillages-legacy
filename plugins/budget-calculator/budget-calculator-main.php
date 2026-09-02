<?php
/*
Plugin Name: Budget Calculator
Plugin URI: https://rangefordvillages-co-uk.stackstaging.com/
Description: This is a Budget Calculator plugin
Version: 1.0
Author: Anotherway Digital
Author URI: https://anotherway.digital/
*/

// Ensure this script is not executed outside of WordPress
defined('ABSPATH') or die('No script kiddies please!');

function enqueue_custom_js() {
    // Calculator assets load only on pages that render the calculator --
    // html2canvas alone is 198 KB. The function_exists() guard keeps the
    // plugin working standalone if the theme helper ever disappears.
    if (function_exists('rv_is_budget_calculator_page') && !rv_is_budget_calculator_page()) {
        return;
    }

    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . 'js/nouislider.min.js', array('jquery'), '1.0', true);
    // filemtime, not time(): a time() version mints a new URL on every
    // page load, so nothing ever caches the file.
    wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'js/custom-calculator.js', array('jquery'), filemtime(plugin_dir_path(__FILE__) . 'js/custom-calculator.js'), true);
    wp_enqueue_style('custom-style', plugin_dir_url(__FILE__) . 'css/nouislider.min.css', array(), '1.0');
    wp_enqueue_script('html2canvas', plugin_dir_url(__FILE__) . 'js/html2canvas.min.js', array(), '1.0');

}
add_action('wp_enqueue_scripts', 'enqueue_custom_js');

function my_custom_calculator($atts) {
    // Extract the attribute
    $atts = shortcode_atts(
        array(
            'repeater_name' => 'servicesutility', // Default repeater name
        ), 
        $atts, 
        'budget_calculator_custom'
    );
    
    $repeater_name = $atts['repeater_name'];

    ob_start();
    ?>

    <div class="budget-calculator">
        <div class="bottom-text">
            <p>The information provided is indicative only and should not be taken as guaranteed expenditure. Costs will vary based on individual circumstances, usage and options selected. We recommend that customers take independent legal and financial advice before making the decision to buy one of our properties.  
            
            <?php if(is_page( 12910 )): ?>
            
            Learn more about our fees and what's included <a href="/fees-charges-homewood-grove/" style="text-decoration:underline">here</a>.
            
             <?php elseif(is_page( 12974 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-mickle-hill/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 13004 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-siddington-park/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 12994 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-wadswick-green/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 18555 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-strawberry-fields/" style="text-decoration:underline">here</a>.
             
             <?php endif; ?>
            
            </p>
        </div>
        <div class="flex-row">
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6> How many bedrooms?</h6>
                    <select name="property_type_dropdown" id="property_type_dropdown">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6> How many people</h6>
                  
                    <select name="property_type_dropdown">
                        <option value="1">1</option>
                        <option value="2">2</option>
                       
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6>Do you need a parking space?</h6>
                    <select name="property_type_dropdown">
                        <option value="">Yes</option>
                        <option value="">No</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex-row radio-row">
            <div class="radio">
                <input type="radio" id="Weekly" name="condition" value="Weekly">
                <label for="Weekly">Weekly</label>
            </div>
            <div class="radio">
                <input type="radio" id="Monthly" name="condition" value="Monthly" checked>
                <label for="Monthly">Monthly</label>
            </div>
            <div class="radio">
                <input type="radio" id="Yearly" name="condition" value="Yearly">
                <label for="Yearly">Yearly</label>
            </div>
        </div>
        <div class="range-slider-section">
            <div class="title">
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>SERVICES/UTILITY</h5>
                    </div>
                    <div class="col">
                        <h5>YOU PAY PER <span class="change-year"></span></h5>
                    </div>
                    <div class="col">
                        <h5>LIKELY COST AT RANGEFORD PER <span class="change-year"></span></h5>
                    </div>
                </div>
            </div>
        </div>
        <?php if( have_rows($repeater_name,'option') ): 
    while ( have_rows($repeater_name,'option') ) : the_row();
    $main_section_title = get_sub_field('main_section_title','option');
    
    // Check if the main_section_title is 'Home' and replace it with 'Homes'
    if ($main_section_title === 'Home') {
        $main_section_title = 'Homes';
    }
    $formatted_title_class = strtolower(str_replace(' ', '-', $main_section_title));
    $weekly = get_field('weekly','option');
    $yearly = get_field('yearly','option');
    $monthly = get_field('monthly','option');
    if($main_section_title == 'Transport'){
        $monthly = 2500;
    }
    ?>
    <div class="range-slider-section <?php echo $formatted_title_class; ?> <?php echo 'section-color'.get_row_index(); ?>" 
         data-weekly="<?php echo get_field('weekly','option'); ?>" 
         data-monthly="<?php echo get_field('monthly','option'); ?>" 
         data-yearly="<?php echo get_field('yearly','option'); ?>"
         data-weekly-max="<?php echo get_field('weekly','option'); ?>"
         data-monthly-max="<?php echo get_field('monthly','option'); ?>"
         data-yearly-max="<?php echo get_field('yearly','option'); ?>">
        <div class="description">
            <?php 
            $subtitle = get_sub_field('subtitle','option');
            ?>
            <h4><?php echo $main_section_title; ?></h4>
            <p><?php echo $subtitle; ?></p>
            <?php if( have_rows('utility_section','option') ):
                    $index = 0;
                while ( have_rows('utility_section','option') ) : the_row();  
                $service_title = get_sub_field('service_title','option');
                $slide_range_min = get_sub_field('slide_range_min','option');
                $slide_range_max = get_sub_field('slide_range_max','option');
                
                // Fetch ACF values for different bedrooms
                $service_changed_value_1 = get_sub_field('service_changed_value', 'option');
                $service_changed_value_2 = get_sub_field('service_changed_value_bed_2', 'option');
                $service_changed_value_3 = get_sub_field('service_changed_value_bed_3', 'option');
                $reduce_rate = get_sub_field('reduce_rate', 'option');
                $reduce_rate_value = get_sub_field('reduce_rate_value', 'option');
                $tooltip_text = get_sub_field('tooltip_text', 'option');
                
                ?>
            <div class="flex-row table-row">
                <div class="col">
                    <h5><?php echo $service_title; ?></h5>
                </div>
                <div class="col">
                    <div class="flex-row range-slider-row">
                        <div class="range-wrap">
                            <div class="range-value"></div>
                            <input type="range" id="rangeSlider_<?php echo $formatted_title_class; ?>_<?php echo $index; ?>" class="range-slider" min="0" max="<?php echo $monthly; ?>" value="<?php echo $slide_range_min; ?>">

                            
                        </div>
                        <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value" id="rangeSliderValue_<?php echo $formatted_title_class; ?>_<?php echo $index; ?>"><?php echo $slide_range_min; ?></span></span>
                        <span class="field"></span>
                        <div class="inspired-price">
                            <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                            <div class="popup-row">
                                <input type="text" data-id="0" id="inspired_<?php echo $formatted_title_class; ?>_<?php echo $index; ?>" name="inspired_home_<?php echo $index; ?>" class="inspired_input" 
                                    data-value-1="<?php echo $service_changed_value_1; ?>" 
                                    data-value-2="<?php echo $service_changed_value_2; ?>" 
                                    data-value-3="<?php echo $service_changed_value_3; ?>"
                                    value="£<?php echo $service_changed_value_1; ?>" 
                                    data-original-value="<?php echo $service_changed_value_1; ?>"
                                    data-reduce-rate="<?php echo $reduce_rate ? 'true' : 'false'; ?>"
                                    data-reduce-value="<?php echo $reduce_rate_value; ?>" data-reduced-value="">
                                    
                                <div class="tooltip" onclick="toggleTooltip(this)">
                                    <i class="fa fa-info-circle"></i>
                                    <span class="tooltiptext"><?php echo $tooltip_text; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $index++; 
            endwhile;
        endif; ?> 
        <div class="section-total" style="display:none;">
            <strong>Total: £<span id="sectionTotal_<?php echo $formatted_title_class; ?>">0</span></strong>
        </div>
    
        <div class="section-total-right" style="display:none;">
            <strong>Totalright: £<span id="sectionTotalright_<?php echo $formatted_title_class; ?>">0</span></strong>
        </div>

       

        </div>
    </div>
    <?php
     endwhile;
    endif; 
    ?>
    <!-- Chart section  -->
     <div class="chart" id="mainchart">
        <div class="flex-row chart-row">
            <div class="col-lg-6">
                <div class="ChartOne1" id="chart11">
                    <canvas id="ChartOne1" class="piechart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="input-container chart-one">
                    <div class="headings">
                        <h4>WHAT YOU PAY CURRENTLY</h4>
                        <h3 class="toal-value-chart">0</h3>
                    </div>
                    <div class="flex-row">
                    <?php if( have_rows($repeater_name,'option') ): 
                        while ( have_rows($repeater_name,'option') ) : the_row();
                        $main_section_title = get_sub_field('main_section_title','option');
                        if ($main_section_title === 'Home') {
                            $main_section_title = 'Homes';
                        }
                        $formatted_title = strtolower(str_replace(' ', '-', $main_section_title));
                        ?>
                        <div class="col-md-6 <?php echo $formatted_title; ?>">
                            <div class="value">
                                £
                                <input type="number" id="Input_<?php echo $formatted_title; ?>" class="dynamic-input" value="0" readonly>
                                <input type="hidden" id="Input_formnew<?php echo $formatted_title; ?>" class="dynamic-input1" value="0">
                            </div>
                            <label for="Input_<?php echo $formatted_title; ?>"><?php echo $main_section_title; ?></label>
                        </div>
                        <?php endwhile; endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-row chart-row mt-20">
            <div class="col-lg-6">
                <div class="Charttwo" id="chart22">
                    <canvas id="Charttwo" class="piechart"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="input-container chart-two">
                    <div class="headings">
                        <h4>LIKELY COSTS AT RANGEFORD VILLAGES</h4>
                        <h3 class="toal-value-chart-second">0</h3>
                    </div>
                    <div class="flex-row">
                    <?php if( have_rows($repeater_name,'option') ): 
                        while ( have_rows($repeater_name,'option') ) : the_row();
                        $main_section_title_second = get_sub_field('main_section_title','option');
                        if ($main_section_title_second === 'Home') {
                            $main_section_title_second = 'Homes';
                        }
                        $formatted_title_second = strtolower(str_replace(' ', '-', $main_section_title_second));
                        ?>
                        <div class="col-md-6 <?php echo $formatted_title_second; ?>">
                            <div class="value">
                                £
                                <input type="number" id="Input_secon<?php echo $formatted_title_second; ?>" class="dynamic-input-second" value="0" readonly>
                                <input type="hidden" id="Input_form<?php echo $formatted_title_second; ?>" class="dynamic-input-second1" value="0">
                            </div>
                            <label for="Inputsecond_<?php echo $formatted_title_second; ?>"><?php echo $main_section_title_second; ?></label>
                        </div>
                        <?php endwhile; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="bottom-text" style="margin-top:1rem;">
            <p>The information provided is indicative only and should not be taken as guaranteed expenditure. Costs will vary based on individual circumstances, usage and options selected. We recommend that customers take independent legal and financial advice before making the decision to buy one of our properties.
            
            <?php if(is_page( 12910 )): ?>
            
            Learn more about our fees and what's included <a href="/fees-charges-homewood-grove/" style="text-decoration:underline">here</a>.
            
             <?php elseif(is_page( 12974 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-mickle-hill/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 13004 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-siddington-park/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 12994 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-wadswick-green/" style="text-decoration:underline">here</a>.
             
             <?php elseif(is_page( 18555 )): ?>
             
             Learn more about our fees and what's included <a href="/fees-charges-strawberry-fields/" style="text-decoration:underline">here</a>.
             
             <?php endif; ?>
            
            </p>
        </div>
        <!-- Form -->
        <div class="form-section">
                <h4>Save your calculation</h4>
                <p>Please complete the form, and we will email the result of the budget calculator.</p>
                <?php
                // The Contact Form 7 shortcode you copied
                $contact_form_shortcode = '[contact-form-7 id="75100c5" title="Save your calculation"]';

                // Output the contact form
                echo do_shortcode($contact_form_shortcode);
                ?>
            </div>

        </div> <!-- // Chart section  -->
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('budget_calculator_custom', 'my_custom_calculator');








