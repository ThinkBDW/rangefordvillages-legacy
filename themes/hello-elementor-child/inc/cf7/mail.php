<?php
/**
 * Contact Form 7: outgoing mail
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 2236-2298, 3130-3242). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 2236-2298: wpcf7_custom_email_recipient ---
function wpcf7_custom_email_recipient($contact_form) {
    $form_id = $contact_form->id();
    $submission = WPCF7_Submission::get_instance();

    if (!$submission) {
        error_log('Submission instance not found.');
        return;
    }

    $data = $submission->get_posted_data();
    $mapping = get_transient('village_email_mapping');

    // --- Forms with your-field-name dropdown ---
    if (in_array($form_id, [637, 2564, 16015, 10765, 32660])) {

        if (isset($data['your-field-name'])) {
            $selected_value = is_array($data['your-field-name'])
                ? trim($data['your-field-name'][0])
                : trim($data['your-field-name']);

            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }elseif(isset ($data['propertiesvillage'])){
            $selected_value = $data['propertiesvillage'];
            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }
    }

    // --- Form 22416 with venu-title hidden field ---
    if ($form_id == 22416) {
        if (isset($data['venue-title'])) {
            $selected_value = trim($data['venue-title']);

            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }
    }

    // If we got addresses, process them
    if (!empty($email_addresses)) {
        $emails = array_map('trim', explode(',', $email_addresses));
        $valid_emails = array_filter($emails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        if (!empty($valid_emails)) {
            $recipient_string = implode(',', $valid_emails);
            $mail = $contact_form->prop('mail');
            $mail['recipient'] = $recipient_string;
            $contact_form->set_properties(['mail' => $mail]);
        } else {
            error_log('No valid emails found for: ' . $selected_value);
        }
    } else {
        error_log("Email mapping not found for form $form_id value: " . ($selected_value ?? 'N/A'));
    }
}
add_action('wpcf7_before_send_mail', 'wpcf7_custom_email_recipient');


// --- functions.php lines 3130-3242: custom_add_hidden_fields_to_email + dead blocks ---
add_action('wpcf7_before_send_mail', 'custom_add_hidden_fields_to_email');

function custom_add_hidden_fields_to_email($contact_form) {
    $submission = WPCF7_Submission::get_instance();

    if ($submission) {
        $posted_data = $submission->get_posted_data();
        $email_body = '';
        if (isset($posted_data['hidden_dynamic_values_new'])) {
            $dynamic_values_new = json_decode($posted_data['hidden_dynamic_values_new'], true);

            $email_body .= "\n\n\n" . $dynamic_values_new['title'] . "\n\n\n";
            $email_body .= "<b>Total Value:</b>" . $dynamic_values_new['totalValue'] . "\n\n";
            $email_body .= "<b>Details</b>:\n\n\n";

            foreach ($dynamic_values_new['details'] as $label_new => $value_new) {
                $email_body .= $label_new . ': £' . $value_new . "\n";
            }
        }

        // Adding chart images to email body
        if (isset($posted_data['hidden_chart_image1'])) {
            $chart_image1 = $posted_data['hidden_chart_image1'];
            $email_body .= "\n<b>Chart 1</b>:\n<img src=\"$chart_image1\" alt=\"Chart 1\">\n";
        }

        if (isset($posted_data['hidden_dynamic_values'])) {
            $dynamic_values = json_decode($posted_data['hidden_dynamic_values'], true);

            $email_body .= $dynamic_values['title'] . "\n\n\n";
            $email_body .= "<b>Total Value:</b>" . $dynamic_values['totalValue'] . "\n\n";
            $email_body .= "<b>Details</b>:\n\n\n";

            foreach ($dynamic_values['details'] as $label => $value) {
                $email_body .= $label . ': £' . $value . "\n";
            }
        }

        if (isset($posted_data['hidden_chart_image2'])) {
            $chart_image2 = $posted_data['hidden_chart_image2'];
            $email_body .= "\n<b>Chart 2</b>:\n<img src=\"$chart_image2\" alt=\"Chart 2\">\n";
        }

        if (!empty($email_body)) {
            $mail = $contact_form->prop('mail');
            $mail['body'] .= "\n\n" . $email_body;

            $contact_form->set_properties(array(
                'mail' => $mail
            ));
        }
    }
}




// add_action('wp_ajax_save_chart_image', 'save_chart_image');
// add_action('wp_ajax_nopriv_save_chart_image', 'save_chart_image');

// function save_chart_image() {
//     if (isset($_POST['image'])) {
//         $image_data = $_POST['image'];

//         // Remove the base64 encoding part of the image
//         $image_data = str_replace('data:image/png;base64,', '', $image_data);
//         $image_data = str_replace(' ', '+', $image_data);

//         $decoded_image = base64_decode($image_data);
//         $upload_dir = get_stylesheet_directory() . '/images';
//         $image_filename = 'chart_image_' . time() . '.png';
//         $image_path = $upload_dir . '/' . $image_filename;

//         // Create the directory if it doesn't exist
//         if (!file_exists($upload_dir)) {
//             mkdir($upload_dir, 0755, true);
//         }

//         if (file_put_contents($image_path, $decoded_image)) {
//             $response = array(
//                 'success' => true,
//                 'filepath' => $image_path,
//                 'relative_filepath' => get_stylesheet_directory_uri() . '/images/' . $image_filename
//             );
//         } else {
//             $response = array(
//                 'success' => false,
//                 'message' => 'Failed to save image',
//             );
//         }

//         echo json_encode($response);
//         wp_die(); // This is required to terminate immediately and return a proper response
//     }
// }

// function populate_hidden_field_with_acf_value($form) {

//         // Get the ACF field value
//         $sherpa_vendorname = get_field('sherpa_vendorname','option'); // Ensure this matches your ACF field name
//         $sherpa_source_category = get_field('sherpa_source_category','option'); // Ensure this matches your ACF field name
//         $sherpa_source_name = get_field('sherpa_source_name','option'); // Ensure this matches your ACF field name

//         // Find the hidden field and set its value
//         $form = str_replace('[hidden vendorName]', '[hidden vendorName "' . esc_attr($sherpa_vendorname) . '"]', $form);
//         $form = str_replace('[hidden sourceCategory]', '[hidden sourceCategory "' . esc_attr($sherpa_source_category) . '"]', $form);
//         $form = str_replace('[hidden sourceName]', '[hidden sourceName "' . esc_attr($sherpa_source_name) . '"]', $form);

//     return $form;
// }
// add_filter('wpcf7_form_elements', 'populate_hidden_field_with_acf_value');


