<?php
/**
 * Media and upload governance.
 *
 * The theme previously had no image or
 * upload filter of any kind -- no add_image_size(), no quality setting, no
 * threshold -- so every sizing decision came from core defaults and nothing
 * stopped print-resolution masters accumulating.
 *
 * The problem this solves: uploads/ had grown to 26.7GB, of which 15GB was
 * full-resolution masters that WordPress had already superseded with "-scaled"
 * copies and never served again. Pruning them once is a one-off
 * (audit/scripts/media/prune-masters.php); these filters are what stop the
 * 15GB coming back.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Longest edge, in pixels, that any uploaded image is stored at.
 *
 * 2560 is WordPress's own default and comfortably covers a full-bleed hero
 * banner on a 2x retina desktop display. It is pinned here so that a plugin
 * raising or disabling the threshold cannot silently reintroduce multi-gigabyte
 * uploads.
 */
const RV_MAX_IMAGE_EDGE = 2560;

/**
 * JPEG/WebP encode quality.
 *
 * 82 matches core's default and this library's existing "-scaled" output, so
 * newly uploaded images are visually consistent with the 6,000+ already here.
 */
const RV_IMAGE_QUALITY = 82;

/**
 * Pin the big-image threshold.
 *
 * @param int $threshold Incoming threshold.
 * @return int
 */
function rv_big_image_size_threshold( $threshold ) {
	unset( $threshold );
	return RV_MAX_IMAGE_EDGE;
}
add_filter( 'big_image_size_threshold', 'rv_big_image_size_threshold', 99 );

/**
 * Pin encode quality for lossy formats.
 *
 * @param int    $quality Incoming quality.
 * @param string $mime    Mime type being encoded.
 * @return int
 */
function rv_image_quality( $quality, $mime ) {
	if ( in_array( $mime, array( 'image/jpeg', 'image/webp' ), true ) ) {
		return RV_IMAGE_QUALITY;
	}

	return $quality;
}
add_filter( 'wp_editor_set_quality', 'rv_image_quality', 99, 2 );

/**
 * Discard the full-resolution master once WordPress has produced the scaled copy.
 *
 * When an upload exceeds the threshold, core writes a "-scaled" version, points
 * _wp_attached_file at it, and keeps the original on disk indefinitely --
 * recorded as $metadata['original_image'] and read only by the admin image
 * editor's re-crop. That retention is what produced 15GB of files no visitor
 * has ever downloaded.
 *
 * Re-cropping still works after this: the media editor falls back to the
 * 2560px scaled copy, which is ample for every rendered size on this site (the
 * largest registered size is 2048x2048).
 *
 * Filterable so it can be disabled per-attachment if a genuine archival master
 * is ever needed:
 *
 *     add_filter( 'rv_keep_original_image', fn() => true );
 *
 * @param array $metadata      Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function rv_discard_original_image( $metadata, $attachment_id ) {
	if ( ! is_array( $metadata ) || empty( $metadata['original_image'] ) ) {
		return $metadata;
	}

	/**
	 * Allow the full-resolution master to be kept for this attachment.
	 *
	 * @param bool  $keep          Whether to keep the master. Default false.
	 * @param int   $attachment_id Attachment ID.
	 * @param array $metadata      Attachment metadata.
	 */
	if ( apply_filters( 'rv_keep_original_image', false, $attachment_id, $metadata ) ) {
		return $metadata;
	}

	$served = get_attached_file( $attachment_id );
	$master = wp_get_original_image_path( $attachment_id );

	// Never delete the file that is actually being served.
	if ( ! $served || ! $master || wp_normalize_path( $served ) === wp_normalize_path( $master ) ) {
		return $metadata;
	}

	// Only proceed once the scaled copy is confirmed present and readable --
	// otherwise this would destroy the only copy of the image.
	if ( ! file_exists( $served ) || false === @getimagesize( $served ) ) {
		return $metadata;
	}

	if ( file_exists( $master ) ) {
		wp_delete_file( $master );
	}

	unset( $metadata['original_image'] );

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'rv_discard_original_image', 99, 2 );
