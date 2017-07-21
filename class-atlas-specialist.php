<?php

/*
Plugin Name: Atlas Specialist
Description: This plugin gives you a set of tools to integrate your Atlas Specialist profile in your Wordpress site
Version:     1.1.5
Author:      Atlas
Author URI:  https://atlashelp.ro
Text Domain: atlas-specialist
Domain Path: /languages
License:     GPL2

Atlas Specialist is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Atlas Specialist is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Atlas Specialist. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/

if ( ! class_exists( 'Atlas_Specialist' ) ) {
	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 */
	class Atlas_Specialist {

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

	   	/**
		 * Plugin init function
		 *
		 * @since    1.0.0
		 */
		public function init() {
			$this->version = '1.1.5';

			// init admin page if the user is logged in and has rights to see it!
			$current_user = wp_get_current_user();
			if ( ! empty( $current_user->ID ) ) {
				// check if can edit settings!
				if ( current_user_can( 'manage_options' ) ) {
					require_once plugin_dir_path( __FILE__ ) . 'admin/class-atlas-specialist-admin.php';
					$admin_menu = new Atlas_Specialist_Admin;
					$admin_menu->init();
				}
			}

			if ( ! is_admin() ) {
				$options = get_option( 'atlas_specialist_options' );
				$enable_chat = isset( $options['specialist_chat_enable'] ) ? wp_strip_all_tags( $options['specialist_chat_enable'] ) : '0';
				$chat_title = isset( $options['specialist_chat_title'] ) ? wp_strip_all_tags( $options['specialist_chat_title'] ) : 'Live Chat';

				$options_array = array(
					'url' => wp_strip_all_tags( $options['specialist_url'] ),
					'chatEnable' => $enable_chat,
					'chatTitle' => $chat_title,
					'locale' => get_locale()
				);

				if ( $options ) {
					wp_enqueue_script( 'zabuto_calendar', plugin_dir_url( __FILE__ ) . 'public/libs/zabuto_calendar.min.js', array( 'jquery' ), '1.2.1', true );
					wp_enqueue_style( 'zabuto_calendar_style', plugin_dir_url( __FILE__ ) . 'public/libs/zabuto_calendar.min.css', false, '1.2.1' );

					wp_enqueue_style( 'atlas_specialist_style', plugin_dir_url( __FILE__ ) . 'public/css/atlas-specialist.min.css', false, $this->version );
					wp_enqueue_script( 'atlas_specialist_js', plugin_dir_url( __FILE__ ) . 'public/js/atlas-specialist.min.js', array( 'jquery' ), $this->version, true );

					wp_localize_script( 'atlas_specialist_js', 'atlasSpecialistOptions', $options_array );
				}
			}

			// add specialist profile shortcode.
			add_shortcode( 'atlas-specialist-profile', array( $this, 'embed_profile_shortcode' ) );

			// add specialist calendar
			add_shortcode( 'atlas-specialist-calendar', array( $this, 'appointments_calendar_shortcode' ) );
		}

	    /**
		 * Profile embed shortcode function
		 *
		 * @param array $atts - array of shortcode attributes.
		 * @since    1.0.0
		 */
		public function embed_profile_shortcode( $atts ) {
			$opts = shortcode_atts( array(
				'width' => '100%',
				'height' => '500',
			), $atts );

			$options = get_option( 'atlas_specialist_options' );
			$profile = isset( $options['specialist_url'] ) ? esc_js( $options['specialist_url'] ) : 'atlas';

			return '<iframe style="border:none;padding:0;margin:0" src="https://app.atlashelp.ro/' . $profile . '?standalone" width="' . esc_attr( $opts['width'] ) . '" height="' . esc_attr( $opts['height'] ) . '></iframe>';
		}

	    /**
		 * Specialist calendar shortcode function
		 *
		 * @param array $atts - array of shortcode attributes.
		 * @since    1.1.0
		 */
		public function appointments_calendar_shortcode( $atts ) {
			$opts = shortcode_atts( array(
				'width' => '200',
				'height' => '200',
			), $atts );

			$options = get_option( 'atlas_specialist_options' );
			$profile = isset( $options['specialist_url'] ) ? esc_js( $options['specialist_url'] ) : 'atlas';

			return '<div class="atlas-app-calendar"></div>';
		}

	}

	add_action( 'init','atlas_specialist_init' );

	/**
	 * Function to be runned on init event
	 *
	 * @since    1.0.0
	 */
	function atlas_specialist_init() {
		$atlas_specialist = new Atlas_Specialist;
		$atlas_specialist->init();
	}

	register_activation_hook( __FILE__, 'atlas_specialist_activate' );

	/**
	 * Function to be runned on plugin installation
	 *
	 * @since    1.0.0
	 */
	function atlas_specialist_activate() {
		add_option( 'atlas_specialist_options', array(
			'specialist_chat_enable' => 0,
			'specialist_url' => 'atlas',
			'specialist_chat_title' => 'Live Chat',
			)
		);
	}

	// add calendar widget
	require_once plugin_dir_path( __FILE__ ) . 'class-calendar-widget.php';
	add_action( 'widgets_init', function(){
		register_widget( 'Atlas_Specialist_Calendar_Widget' );
	});

	// load text domain.
	add_action( 'plugins_loaded', 'load_text_domain' );

	/**
	 * Add plugin text domain
	 *
	 * @since    1.0.0
	 */
	function load_text_domain() {
		load_plugin_textdomain( 'atlas-specialist', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
} // End if().
