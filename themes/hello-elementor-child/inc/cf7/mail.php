<?php
/**
 * Contact Form 7: outgoing mail
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- wpcf7_custom_email_recipient ---
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


// --- custom_add_hidden_fields_to_email + dead blocks ---
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


