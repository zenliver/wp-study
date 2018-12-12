<?php

/**
* @package Publish Confirm
* @author - pluginkollektiv
*
* @author URI - https://github.com/pluginkollektiv/publish-confirm
*/

/** Quit. */
defined( 'ABSPATH' ) || exit;

class AIOWS_Publish_Confirm {

	protected static $instance;

	public static function get_instance() {

		if ( ! self::$instance instanceof self ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	
	public function __construct() {
	}

	
	public function setup() {

		// Check user role.
		if ( ! current_user_can( 'publish_posts' ) ) {
			return;
		}

		$this->localize();

		foreach ( array( 'post-new.php', 'post.php' ) as $page ) {
			add_action( 'admin_footer-' . $page, array( $this, 'inject_js' ), 11 );
		}
	}

	
	private static function validate_post_type() {

		// Filter published posts.
		if ( get_post()->post_status === 'publish' ) {
			return;
		}

		// Optionally include/exclude post types.
		$current_pt = get_post()->post_type;

		// Filter post types.
		$include_pts = apply_filters(
			'publish_confirm_post_types',
			get_post_types()
		);

		// Bail if current PT is not in PT stack.
		if ( ! in_array( $current_pt, (array) $include_pts, true ) ) {
			return;
		}
	}

	public function localize() {

		load_plugin_textdomain( 'publish-confirm' );
	}

	private static function get_message() {

		// Custom message.
		return apply_filters(
			'publish_confirm_message',
			esc_attr__( 'Are you sure you want to publish this now?', 'publish-confirm' )
		);
	}

	public static function inject_js() {

		self::validate_post_type();

		// Is jQuery loaded.
		if ( ! wp_script_is( 'jquery', 'done' ) ) {
			return;
		}

		// Print javascript.
		self::_print_js( self::get_message() );
	}

	private static function _print_js( $msg ) {

		?>
		<script type="text/javascript">
			jQuery( document ).ready(
				function( $ ) {
					var scheduleLabel = postL10n.schedule; // if the language is English, this is "Schedule"
					$( '#publish' ).on(
						'click',
						function( event ) {
							if ( $( this ).attr( 'name' ) !== 'publish' || $( this ).attr( 'value' ) === scheduleLabel ) {
								return;
							}
							if ( ! confirm( <?php echo wp_json_encode( $msg ) ?> ) ) {
								event.preventDefault();
							}
						}
					);
				}
			);
		</script>
	<?php }
}
