<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/SeriousMatters
 * @since      1.0.0
 *
 * @package    Wp_Services_Mailman
 * @subpackage Wp_Services_Mailman/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Services_Mailman
 * @subpackage Wp_Services_Mailman/admin
 * @author     Howard Li <SeriousMatters@users.noreply.github.com>
 */
class Wp_Services_Mailman_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Adds a settings page and link in menu
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_admin_menu() {
		// add_options_page($page_title, $menu_title, $capability, $menu_slug, $function)
		add_options_page(
			__('Mailman Settings', $this->plugin_name),
			__('Mailman', $this->plugin_name),
			'manage_options',
			$this->plugin_name,
			array($this, 'display_admin_page')
		);
	}

	/**
	 * Add plugin settings link on plugins page
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   mixed    The settings field
	 */
	public function add_settings_link( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">Settings</a>',
		);
		return array_merge( $links, $mylinks );
	}

	/**
	 * Registers plugin settings, sections, and fields
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function settings_api_init() {
		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

		// add_settings_section( $id, $title, $callback, $page );
		add_settings_section(
			$this->plugin_name . '-options-section',
			'',
			array( $this,'display_options_section' ),
			$this->plugin_name
		);

		// add_settings_field( $id, $title, $callback, $page, $section, $args );
		add_settings_field(
			'mailinglist-label-field',
			__( 'List Label' ),
			array( $this, 'display_label_field' ),
			$this->plugin_name,
			$this->plugin_name . '-options-section'
		);
		add_settings_field(
			'mailinglist-url-field',
			__( 'Mailman admin URL' ),
			array($this, 'display_url_field' ),
			$this->plugin_name,
			$this->plugin_name . '-options-section'
		);
		add_settings_field(
			'mailinglist-list-field',
			__( 'List ID' ),
			array( $this, 'display_list_field' ),
			$this->plugin_name,
			$this->plugin_name . '-options-section'
		);
		add_settings_field(
			'mailinglist-pw-field',
			__( 'List Password' ),
			array( $this, 'display_pw_field' ),
			$this->plugin_name,
			$this->plugin_name . '-options-section'
		);

	}

	/**
	 * Outputs the options page
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_admin_page() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-services-mailman-admin-display.php';
	}

	/**
	 * Display settings section
	 * 
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_options_section() {
		// $options = get_option( $this->plugin_name . '-options' );

	}

	/**
	 * Display settings fields
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_label_field() {
		$options = get_option( $this->plugin_name . '-options' );
		$listLabel = '';

		if ( !empty( $options['listLabel'] ) ) {
			$listLabel = $options['listLabel'];
		}

		?>
		<input type="text" id="<?=$this->plugin_name;?>-options[listLabel]" name="<?=$this->plugin_name;?>-options[listLabel]" value="<?=$listLabel;?>" />
		<p class="description">Descriptive name for front end</p>
		<?php
	}

	public function display_url_field() {
		$options = get_option( $this->plugin_name . '-options' );
		$adminUrl = '';

		if ( !empty( $options['adminUrl'] ) ) {
			$adminUrl = $options['adminUrl'];
		}

		?>
		<input type="text" id="<?=$this->plugin_name;?>-options[adminUrl]" name="<?=$this->plugin_name;?>-options[adminUrl]" value="<?=$adminUrl;?>" />
		<p class="description">eg. <?=$_SERVER['HTTP_HOST'].'/mailman/admin'?></p>
		<?php
	}

	public function display_list_field() {
		$options = get_option( $this->plugin_name . '-options' );
		$listId = '';

		if ( !empty( $options['listId'] ) ) {
			$listId = $options['listId'];
		}

		?>
		<input type="text" id="<?=$this->plugin_name;?>-options[listId]" name="<?=$this->plugin_name;?>-options[listId]" value="<?=$listId;?>" />
		<p class="description"></p>
		<?php
	}

	public function display_pw_field() {
		$options = get_option( $this->plugin_name . '-options' );
		$description = '';

		if ( !empty( $options['listPw'] ) ) {
			$description = 'Leave blank to keep unchanged';
		}

		?>
		<input type="password" id="<?=$this->plugin_name;?>-options[listPw]" name="<?=$this->plugin_name;?>-options[listPw]" />
		<p class="description"><?=$description?></p>
		<?php
	}

	/**
	 * Validate and sanitize mailinglists form submission
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   
	 */
	public function validate_options( $input ) {
		$options = get_option( $this->plugin_name . '-options' );
		$sanitized = Array();
		$errors = 0;

		if(isset($input)) {
			// List Label must not be empty
			$sanitized['listLabel'] = sanitize_text_field( $input['listLabel'] );
			if ( empty( $sanitized['listLabel'] ) ) {
				$errors++;
				add_settings_error( '', 'invalid-listLabel', 'List label cannot be empty.' );
			} else {
				$options['listLabel'] = $sanitized['listLabel'];
			}
			
			// List Url must be a valid URL
			$sanitized['adminUrl'] = sanitize_text_field( $input['adminUrl'] );
			if ( empty( $sanitized['adminUrl'] ) ) {
				$errors++;
				add_settings_error( '', 'invalid-adminUrl', 'Please enter a valid list admin URL' );
			} else {
				$options['adminUrl'] = $sanitized['adminUrl'];
			}

			// List ID must not be empty
			$sanitized['listId'] = sanitize_text_field( $input['listId'] );
			if ( empty( $sanitized['listId'] ) ) {
				$errors++;
				add_settings_error( '', 'invalid-listId', 'List ID cannot be empty.' );
			} else {
				$options['listId'] = $sanitized['listId'];
			}

			// List password must not be empty
			$sanitized['listPw'] = sanitize_text_field( $input['listPw'] );
			if ( empty( $sanitized['listPw'] ) AND empty( $options['listPw'] ) ) {
				$errors++;
				add_settings_error( '', 'invalid-listPw', 'List admin password required.' );
			} else if ( !empty( $sanitized['listPw'] ) ) {
				$options['listPw'] = $sanitized['listPw'];
			}

			// Validates Mailman account credentials
			if ($errors == 0) {
				$mm_error = $this->testMailmanConnection( $sanitized['adminUrl'], $sanitized['listId'], $sanitized['listPw'] );
				if ( !empty($mm_error) ) {
					add_settings_error( '', 'mailman-connection-failed', 'Mailman: ' . $mm_error );
				} else {
					add_settings_error( '', 'mailman-connection-success', 'Mailman connection test successful', 'updated' );

				}
			}

			return $options;

		}
	}

	/**
	 * Test Services Mailman Connection
	 * 
	 */
	private function testMailmanConnection($url, $list, $pw) {
		require_once plugin_dir_path( dirname( __FILE__ ) ). 'includes/Services/Mailman.php';
		try {
			$mm = new Services_Mailman($url, $list, $pw);
			$mm->members();
			return '';
		} catch(Services_Mailman_Exception $e) {
			return $e->getMessage();
		}
	}
}
