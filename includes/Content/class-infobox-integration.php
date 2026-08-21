<?php
/**
 * Registers Lunar Wiki as a provider for the Lunar Blocks Infobox extension point.
 *
 * @package Lunar\Content
 */

namespace Lunar\Content;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Infobox_Integration {

	public function init(): void {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );
	}

	public function enqueue_assets(): void {
		wp_enqueue_script(
			'lunar-wiki-infobox-field-source',
			LUNAR_WIKI_PLUGIN_URL . 'assets/js/infobox-field-source.js',
			array( 'wp-hooks' ),
			LUNAR_WIKI_VERSION,
			true
		);
	}
}