<?php
/**
 * The Frontend class to manage all output and enqueue Scripts and styles files of the plugin.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free.
 * @subpackage Testimonial_free/Frontend.
 */

namespace ShapedPlugin\TestimonialFree\Frontend;

use ShapedPlugin\TestimonialFree\Frontend\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }  // if direct access

/**
 * Frontend class
 */
class Frontend {

	/**
	 * Single instance of the class.
	 *
	 * @var null
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Frontend Instance.
	 *
	 * @return Frontend
	 * @since 1.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'register_all_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_front_scripts' ) );
		add_shortcode( 'sp_testimonial', array( $this, 'shortcode_render' ) );
		add_action( 'save_post', array( $this, 'delete_page_testimonial_option_on_save' ) );

	}

	/**
	 * Shortcode render.
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function shortcode_render( $attributes ) {

		shortcode_atts(
			array(
				'id' => '',
			),
			$attributes,
			'sp_testimonial'
		);

		$post_id            = $attributes['id'];
		$setting_options    = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data     = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		$main_section_title = get_the_title( $post_id );
		$dequeue_swiper_css = isset( $setting_options['tf_dequeue_slick_css'] ) ? $setting_options['tf_dequeue_slick_css'] : true;
		$dequeue_fa_css     = isset( $setting_options['tf_dequeue_fa_css'] ) ? $setting_options['tf_dequeue_fa_css'] : true;
		$custom_css         = isset( $setting_options['custom_css'] ) ? $setting_options['custom_css'] : '';
		ob_start();
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp-testimonial_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );

		if ( is_multisite() ) {
			$option_key         = 'sp-testimonial_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
			if ( $dequeue_swiper_css ) {
				wp_enqueue_style( 'sp-testimonial-swiper' );
			}
			if ( $dequeue_fa_css ) {
				wp_enqueue_style( 'tfree-font-awesome' );
			}
			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );
			$outline = '';
			include SP_TFREE_PATH . 'Frontend/Views/partials/dynamic-style.php';
			if ( ! empty( $custom_css ) ) {
				$outline .= $custom_css;
			}
			$css = Helper::minify_output( $outline );
			echo '<style id="sp_testimonial_dynamic_css' . $post_id . '">' . $css . '</style>';
		}
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) { // If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else { // If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}

		Helper::sp_testimonial_html_show( $post_id, $setting_options, $shortcode_data, $main_section_title );
		return Helper::minify_output( ob_get_clean() );
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function front_scripts() {
		$setting_options    = get_option( 'sp_testimonial_pro_options' );
		$dequeue_swiper_css = isset( $setting_options['tf_dequeue_slick_css'] ) ? $setting_options['tf_dequeue_slick_css'] : true;
		$dequeue_fa_css     = isset( $setting_options['tf_dequeue_fa_css'] ) ? $setting_options['tf_dequeue_fa_css'] : true;
		$custom_css         = isset( $setting_options['custom_css'] ) ? $setting_options['custom_css'] : '';

		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp-testimonial_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp-testimonial_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		// CSS Files.
		if ( $found_generator_id ) {

			if ( $dequeue_swiper_css ) {
				wp_enqueue_style( 'sp-testimonial-swiper' );
			}
			if ( $dequeue_fa_css ) {
				wp_enqueue_style( 'tfree-font-awesome' );
			}
			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );

			$outline = '';
			foreach ( $found_generator_id as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$setting_options = get_option( 'sp_testimonial_pro_options' );
					$shortcode_data  = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
					include SP_TFREE_PATH . 'Frontend/Views/partials/dynamic-style.php';
				}
			}
			if ( ! empty( $custom_css ) ) {
				$outline .= $custom_css;
			}
			$css = Helper::minify_output( $outline );
			wp_add_inline_style( 'tfree-style', $css );
		}

	}
	/**
	 * Plugin Scripts and Styles
	 */
	public function admin_front_scripts() {
		$wpscreen = get_current_screen();
		if ( 'spt_shortcodes' === $wpscreen->post_type ) {
			$setting_options    = get_option( 'sp_testimonial_pro_options' );
			$dequeue_swiper_css = isset( $setting_options['tf_dequeue_slick_css'] ) ? $setting_options['tf_dequeue_slick_css'] : true;
			$dequeue_fa_css     = isset( $setting_options['tf_dequeue_fa_css'] ) ? $setting_options['tf_dequeue_fa_css'] : true;
			// CSS Files.
			if ( $dequeue_swiper_css ) {
				wp_enqueue_style( 'sp-testimonial-swiper' );
			}
			if ( $dequeue_fa_css ) {
				wp_enqueue_style( 'tfree-font-awesome' );
			}

			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );
			wp_enqueue_script( 'sp-testimonial-swiper-js' );
		}

	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		/**
		 *  Register the All style for the public-facing side of the site.
		 */
		wp_register_style( 'sp-testimonial-swiper', SP_TFREE_URL . 'Frontend/assets/css/swiper.min.css', array(), SP_TFREE_VERSION );
		wp_register_style( 'tfree-font-awesome', SP_TFREE_URL . 'Frontend/assets/css/font-awesome.min.css', array(), SP_TFREE_VERSION );
		wp_register_style( 'tfree-deprecated-style', SP_TFREE_URL . 'Frontend/assets/css/deprecated-style.min.css', array(), SP_TFREE_VERSION );
		wp_register_style( 'tfree-style', SP_TFREE_URL . 'Frontend/assets/css/style.min.css', array(), SP_TFREE_VERSION );

		/**
		 *  Register the All scripts for the public-facing side of the site.
		 */
		wp_register_script( 'sp-testimonial-swiper-js', SP_TFREE_URL . 'Frontend/assets/js/swiper.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );
		wp_register_script( 'sp-testimonial-scripts', SP_TFREE_URL . 'Frontend/assets/js/sp-scripts.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );

	}
	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_testimonial_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp-testimonial_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} else {
			if ( delete_option( 'sp-testimonial_page_id' . $post_ID ) ) {
				delete_option( 'sp-testimonial_page_id' . $post_ID );
			}
		}

	}


}
