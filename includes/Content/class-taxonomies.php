<?php
/**
 * Registers Lunar Wiki taxonomies.
 *
 * @package Lunar\Content
 */

namespace Lunar\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Taxonomies {

	private const SLUG_GAME = 'game';

	private const SLUG_CONTENT_TYPE = 'content_type';

	public function init(): void {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register(): void {
		$this->register_game();
		$this->register_content_type();
	}

	private function register_game(): void {
		$labels = array(
			'name'          => __( 'Games', 'lunar-wiki' ),
			'singular_name' => __( 'Game', 'lunar-wiki' ),
			'search_items'  => __( 'Search Games', 'lunar-wiki' ),
			'all_items'     => __( 'All Games', 'lunar-wiki' ),
			'parent_item'   => __( 'Parent Franchise', 'lunar-wiki' ),
			'edit_item'     => __( 'Edit Game', 'lunar-wiki' ),
			'add_new_item'  => __( 'Add New Game', 'lunar-wiki' ),
			'menu_name'     => __( 'Game', 'lunar-wiki' ),
		);

		register_taxonomy(
			self::SLUG_GAME,
			array( Post_Types::get_slug() ),
			array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'         => 'game',
					'hierarchical' => true,
					'with_front'   => false,
				),
			)
		);
	}

	private function register_content_type(): void {
		$labels = array(
			'name'          => __( 'Content Types', 'lunar-wiki' ),
			'singular_name' => __( 'Content Type', 'lunar-wiki' ),
			'search_items'  => __( 'Search Content Types', 'lunar-wiki' ),
			'all_items'     => __( 'All Content Types', 'lunar-wiki' ),
			'edit_item'     => __( 'Edit Content Type', 'lunar-wiki' ),
			'update_item'   => __( 'Update Content Type', 'lunar-wiki' ),
			'add_new_item'  => __( 'Add New Content Type', 'lunar-wiki' ),
			'new_item_name' => __( 'New Content Type Name', 'lunar-wiki' ),
			'menu_name'     => __( 'Content Type', 'lunar-wiki' ),
		);

		register_taxonomy(
			self::SLUG_CONTENT_TYPE,
			array( Post_Types::get_slug() ),
			array(
				'labels'            => $labels,
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       => 'content-type',
					'with_front' => false,
				),
			)
		);
	}

	public static function get_slug_game(): string {
		return self::SLUG_GAME;
	}

	public static function get_slug_content_type(): string {
		return self::SLUG_CONTENT_TYPE;
	}
}