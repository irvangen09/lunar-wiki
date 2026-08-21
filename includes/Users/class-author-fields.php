<?php
/**
 * Adds Author Box fields (Role, Social Links) to the WordPress user profile screen.
 *
 * @package Lunar\Users
 */

namespace Lunar\Users;

use Lunar\Services\Nonce_Verifier;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Author_Fields {

	private const ROLE_META_KEY = 'lunar_wiki_author_role';

	private const SOCIAL_META_KEY = 'lunar_wiki_author_social_links';

	private const NONCE_ACTION = 'lunar_wiki_author_fields_action';
	private const NONCE_FIELD  = 'lunar_wiki_author_fields_nonce';

	public function init(): void {
		add_action( 'show_user_profile', array( $this, 'render_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'render_fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_fields' ) );
	}

	public function render_fields( \WP_User $user ): void {
		if ( ! current_user_can( 'edit_user', $user->ID ) ) {
			return;
		}

		$role_value   = get_user_meta( $user->ID, self::ROLE_META_KEY, true );
		$social_value = get_user_meta( $user->ID, self::SOCIAL_META_KEY, true );

		wp_nonce_field( self::NONCE_ACTION, self::NONCE_FIELD );
		?>
		<h2><?php esc_html_e( 'Author Box (Lunar)', 'lunar-wiki' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="lunar-wiki-author-role"><?php esc_html_e( 'Role / Title', 'lunar-wiki' ); ?></label></th>
				<td>
					<input
						type="text"
						name="lunar-wiki-author-role"
						id="lunar-wiki-author-role"
						class="regular-text"
						value="<?php echo esc_attr( $role_value ); ?>"
					/>
					<p class="description">
						<?php esc_html_e( 'Optional. Free text, e.g. "Writer", "Editor", "Community Contributor".', 'lunar-wiki' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th><label for="lunar-wiki-author-social"><?php esc_html_e( 'Social Media Links', 'lunar-wiki' ); ?></label></th>
				<td>
					<textarea
						name="lunar-wiki-author-social"
						id="lunar-wiki-author-social"
						rows="5"
						class="large-text"
					><?php echo esc_textarea( $social_value ); ?></textarea>
					<p class="description">
						<?php esc_html_e( 'Optional. One line per link, format: Label | URL (e.g. "Instagram | https://instagram.com/username"). The icon is guessed from the label; unrecognized platforms get a generic link icon.', 'lunar-wiki' ); ?>
					</p>
				</td>
			</tr>
		</table>
		<?php
	}

	public function save_fields( int $user_id ): void {
		if ( ! Nonce_Verifier::is_valid( self::NONCE_FIELD, self::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		if ( isset( $_POST['lunar-wiki-author-role'] ) ) {
			$role = sanitize_text_field( wp_unslash( $_POST['lunar-wiki-author-role'] ) );

			if ( '' === $role ) {
				delete_user_meta( $user_id, self::ROLE_META_KEY );
			} else {
				update_user_meta( $user_id, self::ROLE_META_KEY, $role );
			}
		}

		if ( isset( $_POST['lunar-wiki-author-social'] ) ) {
			$social = sanitize_textarea_field( wp_unslash( $_POST['lunar-wiki-author-social'] ) );

			if ( '' === trim( $social ) ) {
				delete_user_meta( $user_id, self::SOCIAL_META_KEY );
			} else {
				update_user_meta( $user_id, self::SOCIAL_META_KEY, $social );
			}
		}
	}

	public static function get_role( int $user_id ): string {
		return (string) get_user_meta( $user_id, self::ROLE_META_KEY, true );
	}

	/**
	 * @param int $user_id User ID.
	 * @return array<int, array{label: string, url: string, icon: string}>
	 */
	public static function get_social_links( int $user_id ): array {
		$raw = (string) get_user_meta( $user_id, self::SOCIAL_META_KEY, true );

		if ( '' === trim( $raw ) ) {
			return array();
		}

		$links = array();

		foreach ( explode( "\n", $raw ) as $line ) {
			$line = trim( $line );

			if ( '' === $line || ! str_contains( $line, '|' ) ) {
				continue;
			}

			list( $label, $url ) = array_map( 'trim', explode( '|', $line, 2 ) );
			$url                 = esc_url_raw( $url );

			if ( '' === $label || '' === $url ) {
				continue;
			}

			$links[] = array(
				'label' => $label,
				'url'   => $url,
				'icon'  => self::detect_icon( $label ),
			);
		}

		return $links;
	}

	private static function detect_icon( string $label ): string {
		$label = strtolower( $label );

		if ( 'x' === $label || str_contains( $label, 'twitter' ) ) {
			return 'dashicons-twitter';
		}

		$keyword_map = array(
			'facebook'  => 'dashicons-facebook',
			'instagram' => 'dashicons-instagram',
			'whatsapp'  => 'dashicons-whatsapp',
			'linkedin'  => 'dashicons-linkedin',
			'youtube'   => 'dashicons-youtube',
			'email'     => 'dashicons-email',
			'website'   => 'dashicons-admin-site',
		);

		foreach ( $keyword_map as $keyword => $icon ) {
			if ( str_contains( $label, $keyword ) ) {
				return $icon;
			}
		}

		return 'dashicons-admin-links';
	}
}