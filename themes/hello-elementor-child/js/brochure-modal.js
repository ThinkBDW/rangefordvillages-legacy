/**
 * Brochure modals -- the open/close behaviour popup-maker used to provide.
 *
 * Written against jQuery deliberately. inc/cf7/redirects.php closes the popup
 * after a successful submission with
 * `$submittedPopup.find('.popmake-close').trigger('click')`, and a jQuery
 * synthetic trigger is not guaranteed to reach a natively-bound listener.
 * Binding here with jQuery keeps that path working without editing it.
 */
(function ($) {
	'use strict';

	var OPEN_CLASS = 'rv-modal-open';

	function overlayFor(id) {
		return $('.rv-modal-overlay[data-rv-modal="' + id + '"]');
	}

	function open(id, $trigger) {
		var $overlay = overlayFor(id);
		if (!$overlay.length) {
			return false;
		}

		// inc/cf7/redirects.php reads the PDF URL and the village/type
		// redirect back off window.lastClickedBrochureButton after the form
		// is submitted. js/custom.js already sets it on the same click; set
		// it here too so the modal works even if that handler is skipped.
		if ($trigger && $trigger.length) {
			window.lastClickedBrochureButton = $trigger;
		}

		$overlay.attr('aria-hidden', 'false').addClass(OPEN_CLASS);
		$('body').addClass('rv-modal-is-open');

		// Match popup-maker: focus the first field so keyboard users land
		// inside the form rather than behind it.
		var $first = $overlay.find('input:visible, select:visible, textarea:visible').first();
		if ($first.length) {
			$first.trigger('focus');
		}

		return true;
	}

	function close($overlay) {
		$overlay.attr('aria-hidden', 'true').removeClass(OPEN_CLASS);
		if (!$('.rv-modal-overlay.' + OPEN_CLASS).length) {
			$('body').removeClass('rv-modal-is-open');
		}
	}

	$(function () {
		// popup-maker's own trigger contract: .popmake-{id} / data-popmake.
		$(document).on('click', '[data-popmake]', function (event) {
			var id = $(this).data('popmake');
			if (open(id, $(this))) {
				event.preventDefault();
			}
		});

		// Popup 10766's extra_selectors. The mega-menu panels and
		// single-properties.php emit this class WITHOUT a data-popmake
		// attribute, so it needs its own binding. Guarded so a button that
		// carries both does not open two modals.
		$(document).on('click', '.single-brochure-view-button', function (event) {
			if ($(this).is('[data-popmake]')) {
				return;
			}
			if (open(10766, $(this))) {
				event.preventDefault();
			}
		});

		$(document).on('click', '.popmake-close', function (event) {
			event.preventDefault();
			close($(this).closest('.rv-modal-overlay'));
		});

		// Click the backdrop, not the panel.
		$(document).on('click', '.rv-modal-overlay', function (event) {
			if (event.target === this) {
				close($(this));
			}
		});

		$(document).on('keydown', function (event) {
			if (event.key === 'Escape') {
				close($('.rv-modal-overlay.' + OPEN_CLASS));
			}
		});
	});
	// js/custom.js binds these buttons directly and used to hand off to
	// PUM.open() for anything carrying data-popmake. That global disappeared
	// with the plugin, so it calls this instead.
	window.rvBrochureModal = {
		open: function (id) {
			return open(id, null);
		},
		close: function (id) {
			close(id ? overlayFor(id) : $('.rv-modal-overlay.' + OPEN_CLASS));
		}
	};
})(jQuery);
