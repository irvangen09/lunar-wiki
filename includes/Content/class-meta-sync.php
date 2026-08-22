<?php
/**
 * Syncs Infobox field data from Lunar Blocks into wiki_article post meta.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meta_Sync {

	public function init(): void {
		add_action( 'lunar_blocks_infobox_field_saved', array( $this, 'sync_field' ), 10, 3 );
	}

	public function sync_field( int $post_id, array $field_data, \WP_Post $post ): void {
		if ( $post->post_type !== Post_Types::get_slug() ) {
			return;
		}

		if ( empty( $field_data['field_source_id'] ) ) {
			return;
		}

		$term = get_term( (int) $field_data['field_source_id'], Taxonomies::get_slug_field() );

		if ( ! $term instanceof \WP_Term ) {
			return;
		}

		$meta_fields = new Meta_Fields();

		update_post_meta(
			$post_id,
			$meta_fields->get_meta_key( $term->slug ),
			sanitize_text_field( $field_data['value'] ?? '' )
		);
	}
}