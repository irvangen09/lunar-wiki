<?php
/**
 * Public API for Lunar Wiki, consumed by Lunar Theme and Lunar SEO.
 *
 * @package Lunar\Wiki
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string
 */
function lunar_wiki_get_post_type_slug(): string {
	return \Lunar\Content\Post_Types::get_slug();
}

/**
 * @return string
 */
function lunar_wiki_get_taxonomy_slug_game(): string {
	return \Lunar\Content\Taxonomies::get_slug_game();
}

/**
 * @return string
 */
function lunar_wiki_get_taxonomy_slug_content_type(): string {
	return \Lunar\Content\Taxonomies::get_slug_content_type();
}

/**
 * @return string
 */
function lunar_wiki_get_taxonomy_slug_field(): string {
	return \Lunar\Content\Taxonomies::get_slug_field();
}

/**
 * @param int|\WP_Post|null $post Post ID, object, or null for the current global $post.
 * @return bool
 */
function lunar_wiki_is_wiki_article( $post = null ): bool {
	$post = get_post( $post );

	if ( ! $post instanceof \WP_Post ) {
		return false;
	}

	return $post->post_type === \Lunar\Content\Post_Types::get_slug();
}

/**
 * @return array<string, string> Term slug => term label.
 */
function lunar_wiki_get_recognized_fields(): array {
	return ( new \Lunar\Content\Meta_Fields() )->get_recognized_fields();
}

/**
 * @param int $term_id Term ID in the wiki_field taxonomy.
 * @return string|null
 */
function lunar_wiki_get_field_label( int $term_id ): ?string {
	return ( new \Lunar\Content\Meta_Fields() )->get_field_label( $term_id );
}

/**
 * @param string $slug Term slug in the wiki_field taxonomy.
 * @return string
 */
function lunar_wiki_get_field_meta_key( string $slug ): string {
	return ( new \Lunar\Content\Meta_Fields() )->get_meta_key( $slug );
}

/**
 * @param int $user_id User ID.
 * @return string
 */
function lunar_wiki_get_author_role( int $user_id ): string {
	return '';
}

/**
 * @param int $user_id User ID.
 * @return array<int, array{label: string, url: string, icon: string}>
 */
function lunar_wiki_get_author_social_links( int $user_id ): array {
	return array();
}

/**
 * @return string
 */
function lunar_wiki_get_game_menu_meta_key(): string {
	return \Lunar\Content\Game_Menu_Meta::get_meta_key();
}

/**
 * @return string
 */
function lunar_wiki_get_game_tile_url_meta_key(): string {
	return \Lunar\Content\Game_Tile_Meta::get_url_meta_key();
}

/**
 * @return string
 */
function lunar_wiki_get_game_tile_image_meta_key(): string {
	return \Lunar\Content\Game_Tile_Meta::get_image_meta_key();
}