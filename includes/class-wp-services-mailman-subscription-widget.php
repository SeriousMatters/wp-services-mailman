<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/SeriousMatters
 * @since      1.0.0
 *
 * @package    Wp_Services_Mailman
 */
class Wp_Services_Mailman_Subscription_Widget extends WP_Widget {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Constructor for the widget
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'wp-services-mailman';

		parent::__construct(
			$this->plugin_name . '-subscription-widget', // Base ID
			__( 'Mailman Subscription' ) // Name
			// array() // Args
		);
	}

	/**
	 * Widget options form in admin area
	 *
	 * @since    1.0.0
	 */
	public function form( $instance ) {
		$options = get_option( $this->plugin_name . '-options' );
		if ( empty( $instance['success_text'] ) ) {
			$instance['success_text'] = '<p>Thank you for joining our mailing list. You will receive a confirmation soon</p>';
		}
		if ( empty( $instance['fail_text'] ) ) {
			$instance['fail_text'] = '<p>Something went wrong. If the problem persist please contact administrator.</p>';
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?=__( 'Title' )?>:</label>
			<input type="text" class="widefat" name="<?=$this->get_field_name( 'title' );?>" id="<?=$this->get_field_id( 'title' );?>" value="<?=$instance['title'];?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?=__( 'Description' )?>:</label>
			<textarea class="widefat" name="<?=$this->get_field_name( 'description' );?>" id="<?=$this->get_field_id( 'description' );?>"><?=$instance['description'];?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'success_text' ); ?>"><?=__( 'Success Message' )?>:</label>
			<textarea class="widefat" name="<?=$this->get_field_name( 'success_text' );?>" id="<?=$this->get_field_id( 'success_text' );?>"><?=$instance['success_text'];?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fail_text' ); ?>"><?=__( 'Failure Message' )?>:</label>
			<textarea class="widefat" name="<?=$this->get_field_name( 'fail_text' );?>" id="<?=$this->get_field_id( 'fail_text' );?>"><?=$instance['fail_text'];?></textarea>
		</p>
		
		<?php
	}

	/**
	 * Process widget options on save
	 *
	 * @since    1.0.0
	 */
	public function update( $new_instance, $old_instance ) {

		return $new_instance;
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @since    1.0.0
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		if ( ! empty( $args['title'] ) ) { echo $args['before_title'] . $args['title'] . $args['after_title']; }
		$options = get_option( $this->plugin_name . '-options' );
		if ( isset( $_POST['subscribe-mailinglist'] ) ) {
			// Handle submission
			$result = $this->mailman_subscribe( $_POST['email'], $options['adminUrl'], $options['listId'], $options['listPw'] );
			if ( true == $result ) {
				echo $instance['success_text'];
			} else {
				echo $instance['fail_text'];
				$this->display_subscription_form( $_POST['email'] );
			}
			
		} else {
			$this->display_subscription_form();
		}
		echo $args['after_widget'];
	}

	/**
	 * Dsiplay subscription form
	 *
	 * @since    1.0.0
	 * @param    string    $email    
	 */
	protected function display_subscription_form( $email = '' ) {
		?>
		<form method="POST" action="">
			<p>
				<label for="subscriber-email">Email address:</label>
				<input type="email" name="email" id="subscriber-email" value="<?=$email?>" />
			</p>
			<p><input type="submit" name="subscribe-mailinglist" value="Subscribe" /></p>
		</form>
		<?php
	}

	/**
	 * Subscribe visitor to mailman list
	 *
	 * @since    1.0.0
	 * @param    string    $email     subscriber email address
	 * @param    string    $url       mailman admin url
	 * @param    string    $list      mailman list
	 * @param    string    $pw        mailman list password
	 * @param    boolean   $invite    invite or subscribe
	 * @return   boolean              true when subscribe/invite successful
	 */
	protected function mailman_subscribe( $email, $url, $list, $pw, $invite = true ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Services/Mailman.php';
		try {
			$mm = new Services_Mailman( $url, $list, $pw );
			// Invite to subscribe (require email confirmation)
			$result = $mm->subscribe( $email, $invite );
			return true;
		} catch( Services_Mailman_Exception $e ) {
			error_log( $e->getMessage() );
			return false;
		}
	}
}

?>