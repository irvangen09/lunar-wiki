<?php
/**
 * Reads and derives values from the wiki_field taxonomy.
 *
 * @package Lunar\Content
 */

namespace Lunar\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meta_Fields {

	private const META_KEY_PREFIX = 'lunar_wiki_field_';

	public function get_meta_key( string $slug ): string {
		return self::META_KEY_PREFIX . $slug;
	}

	/**
	 * @return array<string, string> Term slug => term label.
	 */
	public function get_recognized_fields(): array {
		$terms = get_terms(
			array(
				'taxonomy'   => Taxonomies::get_slug_field(),
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return wp_list_pluck( $terms, 'name', 'slug' );
	}

	public function get_field_label( int $term_id ): ?string {
		$term = get_term( $term_id, Taxonomies::get_slug_field() );

		if ( ! $term instanceof \WP_Term ) {
			return null;
		}

		return $term->name;
	}
}