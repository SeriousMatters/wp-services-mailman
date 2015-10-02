<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/SeriousMatters
 * @since      1.0.0
 *
 * @package    Wp_Services_Mailman
 * @subpackage Wp_Services_Mailman/admin/partials
 */
?>

<div class="wrap">
	<h2><?=esc_html( get_admin_page_title() );?></h2>
	<div class="postbox-container">
		<div class="postbox">
			<div class ="inside">
				<p>After adding and enabling some mailing lists, they will be available in the mailman widget.</p>
			</div>
		</div>
		<div class="postbox">
			<div class ="inside">
				<form method="post" action="options.php">
				<?php
					settings_fields($this->plugin_name . '-options');
					do_settings_sections( $this->plugin_name );
					submit_button( 'Save' );
				?>
				</form>
			</div>
		</div>
	</div>
</div>