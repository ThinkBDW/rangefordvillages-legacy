<?php
/**
 * Shortcodes: mega-menu panels
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 4143-4487). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 4143-4487: 15 mm_* mega-menu panel shortcodes (file ends //EOF, no trailing newline) ---
add_shortcode('mm_homewood_grove', function() {
    return mm_village_panel('homewood_grove_bg', function() {

        // PUT *YOUR* HTML FOR THIS VILLAGE HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-hg-small.svg" alt="">
                Chertsey, Surrey
            </div>
            <h3>Homewood<br>Grove</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/homewood-grove/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-redirechomewood="https://rangefordvillages.co.uk/contact-us?village=Homewood+Grove&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/extr/" data-term-id="643" target="_blank" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm homewood"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * MICKLE HILL
 */
add_shortcode('mm_mickle_hill', function() {
    return mm_village_panel('mickle_hill_bg', function() {

        // PUT YOUR HTML FOR MICKLE HILL HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-m-small.svg" alt="">
                Mickle Hill, Pickering
            </div>
            <h3>Mickle<br>Hill</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/mickle-hill/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-redirectmickle="https://rangefordvillages.co.uk/contact-us?village=Mickle+Hill&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/sgzx/" target="_blank" data-term-id="651" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm micklehill"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * SIDDINGTON PARK
 */
add_shortcode('mm_siddington_park', function() {
    return mm_village_panel('siddington_park_bg', function() {

        // PUT YOUR HTML FOR SIDDINGTON PARK HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-sp-small.svg" alt="">
                Cirencester, Gloucestershire
            </div>
            <h3>Siddington <br>Park</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="https://rangefordvillages.co.uk/villages/siddington-park/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village</span></span></a>
                <a href="#" data-term-redirectsiddin="https://rangefordvillages.co.uk/contact-us?village=Siddington+Park&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/rsyg/" target="_blank" data-term-id="645" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm siddingtonpark"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * STRAWBERRY FIELDS
 */
add_shortcode('mm_strawberry_fields', function() {
    return mm_village_panel('strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR STRAWBERRY FIELDS HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-sf-small.svg" alt="">
                Stapleford, Cambridgeshire
            </div>
            <h3>Strawberry<br>fields</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/strawberry-fields/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-strawberry ="https://rangefordvillages.co.uk/contact-us?village=Strawberry+Fields&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/ruwq/" target="_blank" data-term-id="647" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm strawberryfields"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * WADSWICK GREEN
 */
add_shortcode('mm_wadswick_green', function() {
    return mm_village_panel('wadswick_green_bg', function() {

        // PUT YOUR HTML FOR WADSWICK GREEN HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-R-small.svg" alt="">
                Corsham, Wiltshire
            </div>
            <h3>Wadswick <br>green</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/wadswick-green/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village</span></span></a>
                <a href="#" data-term-id="649" data-term-redirectgreen="https://rangefordvillages.co.uk/contact-us?village=Wadswick+Green&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/dhfy/" target="_blank" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm wadswickgreen"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * FUTURE VILLAGES
 */
add_shortcode('mm_future_villages', function() {
    return mm_village_panel('future_villages_bg', function() {

        // PUT YOUR HTML FOR FUTURE VILLAGES HERE:
        return '
            <h3>Future<br>Villages</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="https://rangefordvillages.co.uk/villages/elstree-hertfordshire/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
            </div>
        ';

    });
});


/*** RANGEFORD LIFE MENUS ***/

/**
 * LIFESTYLE
 */
add_shortcode('mm_lifestyle', function() {
    return mm_village_panel('lifestyle_bg', function() {

        // PUT YOUR HTML FOR LIFESTYLE HERE:
        return '
            <h3>Lifestyle</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/lifestyle/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more </span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * CARE AND SUPPORT
 */
add_shortcode('mm_care_and_support', function() {
    return mm_village_panel('care_and_support_bg', function() {

        // PUT YOUR HTML FOR CARE AND SUPPORT HERE:
        return '
            <h3>Care and<br>Support</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/care/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more </span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * WELLBEING
 */
add_shortcode('mm_wellbeing', function() {
    return mm_village_panel('wellbeing_bg', function() {

        // PUT YOUR HTML FOR WELLBEING HERE:
        return '
            <h3>Wellbeing</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/wellbeing/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * THE ORANGERY AT SIDDINGTON PARK
 */
add_shortcode('mm_the_orangery_at_siddington_park', function() {
    return mm_village_panel('the_orangery_at_siddington_park_bg', function() {

        // PUT YOUR HTML FOR THE ORANGERY AT SIDDINGTON PARK HERE:
        return '
            <h3>The Orangery at Siddington Park</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.theorangeryatsiddingtonpark.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * THE GREENHOUSE AT WADSWICK GREEN
 */
add_shortcode('mm_the_greenhouse_at_wadswick_green', function() {
    return mm_village_panel('the_greenhouse_at_wadswick_green_bg', function() {

        // PUT YOUR HTML FOR THE GREENHOUSE AT WADSWICK GREEN HERE:
        return '
            <h3>The Greenhouse at Wadswick Green</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.thegreenhouseatwadswickgreen.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * OAK AND HONEY AT HOMESWOOD GROVE
 */
add_shortcode('mm_oak_and_honey_at_homeswood_grove', function() {
    return mm_village_panel('oak_and_honey_at_homeswood_grove_bg', function() {

        // PUT YOUR HTML FOR OAK AND HONEY AT HOMESWOOD GROVE HERE:
        return '
            <h3>Oak and Honey at Homewood Grove</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.oakandhoneyathomewoodgrove.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * WILD THYME AT STRAWBERRY FIELDS
 */
add_shortcode('mm_wild_thyme_at_strawberry_fields', function() {
    return mm_village_panel('wild_thyme_at_strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR WILD THYME AT STRAWBERRY FIELDS HERE:
        return '
            <h3>Wild Thyme at Strawberry Fields</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.wildthymeatstrawberryfields.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * REVIVE SPA AT HOMEWOOD GROVE
 */
add_shortcode('mm_revive_spa_at_homewood_grove', function() {
    return mm_village_panel('revive_spa_at_homewood_grove_bg', function() {

        // PUT YOUR HTML FOR REVIVE SPA AT HOMEWOOD GROVE HERE:
        return '
            <h3>Revive Spa at Homewood Grove</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.reviveathomewoodgrove.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * REVIVE SPA AT STRAWBERRY FIELDS
 */
add_shortcode('mm_revive_spa_at_strawberry_fields', function() {
    return mm_village_panel('revive_spa_at_strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR REVIVE SPA AT STRAWBERRY FIELDS HERE:
        return '
            <h3>Revive Spa at Strawberry Fields</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.reviveatstrawberryfields.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});


//EOF