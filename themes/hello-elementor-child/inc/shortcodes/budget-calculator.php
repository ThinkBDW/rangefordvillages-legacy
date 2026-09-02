<?php
/**
 * Shortcode: budget calculator (legacy theme copy)
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [budget_calculator] -- duplicate of the plugin, 42 uses ---
add_shortcode('budget_calculator', 'recent_posts_function');
function recent_posts_function()
{
    ob_start(); ?>

    <div class="budget-calculator">
        <!-- test -->
        <div class="bottom-text">
            <p>The information provided is indicative only and should not be taken as guaranteed expenditure. Costs will vary based on individual circumstances, usage and options selected. We recommend that customers take independent legal and financial advice before making the decision to buy one of our properties.
            </p>
        </div>
        <div class="flex-row dropdown-row">
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6>How many bedrooms?</h6><select name="property_type_dropdown">
                        <option value="">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6> How many people</h6><select name="property_type_dropdown">
                        <option value="">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6>Do you need a parking space?</h6><select name="property_type_dropdown">
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
                <input type="radio" id="Monthly" name="condition" value="Monthly">
                <label for="Monthly">Monthly</label>
            </div>
            <div class="radio">
                <input type="radio" id="Yearly" name="condition" value="Yearly">
                <label for="Yearly">Yearly</label>
            </div>

        </div>

        <!-- home  -->
        <div class="range-slider-section home">
            <div class="title">
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>SERVICES/UTILITY</h5>
                    </div>
                    <div class="col">
                        <h5>YOU PAY PER YEAR</h5>
                    </div>
                    <div class="col">
                        <h5>LIKELY COST AT RANGEFORD PER YEAR</h5>
                    </div>
                </div>
            </div>
            <div class="description">
                <h4>HOME</h4>
                <p>Adjust the slider to your current outgoings</p>
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Council Tax</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Insurance (Building, Contents)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOME MAINTENANCE -->
        <div class="range-slider-section home-maintanance">
            <div class="description">
                <h4>HOME MAINTENANCE</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TRANSPORT  -->
        <div class="range-slider-section transport">
            <div class="description">
                <h4>TRANSPORT</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- DISCRETIONARY SPEND -->

        <div class="range-slider-section discretionary-spend">
            <div class="description">
                <h4>DISCRETIONARY SPEND</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- SERVICE CHARGE -->

        <div class="range-slider-section services-charge">

            <div class="description">
                <h4>SERVICE CHARGE</h4>
                <p>Adjust the slider to your current outgoings</p>


                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- CARE COSTS  -->

        <div class="range-slider-section care-cost">
            <div class="description">
                <h4>CARE COSTS</h4>
                <p>Adjust the slider to your current outgoings</p>


                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Chart section  -->
        <div class="chart">
            <div class="flex-row chart-row">
                <div class="col-lg-6">
                    <canvas id="ChartOne" class="piechart"></canvas>
                </div>
                <div class="col-lg-6">
                    <div class="input-container">
                        <div class="headings">

                            <h4>WHAT YOU PAY CURRENTLY</h4>
                            <h3>£13,890per year</h3>
                        </div>
                        <div class="flex-row">
                            <div class="col-md-6 home">
                                <div class="value">
                                    £
                                    <input type="number" id="Home" value="3567" readonly>
                                </div>
                                <label for="Home">Home</label>
                            </div>
                            <div class="col-md-6 home-maintanance">
                                <div class="value">
                                    £
                                    <input type="number" id="HomeMaintanance" value="2569" readonly>
                                </div>
                                <label for="HomeMaintanance">Home Maintanance</label>
                            </div>
                            <div class="col-md-6 transport">
                                <div class="value">
                                    £
                                    <input type="number" id="Transport" value="2420" readonly>
                                </div>
                                <label for="Transport">Transport</label>
                            </div>
                            <div class="col-md-6 discretionary-spend">
                                <div class="value">
                                    £
                                    <input type="number" id="DiscretionarySpend" value="3300" readonly>
                                </div>
                                <label for="DiscretionarySpend">Discretionary Spend</label>
                            </div>
                            <div class="col-md-6 services-charge">
                                <div class="value">
                                    £
                                    <input type="number" id="ServicesCharge" value="2440" readonly>
                                </div>
                                <label for="ServicesCharge">Services Charge</label>
                            </div>
                            <div class="col-md-6 care-cost">
                                <div class="value">
                                    £
                                    <input type="number" id="CareCost" value="2001" readonly>
                                </div>
                                <label for="CareCost">Care Cost</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-row chart-row mt-20">
                <div class="col-lg-6">
                    <canvas id="Charttwo" class="piechart"></canvas>
                </div>
                <div class="col-lg-6">
                    <div class="input-container">
                        <div class="headings">

                            <h4>WHAT YOU PAY CURRENTLY</h4>
                            <h3>£13,890per year</h3>
                        </div>
                        <div class="flex-row">
                            <div class="col-md-6 home">
                                <div class="value">
                                    £
                                    <input type="number" id="Home2" value="800" readonly>
                                </div>
                                <label for="Home">Home</label>
                            </div>
                            <div class="col-md-6 home-maintanance">
                                <div class="value">
                                    £
                                    <input type="number" id="HomeMaintanance2" value="750" readonly>
                                </div>
                                <label for="HomeMaintanance">Home Maintanance</label>
                            </div>
                            <div class="col-md-6 transport">
                                <div class="value">
                                    £
                                    <input type="number" id="Transport2" value="625" readonly>
                                </div>
                                <label for="Transport">Transport</label>
                            </div>
                            <div class="col-md-6 discretionary-spend">
                                <div class="value">
                                    £
                                    <input type="number" id="DiscretionarySpend2" value="950" readonly>
                                </div>
                                <label for="DiscretionarySpend">Discretionary Spend</label>
                            </div>
                            <div class="col-md-6 services-charge">
                                <div class="value">
                                    £
                                    <input type="number" id="ServicesCharge2" value="730" readonly>
                                </div>
                                <label for="ServicesCharge">Services Charge</label>
                            </div>
                            <div class="col-md-6 care-cost">
                                <div class="value">
                                    £
                                    <input type="number" id="CareCost2" value="883" readonly>
                                </div>
                                <label for="CareCost">Care Cost</label>
                            </div>
                        </div>
                    </div>
                </div>
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

