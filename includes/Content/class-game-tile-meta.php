<?php
/**
 * Registers the custom URL and image term meta on the game taxonomy.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

use Lunar\Wiki\Services\Nonce_Verifier;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Game_Tile_Meta {

	private const URL_META_KEY = 'lunar_wiki_game_tile_url';

	private const IMAGE_META_KEY = 'lunar_wiki_game_tile_image_id';

	private const NONCE_ACTION = 'lunar_wiki_game_tile_meta_action';
	private const NONCE_FIELD  = 'lunar_wiki_game_tile_meta_nonce';

	public function init(): void {
		$taxonomy = Taxonomies::get_slug_game();

		add_action( "{$taxonomy}_edit_form_fields", array( $this, 'render_edit_fields' ) );
		add_action( "edited_{$taxonomy}", array( $this, 'save_meta' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	public function render_edit_fields( \WP_Term $term ): void {
		if ( 0 === (int) $term->parent ) {
			return;
		}

		$url       = (string) get_term_meta( $term->term_id, self::URL_META_KEY, true );
		$image_id  = (int) get_term_meta( $term->term_id, self::IMAGE_META_KEY, true );
		$image_src = $image_id > 0 ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="lunar-wiki-game-tile-url"><?php esc_html_e( 'Custom Destination URL', 'lunar-wiki' ); ?></label>
			</th>
			<td>
				<input type="text" class="regular-text" id="lunar-wiki-game-tile-url" name="lunar-wiki-game-tile-url" value="<?php echo esc_attr( $url ); ?>" placeholder="/story-of-seasons-fomt/">
				<p class="description">
					<?php esc_html_e( 'Leave empty to use the default game archive link. Fill in to point the Game Tile to another page, e.g. a Pillar Article.', 'lunar-wiki' ); ?>
				</p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row">
				<label for="lunar-wiki-game-tile-select"><?php esc_html_e( 'Game Tile Media', 'lunar-wiki' ); ?></label>
			</th>
			<td>
				<div class="lunar-wiki-game-tile-media">
					<img
						id="lunar-wiki-game-tile-preview"
						src="<?php echo esc_url( $image_src ); ?>"
						style="max-width:120px;max-height:120px;display:<?php echo $image_src ? 'block' : 'none'; ?>;margin-bottom:8px;"
						alt=""
					>
					<input type="hidden" id="lunar-wiki-game-tile-image-id" name="lunar-wiki-game-tile-image-id" value="<?php echo esc_attr( $image_id ); ?>">
					<p>
						<button type="button" class="button" id="lunar-wiki-game-tile-select">
							<?php esc_html_e( 'Select Image', 'lunar-wiki' ); ?>
						</button>
						<button type="button" class="button" id="lunar-wiki-game-tile-remove" style="<?php echo $image_id ? '' : 'display:none;'; ?>">
							<?php esc_html_e( 'Remove Image', 'lunar-wiki' ); ?>
						</button>
					</p>
				</div>
				<p class="description">
					<?php esc_html_e( 'Leave empty to use the default initial-letter placeholder. Suitable for a logo, icon, or game cover.', 'lunar-wiki' ); ?>
				</p>
			</td>
		</tr>
		<?php
	}

	public function save_meta( int $term_id ): void {
		if ( ! Nonce_Verifier::is_valid( self::NONCE_FIELD, self::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_categories' ) ) {
			return;
		}

		if ( isset( $_POST['lunar-wiki-game-tile-url'] ) ) {
			$url = esc_url_raw( wp_unslash( $_POST['lunar-wiki-game-tile-url'] ) );

			if ( '' !== $url ) {
				update_term_meta( $term_id, self::URL_META_KEY, $url );
			} else {
				delete_term_meta( $term_id, self::URL_META_KEY );
			}
		}

		if ( isset( $_POST['lunar-wiki-game-tile-image-id'] ) ) {
			$image_id = absint( $_POST['lunar-wiki-game-tile-image-id'] );

			if ( $image_id > 0 ) {
				update_term_meta( $term_id, self::IMAGE_META_KEY, $image_id );
			} else {
				delete_term_meta( $term_id, self::IMAGE_META_KEY );
			}
		}
	}

	public function enqueue_admin_assets(): void {
		$screen = get_current_screen();

		if ( ! $screen || Taxonomies::get_slug_game() !== $screen->taxonomy ) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_script(
			'lunar-wiki-game-tile-meta',
			LUNAR_WIKI_PLUGIN_URL . 'assets/admin/game-tile-meta.js',
			array( 'media-editor' ),
			LUNAR_WIKI_VERSION,
			true
		);
	}

	public static function get_url_meta_key(): string {
		return self::URL_META_KEY;
	}

	public static function get_image_meta_key(): string {
		return self::IMAGE_META_KEY;
	}
}