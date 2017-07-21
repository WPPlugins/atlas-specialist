<?php
if ( ! class_exists( 'Atlas_Specialist_Admin' ) ) {
	/**
	 * Main admin section plugin class.
	 *
	 * This is used to define the admin section
	 *
	 * @since      1.0.0
	 */
	class Atlas_Specialist_Admin {

	   	/**
		 * Admin init function
		 *
		 * @since    1.0.0
		 */
		public function init() {
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		}

	   	/**
		 * Function for adding the top menu settings page
		 *
		 * @since    1.0.0
		 */
		public function add_options_page() {
			add_menu_page(
				'Atlas',
				'Atlas',
				'manage_options',
				plugin_dir_path( __FILE__ ) . 'view.php',
				null,
				plugin_dir_url( __FILE__ ) . '../public/images/atlas-24x24.png',
				20
			);

			add_action( 'admin_init', array( $this, 'settings_init' ) );
		}

	   	/**
		 * Function for settings section init
		 *
		 * @since    1.0.0
		 */
		public function settings_init() {
			// register a new setting.
			register_setting( 'atlas_specialist', 'atlas_specialist_options', array( $this, 'validate_input' ) );

			/**
			 * Add specialist profile field
			 *
			 * @param array $args array.
			 * @since    1.0.0
			 */
			function atlas_specialist_profile( $args ) {
				// get the value of the setting we've registered with register_setting().
				$options = get_option( 'atlas_specialist_options' );
				$value = isset( $options[ $args['specialist_url'] ] ) ? $options[ $args['specialist_url'] ] : '';
				?>

				<input class="regular-text" value="<?php echo esc_attr( $value ) ?>" id="<?php echo esc_attr( $args['specialist_url'] ); ?>" 
				name="atlas_specialist_options[<?php echo esc_attr( $args['specialist_url'] ); ?>]">

				<p class="description">
					<?php echo esc_html( __( 'You can find your profile URL in', 'atlas-specialist' ) ); ?>
					 <a target="_blank" href="https://app.atlashelp.ro/pages/provider/specialistProfile.jsp"><?php echo esc_html( __( 'My profile section', 'atlas-specialist' ) ); ?></a>
				</p>
				<?php
			}

			/**
			 * Enable/disable specialist chat
			 *
			 * @param array $args array.
			 * @since    1.0.0
			 */
			function atlas_specialist_enable_chat( $args ) {
				// get the value of the setting we've registered with register_setting().
				$options = get_option( 'atlas_specialist_options' );
				$chat_enabled = isset( $options[ $args['specialist_chat_enable'] ] ) ? $options[ $args['specialist_chat_enable'] ] : 0;
				?>

				<input type="checkbox" value="1" <?php checked( 1, $chat_enabled, true ); ?> id="<?php echo esc_attr( $args['specialist_chat_enable'] ); ?>"
				name="atlas_specialist_options[<?php echo esc_attr( $args['specialist_chat_enable'] ); ?>]">

				<p class="description">
					<?php echo esc_html( __( 'Display Atlas chat on your website?', 'atlas-specialist' ) ); ?>
				</p>

				<?php
			}

			/**
			 * Set title of Chat Widget
			 *
			 * @param array $args array.
			 * @since    1.1.0
			 */
			function atlas_specialist_chat_title( $args ) {
				// get the value of the setting we've registered with register_setting().
				$options = get_option( 'atlas_specialist_options' );
				$chat_title = isset( $options[ $args['specialist_chat_title'] ] ) ? $options[ $args['specialist_chat_title'] ] : 'Live Chat';
				?>

				<input class="regular-text" value="<?php echo esc_attr( $chat_title ) ?>" id="<?php echo esc_attr( $args['specialist_chat_title'] ); ?>" 
				name="atlas_specialist_options[<?php echo esc_attr( $args['specialist_chat_title'] ); ?>]">

				<?php
			}

			/**
			 * Shortcodes info
			 *
			 * @param array $args array.
			 * @since    1.1.0
			 */
			function atlas_specialist_shortcodes( $args ) {
				?>

				<p class="description">
					<?php echo esc_html( __( 'Copy the bellow code to any page to display your profile', 'atlas-specialist' ) ); ?>
				</p>
				<p>[atlas-specialist-profile width="100%" height="500"]</p>
				<br />

				<p class="description">
					<?php echo esc_html( __( 'Copy the bellow code to any page to display your calendar', 'atlas-specialist' ) ); ?>
				</p>
				<p>[atlas-specialist-calendar width="200 height="300"]</p>
				<p class="description">
					<?php echo __( 'You can also add it to a sidebar from the <a href="widgets.php">Widgets page</a>', 'atlas-specialist' ); ?>
				</p>

				<?php
			}

			// register a new settings section.
			add_settings_section(
				'atlas_specialist_section',
				__( 'Plugin settings.', 'atlas-specialist' ),
				null,
				'atlas_specialist'
			);

			// register fields.
			add_settings_field(
				'atlas_specialist_url',
				__( 'Your specialist URL', 'atlas-specialist' ),
				'atlas_specialist_profile',
				'atlas_specialist',
				'atlas_specialist_section',
				[
					'specialist_url' => 'specialist_url',
					'class' => 'atlas_specialist_row',
				],
				'specialist_url_save'
			);
			add_settings_field(
				'atlas_specialist_chat',
				__( 'Display chat', 'atlas-specialist' ),
				'atlas_specialist_enable_chat',
				'atlas_specialist',
				'atlas_specialist_section',
				[
					'specialist_chat_enable' => 'specialist_chat_enable',
					'class' => 'atlas_specialist_row',
				]
			);
			add_settings_field(
				'atlas_specialist_chat_title',
				__( 'Chat widget title', 'atlas-specialist' ),
				'atlas_specialist_chat_title',
				'atlas_specialist',
				'atlas_specialist_section',
				[
					'specialist_chat_title' => 'specialist_chat_title',
					'class' => 'atlas_specialist_row',
				]
			);

			add_settings_field(
				'atlas_specialist_shortcodes',
				__( 'Shortcodes', 'atlas-specialist' ),
				'atlas_specialist_shortcodes',
				'atlas_specialist',
				'atlas_specialist_section',
				[
					'atlas_specialist_shortcodes' => 'atlas_specialist_shortcodes',
					'class' => 'atlas_specialist_row',
				]
			);
		}

		/**
		 * Validate the settings that were saved
		 *
		 * @param array $input array of input values.
		 * @since    1.0.0
		 */
		public function validate_input( $input ) {
			if ( $input['specialist_url'] === '' ) {
				$input['specialist_url'] = 'atlas';
			}

			// if full profile url provided, keep just what is needed.
			if ( strpos( $input['specialist_url'], '.atlashelp.ro' ) !== false ) {
				$pieces = explode( '/', $input['specialist_url'] );
				// remove any slashes found.
				$input['specialist_url'] = str_replace( '/', '', end( $pieces ) );
			}
			// remove any slashes found.
			$input['specialist_url'] = str_replace( '/', '', $input['specialist_url'] );

			return $input;
		}
	}
} // End if().
?>
