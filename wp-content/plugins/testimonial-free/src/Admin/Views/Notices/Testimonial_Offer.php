<?php
/**
 * The admin offer notice.
 *
 * @since        2.5.10
 * @version      2.5.10
 *
 * @package    Testimonial
 * @subpackage Testimonial/admin/views/notices
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\Views\Notices;

/**
 * Admin offer notice class.
 */
class Testimonial_Offer {

	/**
	 * Constructor
	 *
	 * @since 2.5.10
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
		add_action( 'wp_ajax_sp-tfree-never-show-offer-notice', array( $this, 'dismiss_offer_notice' ) );
	}

	/**
	 * Display admin notice.
	 *
	 * @return void
	 */
	public function display_admin_notice() {

		// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Variable default value.
		$offer = get_option( 'sp_testimonial_offer_notice_dismiss' );
		$time   = time();
		$load   = false;

		if ( ! $offer ) {
			$offer = array(
				'time'      => $time,
				'dismissed' => false,
			);
			add_option( 'sp_testimonial_offer_notice_dismiss', $offer );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $offer['dismissed'] ) && ! $offer['dismissed'] ) && ( isset( $offer['time'] ) ) ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}
		?>
		<div id="sp-testimonial-offer-notice" class="sp-testimonial-offer-notice" style="margin: 20px 20px 0 0;display: inline-block;position:relative;">
			<a href="#" class="offer-notice-dismissed" style="text-decoration: none;position: absolute;right: 0;top: 0;padding: 5px;color: #fff;"><span class="dashicons dashicons-no-alt"></span></a>
			<a href="https://shapedplugin.com/real-testimonials/pricing/?utm_source=real_testimonials_free&utm_medium=get_special_offer_banner&utm_campaign=special_offer" target="_blank"><img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/assets/images/special-offer.jpg' ); ?>" alt="Special Offer" style="width:100%;display: block;"></a>
		</div>

		<script type='text/javascript'>

			jQuery(document).ready( function($) {
				$(document).on('click', '#sp-testimonial-offer-notice.sp-testimonial-offer-notice .offer-notice-dismissed', function( event ) {
                    var notice_dismissed_value = "3";
                    event.preventDefault();

					$.post( ajaxurl, {
						action: 'sp-tfree-never-show-offer-notice',
						notice_dismissed_data : notice_dismissed_value,
						nonce: '<?php echo esc_attr( wp_create_nonce( 'sp_tfree_offer_notice' ) ); ?>'
					});

					$('#sp-testimonial-offer-notice.sp-testimonial-offer-notice').hide();
				});
			});

		</script>
		<?php
	}

	/**
	 * Dismiss offer notice
	 *
	 * @since  2.5.10
	 *
	 * @return void
	 **/
	public function dismiss_offer_notice() {
		$post_data = wp_unslash( $_POST );

		if ( ! isset( $post_data['nonce'] ) || ! wp_verify_nonce( sanitize_key( $post_data['nonce'] ), 'sp_tfree_offer_notice' ) ) {
			return;
		}
		// Variable default value.
		$offer = get_option( 'sp_testimonial_offer_notice_dismiss' );
		if ( ! $offer ) {
			$offer = array();
		}
		switch ( isset( $post_data['notice_dismissed_data'] ) ? $post_data['notice_dismissed_data'] : '' ) {
			case '3':
				$offer['time']      = time();
				$offer['dismissed'] = true;
				break;
		}
		update_option( 'sp_testimonial_offer_notice_dismiss', $offer );
		die;
	}
}
