<?php
/**
 * Registers wiki_article with Lunar SEO's supported post types filter,
 * per LUNAR_SEO_WIKI_INTEGRATION_CONTRACT.md.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Seo_Integration {

	public function init(): void {
		add_filter( 'lunar_seo_supported_post_types', array( $this, 'register_post_type' ) );
	}

	/**
	 * @param string[] $post_types Post type slugs already registered by other providers.
	 * @return string[]
	 */
	public function register_post_type( array $post_types ): array {
		$post_types[] = Post_Types::get_slug();

		return $post_types;
	}
}