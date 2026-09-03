<?php
/**
 * Contact Form 7: visible, associated field labels.
 *
 * WHAT WAS WRONG. Every form definition already carries a <label> for each
 * field -- `<label>First name</label>` and so on, written by hand into the form
 * content. The stylesheet then threw all of them away:
 *
 *     .contact-form label { visibility: hidden; opacity: 0; height: 0;
 *                           width: 0; overflow: hidden; display: none; }
 *
 * leaving the placeholder as the only thing naming the field. That is the
 * classic placeholder-as-label problem, and it is a poor trade on a site whose
 * visitors are mostly retirement age: the name of the field disappears the
 * moment you start typing, so anyone who pauses, is interrupted, or comes back
 * to check what they entered has nothing to read. Placeholder text is also
 * rendered at a lighter weight than real text by every browser, and the fields
 * carried no border other than a hairline underline, so a form of nine fields
 * read as nine pieces of grey text floating on a cream background.
 *
 * The labels were also never associated with their controls -- no `for`, no
 * `id` anywhere on these forms -- so a screen reader announced "edit text,
 * blank" and clicking a label did not focus its field.
 *
 * WHAT THIS DOES. Three things, all to the rendered HTML, none to the stored
 * form definitions:
 *
 *   1. Gives each control an id and points its label at it with `for`, so the
 *      label is a real label: announced by assistive technology, and clickable
 *      to focus the field.
 *   2. Marks required fields on the label. The asterisk used to live inside the
 *      placeholder text ("First name*"), which is exactly where it cannot be
 *      seen once the field is filled in.
 *   3. Drops a placeholder that only repeats its own label, which is all of
 *      them. A placeholder that says something different -- a format hint --
 *      is left alone.
 *
 * WHY IN CODE RATHER THAN IN THE FORMS. Seventeen form definitions would
 * otherwise need the same edit by hand, and every one of them is content: an
 * editor rearranging a form in the CF7 screen would silently undo it. This
 * runs on whatever the forms happen to contain, including any form added
 * later.
 *
 * The visual half lives in new-style.css, under "Contact form fields".
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Reduce a string for comparison: case, spacing, punctuation and the required
 * asterisk all removed.
 *
 * Used to decide whether a placeholder is really just the label again. The
 * forms are not consistent about it -- "Telephone Number*" against a
 * "Telephone Number" label, "Address Line 1" against "Address Line 1" -- so a
 * literal comparison would miss most of them.
 *
 * @param string $text Raw text.
 * @return string
 */
function rv_cf7_label_key( $text ) {
	$text = wp_strip_all_tags( html_entity_decode( (string) $text, ENT_QUOTES, 'UTF-8' ) );
	$text = strtolower( $text );
	$text = preg_replace( '/[^a-z0-9]+/', '', $text );

	return (string) $text;
}

/**
 * Wire every hand-written <label> in a form to the control that follows it.
 *
 * Works on the rendered string rather than through DOMDocument on purpose:
 * this markup is a fragment full of CF7's own spans and an unclosed <br />,
 * and re-serialising it would rewrite parts of the form that have nothing to
 * do with labels. Every edit here is collected with its offset and applied
 * back to front, so nothing outside the labels and their controls is touched.
 *
 * @param string $elements Rendered form HTML.
 * @return string
 */
