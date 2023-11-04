<?php
/**
 * EasySocialShareButtons DisplayMethod: Popup
 *
 * @package   EasySocialShareButtons
 * @author    AppsCreo
 * @link      http://appscreo.com/
 * @copyright 2016 AppsCreo
 * @since 3.6
 *
 */

class ESSBDisplayMethodPopup {
	
	public static function generate_popup_code($options, $share_buttons, $is_shortcode, $shortcode_options = array()) {
		$output = '';
		
		$popup_window_title = essb_object_value($options, 'popup_window_title');
		$popup_user_message = essb_object_value($options, 'popup_user_message');
		$popup_user_autoclose = essb_object_value($options, 'popup_user_autoclose');
			
		// display settings
		$popup_user_width = essb_object_value($options, 'popup_user_width');
		$popup_window_popafter = essb_object_value($options, 'popup_window_popafter');
		$popup_user_percent = essb_object_value($options, 'popup_user_percent');
		$popup_display_end = essb_object_bool_value($options, 'popup_display_end');
		$popup_user_manual_show = essb_object_bool_value($options, 'popup_user_manual_show');
		$popup_window_close_after = essb_object_value($options, 'popup_window_close_after');
		$popup_user_notshow_onclose = essb_object_bool_value($options, 'popup_user_notshow_onclose');
		$popup_user_notshow_onclose_all = essb_object_bool_value($options, 'popup_user_notshow_onclose_all');
			
		// new @3.3
		$popup_display_exit = essb_object_bool_value($options, 'popup_display_exit');
			
		if ($is_shortcode) {
			if (!empty($shortcode_popafter)) {
				$popup_window_popafter = $shortcode_popafter;
			}
			
			$shortcode_window_title = isset($shortcode_options['popup_title']) ? $shortcode_options['popup_title'] : '';
			$shortcode_window_message = isset($shortcode_options['popup_message']) ? $shortcode_options['popup_message'] : '';
			$shortcode_pop_on_percent = isset($shortcode_options['popup_percent']) ? $shortcode_options['popup_percent'] : '';
			$shortcode_pop_end = isset($shortcode_options['popup_end']) ? $shortcode_options['popup_end'] : '';
			
			$shortcode_manaualonly = isset($shortcode_options['manualonly']) ? $shortcode_options['manualonly'] : '';
		
			if (!empty($shortcode_window_title)) {
				$popup_window_title = $shortcode_window_title;
			}
			if (!empty($shortcode_window_message)) {
				$popup_user_message = $shortcode_window_message;
			}
			if (!empty($shortcode_pop_on_percent)) {
				$popup_user_percent = $shortcode_pop_on_percent;
			}
			if (!empty($shortcode_pop_end)) {
				$popup_display_end = essb_unified_true($shortcode_pop_end);
			}
			
			//
			if ($shortcode_manaualonly == 'true' || $shortcode_manaualonly == 'yes') {
				$popup_display_end = false;
				$popup_display_exit = false;
				$popup_user_percent = '';
				$popup_user_manual_show = true;
			}
		}
			
		if (!empty($popup_user_message)) {
			$popup_user_message = stripslashes($popup_user_message);
			$popup_user_message = do_shortcode($popup_user_message);
			$popup_user_message = essb_post_details_to_content($popup_user_message);
		}
		if (!empty($popup_window_title)) {
			$popup_window_title = stripslashes($popup_window_title);
			$popup_window_title = essb_post_details_to_content($popup_window_title);
		}
			
		$popup_trigger_oncomment = essb_object_bool_value($options, 'popup_display_comment') ? " essb-popup-oncomment" : "";
			
		if (essb_option_bool_value('popup_mobile_deactivate')) {
			$popup_trigger_oncomment .= ' essb_mobile_hidden';
		}
		if (essb_option_bool_value('popup_tablet_deactivate')) {
			$popup_trigger_oncomment .= ' essb_tablet_hidden';
		}
		if (essb_option_bool_value('popup_desktop_deactivate')) {
			$popup_trigger_oncomment .= ' essb_desktop_hidden';
		}
		
		$output .= sprintf('<div class="essb-popup%10$s" data-width="%1$s" data-load-percent="%2$s" data-load-end="%3$s" data-load-manual="%4$s" data-load-time="%5$s" data-close-after="%6$s" data-close-hide="%7$s" data-close-hide-all="%8$s" data-postid="%9$s" data-exit-intent="%11$s">',
				esc_attr($popup_user_width), esc_attr($popup_user_percent), esc_attr($popup_display_end), 
				esc_attr($popup_user_manual_show), esc_attr($popup_window_popafter),
				esc_attr($popup_window_close_after), esc_attr($popup_user_notshow_onclose), 
				esc_attr($popup_user_notshow_onclose_all), esc_attr(get_the_ID()), 
				esc_attr($popup_trigger_oncomment), esc_attr($popup_display_exit));
		$output .= '<a href="#" class="essb-popup-close" onclick="essb.popup_close(); return false;"></a>';
		$output .= '<div class="essb-popup-content">';
			
		if ($popup_window_title != '') {
			$output .= sprintf('<h3>%1$s</h3>', stripslashes($popup_window_title));
		}
		if ($popup_user_message != '') {
			$output .= sprintf('<div class="essb-popup-content-message">%1$s</div>', stripslashes($popup_user_message));
		}
			
		$output .= $share_buttons;
			
		if ($popup_window_close_after != '') {
			$output .= '<div class="essb_popup_counter_text"></div>';
		}
			
		$output .= '</div>';
		$output .= "</div>";
		$output .= '<div class="essb-popup-shadow" onclick="essb.popup_close(); return false;"></div>';
			
		if ($popup_window_popafter != '') {
			$output .= '<div class="essb-forced-hidden" id="essb_settings_popafter_counter"></div>';
		}
		if ($popup_user_autoclose != '') {
			$output .= sprintf('<div id="essb_settings_popup_user_autoclose" class="essb-forced-hidden">%1$s</div>', $popup_user_autoclose);
		}
		
		return $output;
	}
	
}