<?php
/**
 * Seeds the initial "Role" term into the wiki_field taxonomy.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Field_Terms_Seeder {

	private const SEEDED_OPTION = 'lunar_wiki_field_terms_seeded';

	private const INITIAL_TERM_NAME = 'Role';

	public function init(): void {
		add_action( 'admin_init', array( $this, 'maybe_seed' ) );
	}

	public function maybe_seed(): void {
		if ( get_option( self::SEEDED_OPTION ) ) {
			return;
		}

		if ( ! taxonomy_exists( Taxonomies::get_slug_field() ) ) {
			return;
		}

		$existing = term_exists( self::INITIAL_TERM_NAME, Taxonomies::get_slug_field() );

		if ( ! $existing ) {
			$result = wp_insert_term( self::INITIAL_TERM_NAME, Taxonomies::get_slug_field() );

			if ( is_wp_error( $result ) ) {
				return;
			}
		}

		update_option( self::SEEDED_OPTION, true );
	}
}