<?php
/**
 * Registers the secondary-menu term meta on the game taxonomy.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

use Lunar\Wiki\Services\Nonce_Verifier;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Game_Menu_Meta {

	private const META_KEY = 'lunar_wiki_secondary_menu_id';

	private const NONCE_ACTION = 'lunar_wiki_game_menu_meta_action';
	private const NONCE_FIELD  = 'lunar_wiki_game_menu_meta_nonce';

	public function init(): void {
		$taxonomy = Taxonomies::get_slug_game();

		add_action( "{$taxonomy}_add_form_fields", array( $this, 'render_add_field' ) );
		add_action( "{$taxonomy}_edit_form_fields", array( $this, 'render_edit_field' ) );
		add_action( "created_{$taxonomy}", array( $this, 'save_meta' ) );
		add_action( "edited_{$taxonomy}", array( $this, 'save_meta' ) );
		add_filter( 'pll_get_taxonomies', array( $this, 'register_translatable' ) );
	}

	public function render_add_field(): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
		?>
		<div class="form-field">
			<label for="lunar-wiki-secondary-menu"><?php esc_html_e( 'Secondary Menu', 'lunar-wiki' ); ?></label>
			<?php $this->render_dropdown( 0 ); ?>
			<p>
				<?php esc_html_e( 'Navigation menu shown by the Theme for articles under this game title. Leave empty for a Franchise (parent term), or to use the default menu.', 'lunar-wiki' ); ?>
			</p>
		</div>
		<?php
	}

	public function render_edit_field( \WP_Term $term ): void {
		if ( 0 === (int) $term->parent ) {
			return;
		}

		$selected = (int) get_term_meta( $term->term_id, self::META_KEY, true );
		?>
		<tr class="form-field">
			<th scope="row">
				<label for="lunar-wiki-secondary-menu"><?php esc_html_e( 'Secondary Menu', 'lunar-wiki' ); ?></label>
			</th>
			<td>
				<?php
				wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
				$this->render_dropdown( $selected );
				?>
				<p class="description">
					<?php esc_html_e( 'Navigation menu shown by the Theme for articles under this game title. Leave empty to use the default menu.', 'lunar-wiki' ); ?>
				</p>
			</td>
		</tr>
		<?php
	}

	private function render_dropdown( int $selected ): void {
		$menus = wp_get_nav_menus();
		?>
		<select name="lunar-wiki-secondary-menu" id="lunar-wiki-secondary-menu">
			<option value="0"><?php esc_html_e( '— Use default menu —', 'lunar-wiki' ); ?></option>
			<?php foreach ( $menus as $menu ) : ?>
				<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $selected, $menu->term_id ); ?>>
					<?php echo esc_html( $menu->name ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function save_meta( int $term_id ): void {
		if ( ! Nonce_Verifier::is_valid( self::NONCE_FIELD, self::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_categories' ) ) {
			return;
		}

		if ( ! isset( $_POST['lunar-wiki-secondary-menu'] ) ) {
			return;
		}

		$menu_id = absint( $_POST['lunar-wiki-secondary-menu'] );

		if ( $menu_id > 0 ) {
			update_term_meta( $term_id, self::META_KEY, $menu_id );
		} else {
			delete_term_meta( $term_id, self::META_KEY );
		}
	}

	/**
	 * @param string[] $taxonomies Taxonomy slugs already marked translatable.
	 * @return string[]
	 */
	public function register_translatable( array $taxonomies ): array {
		$taxonomies[] = Taxonomies::get_slug_game();
		$taxonomies[] = Taxonomies::get_slug_content_type();

		return array_unique( $taxonomies );
	}

	public static function get_meta_key(): string {
		return self::META_KEY;
	}
}