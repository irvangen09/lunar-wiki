<?php
/**
 * Plugin Name:       Lunar Wiki
 * Plugin URI:        https://github.com/irvangen09/lunar-wiki
 * Description:       Data domain plugin for game documentation sites — Custom Post Type, taxonomies, metadata, and a public API consumed by Lunar Theme and (optionally) Lunar SEO.
 * Version:           0.1.0
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Author:            Irvan Noerfazri
 * Author URI:        https://github.com/irvangen09
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lunar-wiki
 *
 * @package Lunar\Wiki
 */

namespace Lunar\Wiki;

use Lunar\Wiki\Content\Post_Types;
use Lunar\Wiki\Content\Taxonomies;
use Lunar\Wiki\Content\Field_Terms_Seeder;
use Lunar\Wiki\Content\Meta_Sync;
use Lunar\Wiki\Content\Infobox_Integration;
use Lunar\Wiki\Content\Game_Menu_Meta;
use Lunar\Wiki\Content\Game_Tile_Meta;
use Lunar\Wiki\Content\Update_Notes_Meta;
use Lunar\Wiki\Content\Seo_Integration;
use Lunar\Wiki\Users\Author_Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LUNAR_WIKI_VERSION', '0.1.0' );
define( 'LUNAR_WIKI_PLUGIN_FILE', __FILE__ );
define( 'LUNAR_WIKI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LUNAR_WIKI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * @return bool True if the environment is supported.
 */
function environment_is_supported(): bool {
	if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
		add_action(
			'admin_notices',
			function () {
				printf(
					'<div class="notice notice-error"><p>%s</p></div>',
					esc_html__( 'Lunar Wiki requires PHP 8.0 or higher and could not be loaded.', 'lunar-wiki' )
				);
			}
		);
		return false;
	}
	return true;
}

if ( ! environment_is_supported() ) {
	return;
}

require_once LUNAR_WIKI_PLUGIN_DIR . 'includes/public-api.php';

/**
 * Maps Lunar\Wiki\Segment\Class_Name to includes/Segment/class-class-name.php.
 *
 * @param string $class_name Fully qualified class name.
 */
function autoload( string $class_name ): void {
	if ( ! str_starts_with( $class_name, 'Lunar\\Wiki\\' ) ) {
		return;
	}

	$relative  = substr( $class_name, strlen( 'Lunar\\Wiki\\' ) );
	$parts     = explode( '\\', $relative );
	$file_name = 'class-' . strtolower( str_replace( '_', '-', array_pop( $parts ) ) ) . '.php';
	$path      = LUNAR_WIKI_PLUGIN_DIR . 'includes/' . implode( '/', $parts ) . '/' . $file_name;

	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
spl_autoload_register( __NAMESPACE__ . '\\autoload' );

function bootstrap(): void {
	( new Post_Types() )->init();
	( new Taxonomies() )->init();
	( new Field_Terms_Seeder() )->init();
	( new Meta_Sync() )->init();
	( new Infobox_Integration() )->init();
	( new Seo_Integration() )->init();
	( new Game_Menu_Meta() )->init();
	( new Game_Tile_Meta() )->init();
	( new Update_Notes_Meta() )->init();
	( new Author_Fields() )->init();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\bootstrap' );

function activate(): void {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

function deactivate(): void {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );