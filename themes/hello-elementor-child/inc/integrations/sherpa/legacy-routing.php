<?php
/**
 * Sherpa CRM: legacy community routing (to be replaced)
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 3351-3407, 3433-3548). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 3433-3548: custom_cf7_update_post_meta -- the cross-routing bug ---
function custom_cf7_update_post_meta( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $data = $submission->get_posted_data();
        $form_id = $contact_form->id();

        // Assuming 'post_id' is passed in the form data
        $post_id = intval( $data['post_id'] ); // Get the post ID

        // Get the selected value from the dropdown
        $selected_page_ID = $data['page-id'];
        $selected_value_array  = $data['your-field-name']; // Replace 'your-field-name' with your actual dropdown name

        // Ensure $selected_value exists
        $selected_value = isset($selected_value_array[0]) ? $selected_value_array[0] : '';
        if ($form_id == 22416) {
            $selected_value = trim($data['venue-title'] ?? '');
        }

        if($form_id == 16015){
            $selected_value = trim($data['propertiesvillage'] ?? '');
        }

        // Initialize the new URL variable
        $new_url = '';

        // Switch case for the first condition (selected value from dropdown)
        if ( !empty($selected_value) ) {
            switch ( $selected_value ) {
                case 'Homewood Grove':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads';
                    break;
                case 'Mickle Hill':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/2/leads';
                    break;
                case 'Siddington Park':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/3/leads';
                    break;
                case 'Strawberry Fields':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/5/leads';
                    break;
                case 'Wadswick Green':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/1/leads';
                    break;
                case 'Bramston Park, Hampshire':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;
                case 'East Grinstead, West Sussex':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;
                case 'Elstree, Hertfordshire':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;

                // Add more cases for additional options if necessary
                default:
                    // Handle any default case or set a fallback URL
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads'; // Optional default URL
                    break;
            }
        }




        // Additional switch case for the second condition (selected page ID)
        if ( !empty($selected_page_ID) ) {
            switch ( $selected_page_ID ) {
                case '12974':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/2/leads';
                    break;
                case '12910':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads';
                    break;
                case '13004':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/3/leads';
                    break;
                case '12994':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/1/leads';
                    break;
                // Add more cases for additional options if necessary
                default:
                    // Handle any default case or set a fallback URL
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads'; // Optional default URL
                    break;
            }
        }


        $myfile = fopen(get_template_directory()."/_url.log", "w") or die("Unable to open file!");
        fwrite($myfile, "<pre>".print_r($new_url, true));
        fclose($myfile);

        // Update the meta based on the form ID
        if ($form_id == 10765 || $form_id == 32660) {
            update_post_meta( 17746, 'cf7anyapi_base_url', $new_url );
        } elseif ($form_id == 16015) {
            update_post_meta( 27992, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 2564){
            update_post_meta( 17932, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 637){
            update_post_meta( 17934, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 7807){
            update_post_meta( 17935, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 12311){
            update_post_meta( 17937, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 22416){
            update_post_meta( 28731, 'cf7anyapi_base_url', $new_url );
        }
    }
}

add_action( 'wpcf7_before_send_mail', 'custom_cf7_update_post_meta' );



