<?php
/*
Plugin Name: Keyboard Save
Plugin URI: 
Description: A simple WordPress plugin to extend save functionality to your keyboard outside of the general editor.
Version: 1.1
Author: Timothy Wood @codearachnid
Author URI: http://www.codearachnid.com
Text Domain: wpkeysave
Domain Path: /lang/
License: GPL v3

WordPress Keyboard Save
Copyright (C) 2014, Timothy Wood - tim@imaginesimplicity.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Don't load directly
if ( !defined( 'ABSPATH' ) )
	die( '-1' );


if( !function_exists('wpkeysave_init') ) {

	function wpkeysave_init(){
		add_action( 'admin_init', 'wpkeysave_admin_init' );
		add_action( 'admin_enqueue_scripts', 'wpkeysave_scripts' );
		add_action( 'admin_print_scripts', 'wpkeysave_print_scripts' ); 
	}

	function wpkeysave_admin_init(){
		register_setting(
			'writing',
			'wpkeysave_options',
			'wpkeysave_validate_options'
		);
		add_settings_field(
			'wpkeysave_save_trigger',
			'CTRL+S Action',
			'wpkeysave_setting_input',
			'writing',
			'default'
		);
	}

	function wpkeysave_get_options(){
		$options = array_merge( array(
			'save_trigger' => 'save-post'
			), (array) get_option( 'wpkeysave_options' ) );
		return $options;
	}

	function wpkeysave_setting_input() {
		$options = wpkeysave_get_options();
		$save_trigger = esc_attr( $options['save_trigger'] );
		
		?><select name='wpkeysave_options[save_trigger]'>
		<option value="">Select the action to trigger</option>
		<option value="save-post" <?php selected( $save_trigger, 'save-post'); ?>>Save</option>
		<option value="publish" <?php selected( $save_trigger, 'publish'); ?>>Publish/Update</option>
		</select><?php
	}

	function wpkeysave_validate_options( $input ) {
		$valid = array();
		$valid['save_trigger'] = in_array( $input['save_trigger'], array('save-post','publish')) ? $input['save_trigger'] : null;
		
		// Something dirty entered? Warn user.
		if( $valid['save_trigger'] != $input['save_trigger'] ) {
			add_settings_error(
				'wpkeysave_validate_error',
				'wpkeysave_texterror',
				'Invalid option for save trigger',
				'error'
			);		
		}
		
		return $valid;
	}

	function wpkeysave_print_scripts(){
		$options = wpkeysave_get_options();
		?><script type='text/javascript'>
		var wpkeysave_save_trigger = "<?php echo $options['save_trigger']; ?>";
		</script><?php
	}

	function wpkeysave_scripts(){
		wp_enqueue_script( 'wpkeysave_scripts', plugin_dir_url( __FILE__ ) . 'keyboard-save.js', array('jquery'), '1.0', true );
	}

	add_action( 'plugins_loaded', 'wpkeysave_init' );
}