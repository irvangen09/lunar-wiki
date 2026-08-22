<?php
/**
 * Registers the Wiki Article custom post type.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Types {

	private const SLUG = 'wiki_article';

	public function init(): void {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register(): void {
		$labels = array(
			'name'               => __( 'Wiki Articles', 'lunar-wiki' ),
			'singular_name'      => __( 'Wiki Article', 'lunar-wiki' ),
			'add_new'            => __( 'Add New', 'lunar-wiki' ),
			'add_new_item'       => __( 'Add New Wiki Article', 'lunar-wiki' ),
			'edit_item'          => __( 'Edit Wiki Article', 'lunar-wiki' ),
			'new_item'           => __( 'New Wiki Article', 'lunar-wiki' ),
			'view_item'          => __( 'View Wiki Article', 'lunar-wiki' ),
			'view_items'         => __( 'View Wiki Articles', 'lunar-wiki' ),
			'search_items'       => __( 'Search Wiki Articles', 'lunar-wiki' ),
			'not_found'          => __( 'No wiki articles found.', 'lunar-wiki' ),
			'not_found_in_trash' => __( 'No wiki articles found in Trash.', 'lunar-wiki' ),
			'all_items'          => __( 'All Wiki Articles', 'lunar-wiki' ),
			'archives'           => __( 'Wiki Article Archives', 'lunar-wiki' ),
			'menu_name'          => __( 'Wiki Articles', 'lunar-wiki' ),
		);

		register_post_type(
			self::SLUG,
			array(
				'labels'          => $labels,
				'public'          => true,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'show_in_rest'    => true,
				'menu_icon'       => 'dashicons-book-alt',
				'supports'        => array( 'title', 'editor', 'custom-fields' ),
				'has_archive'     => false,
				'query_var'       => true,
				'capability_type' => 'post',
				'rewrite'         => array(
					'slug'       => 'wiki',
					'with_front' => false,
				),
			)
		);
	}

	public static function get_slug(): string {
		return self::SLUG;
	}
}