function rv_cf7_wire_labels( $elements ) {
	$form   = class_exists( 'WPCF7_ContactForm' ) ? WPCF7_ContactForm::get_current() : null;
	$prefix = $form && $form->unit_tag() ? $form->unit_tag() : 'wpcf7-form';

	$edits  = array();
	$offset = 0;

	// Only bare <label> tags, which is what the form definitions contain. The
	// honeypot's label carries a class and CF7's own list-item labels wrap
	// their input; both are handled below or simply never matched.
	while ( preg_match( '#<label>(.*?)</label>#s', $elements, $m, PREG_OFFSET_CAPTURE, $offset ) ) {
		$label_start = $m[0][1];
		$label_end   = $label_start + strlen( $m[0][0] );
		$label_text  = $m[1][0];
		$offset      = $label_end;

		// Three of the forms write the required marker into the label text
		// itself ("First Name*") and the rest do not. Strip it and let the
		// marker below be the single source, or those forms get two asterisks.
		$label_text = preg_replace( '/\s*\*\s*$/', '', $label_text );

		// CF7 renders acceptance and checkbox items as <label><input …></label>.
		// Those are already associated by nesting; leave them entirely alone.
		if ( false !== stripos( $label_text, '<input' ) ) {
			continue;
		}

		// Search only as far as the next label, so a field that renders without
		// one cannot be captured by the label above it.
		$next_label = strpos( $elements, '<label', $label_end );
		$window     = substr(
			$elements,
			$label_end,
			( false === $next_label ? strlen( $elements ) : $next_label ) - $label_end
		);

		$control = rv_cf7_first_visible_control( $window );

		if ( ! $control ) {
			continue;
		}

		$tag       = $control['tag'];
		$tag_start = $label_end + $control['offset'];
		$new_tag   = $tag;

		// Reuse an id the form definition already set (several forms use
		// `id:source-data-contact`, `id:current-date`), because scripts and
		// stylesheets may be relying on it.
		if ( preg_match( '#\sid=(["\'])(.*?)\1#i', $tag, $has_id ) ) {
			$control_id = $has_id[2];
		} else {
			$name       = preg_match( '#\sname=(["\'])(.*?)\1#i', $tag, $n ) ? $n[2] : '';
			$control_id = $prefix . '-' . preg_replace( '/[^A-Za-z0-9_-]+/', '-', $name );
			$new_tag    = preg_replace( '#^<(\w+)#', '<$1 id="' . esc_attr( $control_id ) . '"', $new_tag, 1 );
		}

		$required = false !== strpos( $tag, 'wpcf7-validates-as-required' );

		// A placeholder that repeats the label is noise once the label is
		// visible; one that says something else is a genuine hint, so keep it.
		if ( preg_match( '#\splaceholder=(["\'])(.*?)\1#i', $new_tag, $ph )
			&& rv_cf7_label_key( $ph[2] ) === rv_cf7_label_key( $label_text ) ) {
			$new_tag = str_replace( $ph[0], '', $new_tag );
		}

		if ( $new_tag !== $tag ) {
			$edits[] = array( $tag_start, strlen( $tag ), $new_tag );
		}

		// CF7's first_as_label renders the prompt as an empty-valued first
		// option, and every form uses the field's own name for it. Above a
		// visible label that is the same words twice, so say what the control
		// wants instead. The option's value is empty either way, so nothing
		// about validation or what gets submitted changes.
		if ( 0 === stripos( $tag, '<select' ) ) {
			$after = substr( $elements, $tag_start + strlen( $tag ), 400 );

			if ( preg_match( '#^\s*<option value=(["\'])\1>(.*?)</option>#is', $after, $opt, PREG_OFFSET_CAPTURE )
				&& rv_cf7_label_key( $opt[2][0] ) === rv_cf7_label_key( $label_text ) ) {
				$edits[] = array(
					$tag_start + strlen( $tag ) + $opt[2][1],
					strlen( $opt[2][0] ),
					'Please choose',
				);
			}
		}

		$edits[] = array(
			$label_start,
			strlen( $m[0][0] ),
			sprintf(
				'<label for="%s">%s%s</label>',
				esc_attr( $control_id ),
				$label_text,
				// aria-hidden: the control already carries aria-required, so
				// announcing "star" as well is just noise.
				$required ? ' <span class="rv-required" aria-hidden="true">*</span>' : ''
			),
		);
	}

	// Back to front, so each offset is still valid when it is used.
	usort(
		$edits,
		static function ( $a, $b ) {
			return $b[0] <=> $a[0];
		}
	);

	foreach ( $edits as $edit ) {
		$elements = substr_replace( $elements, $edit[2], $edit[0], $edit[1] );
	}

	return $elements;
}
add_filter( 'wpcf7_form_elements', 'rv_cf7_wire_labels', 20 );

/**
 * The first control in a chunk of markup that a visitor can actually fill in.
 *
 * Hidden and submit inputs are skipped rather than stopping the search: the
 * routing fields (`vendorName`, `page-id`, `current-date`) sit between rows on
 * several forms, and a label must not end up pointing at one of them.
 *
 * @param string $html Markup to search.
 * @return array|null offset and tag, or null.
 */
function rv_cf7_first_visible_control( $html ) {
	if ( ! preg_match_all( '#<(?:input|select|textarea)\b[^>]*>#i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
		return null;
	}

	foreach ( $m[0] as $match ) {
		if ( preg_match( '#\stype=(["\'])(?:hidden|submit|button)\1#i', $match[0] ) ) {
			continue;
		}

		return array(
			'offset' => $match[1],
			'tag'    => $match[0],
		);
	}

	return null;
}
