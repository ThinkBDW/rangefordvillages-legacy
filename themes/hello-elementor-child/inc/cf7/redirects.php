<?php
/**
 * Contact Form 7: post-submission redirects
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [villages_thank_url] + dead commented redirect ---
function villages_thank_url_shortcode() {
    // Fetch the 'thank_you_page_url' ACF field value
    $thank_you_page_url = get_field('thank_you_page_url');

    // Return the input field with the value
    return '<input type="hidden" value="' . esc_url($thank_you_page_url) . '" class="villages-thank-url">';
}

// Register the shortcode [villages_thank_url]
add_shortcode('villages_thank_url', 'villages_thank_url_shortcode');


// --- wp_footer redirect + redirect_cf7_with_referer ---
// Contact Form 7 redirect logic
add_action('wp_footer', function () {

    if (is_singular('villages')) { ?>
        <script type="text/javascript">
        document.addEventListener('wpcf7mailsent', function(event) {

            // CF7 form ID
            var formId = event.detail.contactFormId;

            // Current village slug from URL (/villages/{slug}/)
            var pathParts = window.location.pathname.split('/').filter(Boolean);
            var villageSlug = pathParts[1];

            // --- Brochure form redirect ---
            if ((formId === 10765 || formId === 32660) && villageSlug) {
                location.href = '/villages/' + villageSlug + '/thank-you/?brochure-request';
            }
            // --- Default redirect for other forms ---
            else {
                var thankYouUrlInput = document.querySelector('.villages-thank-url');
                if (thankYouUrlInput && thankYouUrlInput.value) {
                    location.href = thankYouUrlInput.value;
                } else {
                    location.href = '/thank-you';
                }
            }

        });
        </script>
    <?php
    } else {
        redirect_cf7_with_referer();
    }

});



function redirect_cf7_with_referer()
{
    if (isset($_GET['village']) && isset($_GET['type'])) { ?>
        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var inputs = event.detail.inputs;
                var village = '';
                var type = '';
                for (var i = 0; i < inputs.length; i++) {
                    if ('village' == inputs[i].name) {
                        // Convert to lowercase and replace spaces with hyphens
                        village = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }

                    if ('type' == inputs[i].name) {
                        type = inputs[i].value;
                    }
                }
                location = '/villages/' + village + '/' + type + '/' + 'thank-you';
            }, false);
        </script>

        <?php
    } else if (isset($_GET['propertiesvillage']) && isset($_GET['property']) && isset($_GET['contactType'])) { ?>
        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var inputs = event.detail.inputs;
                var village = '';
                var property = '';
                var contactType = '';

                for (var i = 0; i < inputs.length; i++) {
                    if ('propertiesvillage' == inputs[i].name) {
                        // Convert to lowercase and replace spaces with hyphens
                        village = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }
                    if ('property' == inputs[i].name) {
                        property = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }
                    if ('contactType' == inputs[i].name) {
                        contactType = inputs[i].value;
                    }
                }
                location = '/villages/' + village + '/' + property + '/' + '/make-enquiry/thank-you';
            }, false);
        </script>

    <?php } else { ?>

        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var thankYouUrlInput = document.querySelector('.villages-thank-url'); // Check for the hidden input
                if (window.lastClickedBrochureButton) {
                    var termUrl = window.lastClickedBrochureButton.data('term-url');
                    if (termUrl) {
                        window.open(termUrl, '_blank'); // Opens the link in a new tab

                    }
                    /*Thank you 1 */
                    var redirectUrlproperties = window.lastClickedBrochureButton.data('term-redirectproperty');
                    if (redirectUrlproperties) {
                        // Parse the redirectUrl to extract village and type
                        var urlParams = new URLSearchParams(redirectUrlproperties.split('?')[1]);

                        var village = urlParams.get('propertiesvillage').toLowerCase().replace(/\s+|\+/g, '-');
                        var property = urlParams.get('property').toLowerCase().replace(/\s+|\+/g, '-');
                        var type = urlParams.get('type');
                        var termId = window.lastClickedBrochureButton.data('term-id'); // Get the term ID

                        // Store the post_id_brochure in localStorage
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationproperties = '/villages/' + village + '/' + property + '/' + '/request-brochure/thank-you';
                        //console.log(newLocationproperties);
                        location.href = newLocationproperties; // Redirect to the new location
                    }
                    /** Thank you 2*/
                    var redirectUrl = window.lastClickedBrochureButton.data('term-redirect');
                    if (redirectUrl) {
                        var urlParams = new URLSearchParams(redirectUrl.split('?')[1]);
                        var village = urlParams.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var type = urlParams.get('type');
                        var termId = window.lastClickedBrochureButton.data('term-id'); // Get the term ID

                        // Store the post_id_brochure in localStorage
                        localStorage.setItem('post_id_brochure_id', termId);


                        var newLocation = '/villages/' + village + '/' + type + '/thank-you';
                        location.href = newLocation; // Redirect to the new location
                    }
                    /**/
                    var redirectUrlhome = window.lastClickedBrochureButton.data('term-redirechomewood');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectUrlhome) {
                        var urlParamshome = new URLSearchParams(redirectUrlhome.split('?')[1]);
                        var villagehome = urlParamshome.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typehome = urlParamshome.get('type');
                        var termUrl1 = window.lastClickedBrochureButton.data('term-url');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationhome = '/village/' + villagehome + '/' + typehome + '/thank-you';
                        location.href = newLocationhome; // Redirect to the new location
                    }

                    var redirectUrlhome = window.lastClickedBrochureButton.data('term-strawberry');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectUrlhome) {
                        var urlParamshome = new URLSearchParams(redirectUrlhome.split('?')[1]);
                        var villagehome = urlParamshome.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typehome = urlParamshome.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationhome = '/village/' + villagehome + '/' + typehome + '/thank-you';
                        location.href = newLocationhome; // Redirect to the new location
                    }
                    var redirectmickle = window.lastClickedBrochureButton.data('term-redirectmickle');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectmickle) {
                        var urlredirectmickle = new URLSearchParams(redirectmickle.split('?')[1]);
                        var villagemickle = urlredirectmickle.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typemickle = urlredirectmickle.get('type');
                        // Construct the new location
                        localStorage.setItem('post_id_brochure_id', termId);
                        var newLocationmickle = '/village/' + villagemickle + '/' + typemickle + '/thank-you';
                        location.href = newLocationmickle; // Redirect to the new location
                    }
                    var redirectsiddin = window.lastClickedBrochureButton.data('term-redirectsiddin');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectsiddin) {
                        var urlredirectsiddin = new URLSearchParams(redirectsiddin.split('?')[1]);
                        var villagesiddin = urlredirectsiddin.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typesiddin = urlredirectsiddin.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationsiddin = '/village/' + villagesiddin + '/' + typesiddin + '/thank-you';
                        location.href = newLocationsiddin; // Redirect to the new location
                    }

                    var redirectgreen = window.lastClickedBrochureButton.data('term-redirectgreen');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectgreen) {
                        var urlredirectgreen = new URLSearchParams(redirectgreen.split('?')[1]);
                        var villagegreen = urlredirectgreen.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typesiddingreen = urlredirectgreen.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationgreen = '/village/' + villagegreen + '/' + typesiddingreen + '/thank-you';
                        location.href = newLocationgreen; // Redirect to the new location
                    }
                    var $submittedPopup = jQuery(event.target).closest('[id^="popmake-"]');
                    if ($submittedPopup.length) {
                        $submittedPopup.find('.wpcf7-response-output').hide();
                        $submittedPopup.find('.popmake-close').trigger('click');
                    } else {
                        jQuery('#popmake-10766 .wpcf7-form sent .wpcf7-response-output').hide();
                        jQuery('#popmake-10766').find('.popmake-close').trigger('click');
                    }
                } else if(thankYouUrlInput && thankYouUrlInput.value) {
                    location.href = thankYouUrlInput.value; // Redirect to the URL from the input

                } else {
                    var redirectUrl1 = '/thank-you';
                    location = redirectUrl1;
                }
            }, false);
        </script>

    <?php }
}
// Add this to your theme's functions.php file or a custom plugin
