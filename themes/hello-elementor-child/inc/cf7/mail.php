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




// --- rv_cf7_no_pii_notification ---

/**
 * Strip all personal data from the admin-bound notification email of every
 * Sherpa lead form.
 *
 * Policy (Dane, 2026-09-02): lead details must not travel by email at all.
 * The lead itself reaches Sherpa CRM, failures are recoverable from the CRM
 * Leads screen, and the Sherpa alert emails already carry no contact
 * details -- so the notification only needs to say that an enquiry arrived
 * and where it went. Email is the worst channel for lead data: it lands in
 * mailboxes nobody controls, and FluentSMTP keeps a copy of every message
 * body in the database for its log window.
 *
 * Runs on wpcf7_mail_components, i.e. AFTER the template is composed and
 * after the wpcf7_before_send_mail mutations above -- so the replacement
 * also discards the budget-calculator values and chart images that
 * custom_add_hidden_fields_to_email() appends for form 12311. Sender,
 * additional headers (Reply-To) and attachments are rebuilt too, because
 * the composed values of all three can carry the visitor's name, address
 * or uploaded files.
 *
 * The visitor's own confirmation copy (mail_2) is deliberately untouched:
 * it goes to the person the data belongs to. Form 11060 (newsletter) is not
 * in the Sherpa config, so its notification -- whose whole purpose is the
 * subscriber's address -- keeps working.
 *
 * @param array             $components subject/sender/body/recipient/
 *                                      additional_headers/attachments.
 * @param WPCF7_ContactForm $contact_form Form being mailed.
 * @param WPCF7_Mail        $mail       Template being composed.
 * @return array
 */
function rv_cf7_no_pii_notification( $components, $contact_form, $mail = null ) {
	if ( ! is_object( $mail ) || 'mail' !== $mail->name() ) {
		return $components;
	}

	if ( ! function_exists( 'rv_sherpa_config' )
		|| ! array_key_exists( (int) $contact_form->id(), rv_sherpa_config() ) ) {
		return $components;
	}

	$community  = '';
	$submission = class_exists( 'WPCF7_Submission' ) ? WPCF7_Submission::get_instance() : null;

	if ( $submission && class_exists( 'RV_Sherpa_Router' ) ) {
		$route = RV_Sherpa_Router::resolve( (int) $contact_form->id(), (array) $submission->get_posted_data() );

		if ( null !== $route['community'] ) {
			$community = RV_Sherpa_Router::community_name( $route['community'] );
		}
	}

	$lines = array(
		'A new enquiry has been received.',
		'',
		'Form:     ' . $contact_form->title() . ' (#' . $contact_form->id() . ')',
	);

	if ( $community ) {
		$lines[] = 'Village:  ' . $community;
	}

	$lines[] = 'Received: ' . gmdate( 'Y-m-d H:i' ) . ' UTC';
	$lines[] = '';
	$lines[] = 'By policy this notification carries no personal data. The enquiry is';
	$lines[] = 'delivered to Sherpa CRM; delivery status is tracked in wp-admin:';
	$lines[] = admin_url( 'admin.php?page=rv-sherpa-leads' );

	$host = (string) wp_parse_url( home_url(), PHP_URL_HOST );

	$components['subject']            = 'New enquiry - ' . ( $community ? $community : $contact_form->title() );
	$components['body']               = implode( "\n", $lines );
	$components['sender']             = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES )
		. ' <wordpress@' . preg_replace( '/^www\./', '', $host ) . '>';
	$components['additional_headers'] = '';
	$components['attachments']        = '';

	return $components;
}
add_filter( 'wpcf7_mail_components', 'rv_cf7_no_pii_notification', 100, 3 );
