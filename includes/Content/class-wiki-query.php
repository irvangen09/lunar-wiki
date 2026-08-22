<?php
/**
 * High-level query helpers for wiki_article, wrapping WP_Query and term
 * lookups so consumers never need to know the underlying post type or
 * taxonomy slugs directly.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wiki_Query {

	/**
	 * Query wiki_article posts. Any $args are merged on top of sane
	 * defaults, but post_type is always forced to the wiki_article slug
	 * regardless of what is passed in.
	 *
	 * @param array<string, mixed> $args Extra/override WP_Query args.
	 * @return \WP_Query
	 */
	public static function query_wiki_articles( array $args = array() ): \WP_Query {
		$defaults = array(
			'post_type'      => Post_Types::get_slug(),
			'posts_per_page' => get_option( 'posts_per_page' ),
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = Post_Types::get_slug();

		return new \WP_Query( $args );
	}

	/**
	 * Get the content_type terms actually used by wiki_article posts
	 * tagged with a given game term, deduplicated.
	 *
	 * @param int $game_term_id Term ID in the game taxonomy.
	 * @return array<int, \WP_Term>
	 */
	public static function get_content_type_terms( int $game_term_id ): array {
		$post_ids = get_posts(
			array(
				'post_type'              => Post_Types::get_slug(),
				'posts_per_page'         => -1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'tax_query'              => array(
					array(
						'taxonomy' => Taxonomies::get_slug_game(),
						'field'    => 'term_id',
						'terms'    => $game_term_id,
					),
				),
			)
		);

		if ( empty( $post_ids ) ) {
			return array();
		}

		$terms = wp_get_object_terms(
			$post_ids,
			Taxonomies::get_slug_content_type(),
			array(
				'orderby' => 'name',
				'order'   => 'ASC',
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		return $terms;
	}
}