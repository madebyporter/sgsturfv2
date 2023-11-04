<?php
/**
 * Facebook Messenger Live Chat Module for Easy Social Share Buttons for WordPress
 *
 * @package EasySocialShareButtons
 * @author appscreo
 * @version 1.0
 * @since 5.3
 */

if (!function_exists('essb_register_messenger')) {
	function essb_register_messenger() {
		
		if (is_admin() || is_feed()) { return; }
		
		$is_deactivated = false;
		$exclude_from = essb_option_value('fbmessenger_exclude');
		if (! empty ( $exclude_from )) {
			$excule_from = explode ( ',', $exclude_from );
		
			$excule_from = array_map ( 'trim', $excule_from );
			if (in_array ( get_the_ID (), $excule_from, false )) {
				$is_deactivated = true;
			}
		}
		
		if (essb_option_bool_value('fbmessender_deactivate_homepage')) {
			if (is_home() || is_front_page()) {
				$is_deactivated = true;
			}
		}
		
		if (essb_option_value('fbmessenger_display_method') == 'shortcode') {
		    $is_deactivated = true;
		}
		
		/**
		 * Changing the check for post types as of options interface change (fbmessenger_posttypes is removed)
		 */
		$posttypes = essb_option_value('fbmessenger_posttypes');
		if (!is_array($posttypes)) {
			$posttypes = array();
		}
			
		$current_post_type = get_post_type();			
		if ($current_post_type && count($posttypes) > 0 && !in_array($current_post_type, $posttypes)) {
			$is_deactivated = true;
		}
		
		// deactivate display of the functions
		if ($is_deactivated) { return; }
		
		$fbmessenger_logged_greeting = stripslashes(essb_option_value('fbmessenger_logged_greeting'));
		$fbmessenger_loggedout_greeting = stripslashes(essb_option_value('fbmessenger_loggedout_greeting'));
		$fbmessenger_color = essb_option_value('fbmessenger_color');
		
		$fbmessenger_language = essb_sanitize_option_value('fbmessenger_language');
		if ($fbmessenger_language == '') {
			$fbmessenger_language = 'en_US';
		}
		
		$extra_options = '';
		
		if ($fbmessenger_color != '') {
			$extra_options .= ' theme_color="'.esc_attr($fbmessenger_color).'"';
		}
		if ($fbmessenger_logged_greeting != '') {
			$extra_options .= ' logged_in_greeting="'.esc_attr($fbmessenger_logged_greeting).'"';
		}
		if ($fbmessenger_loggedout_greeting != '') {
			$extra_options .= ' logged_out_greeting="'.esc_attr($fbmessenger_loggedout_greeting).'"';
		}
		
		/**
		 * @since 8.3
		 */
		$fbmessenger_greeting_dialog = essb_sanitize_option_value('fbmessenger_greeting_dialog');
		if (!empty($fbmessenger_greeting_dialog)) {
		    $extra_options .= ' greeting_dialog_display="'.$fbmessenger_greeting_dialog.'"';
		}
		
		$fbmessenger_greeting_dialog_delay = essb_sanitize_option_value('fbmessenger_greeting_dialog_delay');
		if (!empty($fbmessenger_greeting_dialog_delay)) {
		    $extra_options .= ' greeting_dialog_delay="'.$fbmessenger_greeting_dialog_delay.'"';
		}
		
		$minimized_state = essb_option_bool_value('fbmessenger_minimized');				
		echo '<div class="fb-customerchat" page_id="'.esc_attr(essb_option_value('fbmessenger_pageid')).'" '.($minimized_state ? 'minimized="true"' : '').$extra_options.'></div>';
		
		/**
		 * Loading Facebook API required to run the chat app
		 */
		echo '
		<script type="text/javascript">
		(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/'.esc_attr($fbmessenger_language).'/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>';
	}
	
	add_action('wp_enqueue_scripts', 'essb_register_messenger_styles', 1 );
	add_action('wp_footer', 'essb_register_messenger');
	
	add_shortcode('facebook-messenger-chat', 'essb_facebook_messenger_chat');
	
	function essb_facebook_messenger_chat($atts = array()) {
	    $fbmessenger_logged_greeting = stripslashes(essb_option_value('fbmessenger_logged_greeting'));
	    $fbmessenger_loggedout_greeting = stripslashes(essb_option_value('fbmessenger_loggedout_greeting'));
	    $fbmessenger_color = essb_option_value('fbmessenger_color');
	    
	    $fbmessenger_language = essb_sanitize_option_value('fbmessenger_language');
	    if ($fbmessenger_language == '') {
	        $fbmessenger_language = 'en_US';
	    }
	    
	    $extra_options = '';
	    
	    if ($fbmessenger_color != '') {
	        $extra_options .= ' theme_color="'.esc_attr($fbmessenger_color).'"';
	    }
	    if ($fbmessenger_logged_greeting != '') {
	        $extra_options .= ' logged_in_greeting="'.esc_attr($fbmessenger_logged_greeting).'"';
	    }
	    if ($fbmessenger_loggedout_greeting != '') {
	        $extra_options .= ' logged_out_greeting="'.esc_attr($fbmessenger_loggedout_greeting).'"';
	    }
	    
	    /**
	     * @since 8.3
	     */
	    $fbmessenger_greeting_dialog = essb_sanitize_option_value('fbmessenger_greeting_dialog');
	    if (!empty($fbmessenger_greeting_dialog)) {
	        $extra_options .= ' greeting_dialog_display="'.$fbmessenger_greeting_dialog.'"';
	    }
	    
	    $fbmessenger_greeting_dialog_delay = essb_sanitize_option_value('fbmessenger_greeting_dialog_delay');
	    if (!empty($fbmessenger_greeting_dialog_delay)) {
	        $extra_options .= ' greeting_dialog_delay="'.$fbmessenger_greeting_dialog_delay.'"';
	    }
	    
	    $minimized_state = essb_option_bool_value('fbmessenger_minimized');
	    $output = '';
	    $output .= '<div class="fb-customerchat" page_id="'.esc_attr(essb_option_value('fbmessenger_pageid')).'" '.($minimized_state ? 'minimized="true"' : '').$extra_options.'></div>';
	    
	    /**
	     * Loading Facebook API required to run the chat app
	     */
	    $output .= '
		<script type="text/javascript">
		(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/'.esc_attr($fbmessenger_language).'/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>';
	    
	    return $output;
	}
	
	function essb_register_messenger_styles() {
		if (essb_option_bool_value('fbmessenger_left')) {
			essb_resource_builder()->add_css('.fb_dialog, .fb-customerchat:not(.fb_iframe_widget_fluid) iframe { left: 18pt !important; right: auto !important; }', 'fbmessenger-chat');
		}
	}
}