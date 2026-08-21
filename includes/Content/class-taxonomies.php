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

	public function init(): void {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register(): void {
		$this->register_game();
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

	public static function get_slug_game(): string {
		return self::SLUG_GAME;
	}
}