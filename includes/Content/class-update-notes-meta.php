<?php
/**
 * Registers the optional update notes meta box on the Wiki Article edit screen.
 *
 * @package Lunar\Wiki\Content
 */

namespace Lunar\Wiki\Content;

use Lunar\Wiki\Services\Nonce_Verifier;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Update_Notes_Meta {

	private const META_KEY = 'lunar_wiki_update_notes';

	private const NONCE_ACTION = 'lunar_wiki_update_notes_action';
	private const NONCE_FIELD  = 'lunar_wiki_update_notes_nonce';

	public function init(): void {
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

		$post_type = Post_Types::get_slug();
		add_action( "save_post_{$post_type}", array( $this, 'save_meta_box' ) );
	}

	public function register_meta(): void {
		register_post_meta(
			Post_Types::get_slug(),
			self::META_KEY,
			array(
				'single'            => true,
				'type'              => 'string',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_textarea_field',
				'auth_callback'     => function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);
	}

	public function add_meta_box(): void {
		add_meta_box(
			'lunar-wiki-update-notes',
			__( 'Update Notes', 'lunar-wiki' ),
			array( $this, 'render_meta_box' ),
			Post_Types::get_slug(),
			'side',
			'default'
		);
	}

	public function render_meta_box( \WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );

		$value = get_post_meta( $post->ID, self::META_KEY, true );
		?>
		<p>
			<textarea
				name="lunar-wiki-update-notes"
				id="lunar-wiki-update-notes"
				rows="5"
				style="width: 100%;"
			><?php echo esc_textarea( $value ); ?></textarea>
		</p>
		<p class="description">
			<?php esc_html_e( 'Optional. One line per note, newest first. Leave empty if there is nothing notable about this update.', 'lunar-wiki' ); ?>
		</p>
		<?php
	}

	public function save_meta_box( int $post_id ): void {
		if ( ! Nonce_Verifier::is_valid( self::NONCE_FIELD, self::NONCE_ACTION ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['lunar-wiki-update-notes'] ) ) {
			return;
		}

		$value = sanitize_textarea_field( wp_unslash( $_POST['lunar-wiki-update-notes'] ) );

		if ( '' === trim( $value ) ) {
			delete_post_meta( $post_id, self::META_KEY );
		} else {
			update_post_meta( $post_id, self::META_KEY, $value );
		}
	}

	public static function get_meta_key(): string {
		return self::META_KEY;
	}
}