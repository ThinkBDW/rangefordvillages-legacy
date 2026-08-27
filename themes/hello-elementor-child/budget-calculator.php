<?php /* Template Name: Budget Calculator */ ?>

<?php get_header(); ?>

<main id="content" class="site-main custom-container">

    <div class="budget-calculator">


        <div class="flex-row">
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6> How many bedrooms?</h6><select name="property_type_dropdown">
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

        <div class="range-slider-section ">
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
            <div class="description home">
                <h4>HOME</h4>
                <p>Adjust the slider to your current outgoings</p>
            </div>
        </div>

    </div>
</main>
<?php get_footer();
