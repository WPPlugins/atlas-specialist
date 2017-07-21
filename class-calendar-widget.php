<?php

/**
 * Adds Atlas_Specialist_Calendar_Widget widget.
 * @since    1.1.0
 */
class Atlas_Specialist_Calendar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
     * @since    1.1.0
	 */
	function __construct() {
		parent::__construct(
			'atlas_specialist_calendar_widget', // Base ID
			esc_html__( 'Atlas Specialist Calendar', 'atlas-specialist' ), // Name
			array( 'description' => esc_html__( 'Add your specialist calendar', 'atlas-specialist' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
     * @since    1.1.0
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo '<div class="atlas-app-calendar"></div>';
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
     * @since    1.1.0
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'My Atlas Calendar', 'atlas-specialist' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'atlas-specialist' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
     * @since    1.1.0
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Atlas_Specialist_Calendar_Widget