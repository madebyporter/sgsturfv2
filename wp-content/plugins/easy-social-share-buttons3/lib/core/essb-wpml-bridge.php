<?php

/**
 * ESSBWpmlBridge
 * 
 * Provide sameless integration with WPML to translate build in plugin settings
 *
 * @author appscreo
 * @since 4.1
 * @package EasySocialShareButtons
 *
 */
class ESSBWpmlBridge {
	public static function isWpmlActive() {
		if (class_exists ( 'SitePress' ))
			return (true);
		else
			return (false);
	}
	
	public static function getLanguages() {
		global $sitepress;
		
		$response = array ();
		
		if (class_exists ( 'SitePress' )) {
			$arrLangs = $sitepress->get_active_languages ();
			
			foreach ( $arrLangs as $code => $arrLang ) {
				$name = $arrLang ['native_name'];
				$response [$code] = $name;
			}
		}
		
		if (function_exists('pll_languages_list')) {
			$languages = pll_languages_list();
			foreach ($languages as $lang) {
				$response[$lang] = $lang;
 			}
 		}
		return ($response);
	}
	
	public static function getLanguagesSimplified() {
		global $sitepress;
		$response = array ();
		
		if (class_exists ( 'SitePress' )) {
			$arrLangs = $sitepress->get_active_languages ();
				
			foreach ( $arrLangs as $code => $arrLang ) {
				$name = $arrLang ['native_name'];
				$response [] = '['.$code .'] '. $name;
			}
		}
		
		if (function_exists('pll_languages_list')) {
			$response = pll_languages_list();
		}
		
		return ($response);
		
	}
	
	private function getLangDetails($code) {
		global $wpdb;
		
		$details = $wpdb->get_row ( $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "icl_languages WHERE code='%s'", $code) );
		
		if (! empty ( $details ))
			$details = ( array ) $details;
		
		return ($details);
	}
	
	/**
	 * get language title by code
	 */
	public static function getLangTitle($code) {
		
		$langs = self::getLanguages ();
		
		if ($code == 'all')
			return (esc_html__ ( 'All Languages', 'essb' ));
		
		if (array_key_exists ( $code, $langs ))
			return ($langs [$code]);
		
		$details = self::getLangDetails ( $code );
		if (! empty ( $details ))
			return ($details ['english_name']);
		
		return ('');
	
	}
	
	/**
	 * get current language
	 */
	public static function getCurrentLang() {
		global $sitepress;
		$lang = '';
		
		if (class_exists('SitePress')) {
			if (is_admin ())
				$lang = $sitepress->get_default_language ();
			else
				$lang = ICL_LANGUAGE_CODE;
		}
		
		return ($lang);
	}
	
	public static function getFrontEndLanugage() {
		if (class_exists('SitePress')) {
			return ICL_LANGUAGE_CODE;
		}
		if (function_exists('pll_current_language')) {
			return pll_current_language();
		}
	}
}


if (!function_exists('essb_wpml_option_value')) {
	function essb_wpml_option_value($param, $code = '') {
		global $essb_translate_options;
				
		if ($code == '')
			$code = ESSBWpmlBridge::getCurrentLang();

		$param .= '_'.$code;
		
		return isset($essb_translate_options[$param]) ? $essb_translate_options[$param] : '';
		
	}
}

if (!function_exists('essb_wpml_translatable_fields')) {
	function essb_wpml_translatable_fields() {
				
		$result = array();
		
		$result['menu3'] = array('type' => 'menu', 'title' => esc_html__('Social Networks', 'essb'));
		$result['networks'] = array('type' => 'networks', 'group' => 'menu3');
		
		$result['menu8'] = array('type' => 'menu', 'title' => esc_html__('Homepage Social Share Optimization Values', 'essb'));
		$result['sso_frontpage_title'] = array('type' => 'input', 'group' => 'menu8', 'title' => esc_html__('Title', 'essb'));
		$result['sso_frontpage_description'] = array('type' => 'input', 'group' => 'menu8', 'title' => esc_html__('Description', 'essb'));
		$result['sso_frontpage_image'] = array('type' => 'input', 'group' => 'menu8', 'title' => esc_html__('Image', 'essb'), 'description' => esc_html__('Provide image URL if you need to make a change for a language.', 'essb'));
		
		$result['menu9'] = array('type' => 'menu', 'title' => esc_html__('Twitter Default Hashtags and Username', 'essb'));
		$result['twitteruser'] = array('type' => 'field', 'group' => 'menu9', 'tab_id' => 'social', 'menu_id' => 'share-1');
		$result['twitterhashtags'] = array('type' => 'field', 'group' => 'menu9', 'tab_id' => 'social', 'menu_id' => 'share-1');
		
		
		$result['menu2'] = array('type' => 'menu', 'title' => esc_html__('E-mail Message', 'essb'));
		$result['mail_captcha'] = array('type' => 'field', 'group' => 'menu2', 'tab_id' => 'social', 'menu_id' => 'share-1');
		$result['mail_captcha_answer'] = array('type' => 'field', 'group' => 'menu2', 'tab_id' => 'social', 'menu_id' => 'share-1');
		$result['mail_subject'] = array('type' => 'field', 'group' => 'menu2', 'tab_id' => 'social', 'menu_id' => 'share-1');
		$result['mail_body'] = array('type' => 'field', 'group' => 'menu2', 'tab_id' => 'social', 'menu_id' => 'share-1');		
		
		$result['menu0'] = array('type' => 'menu', 'title' => esc_html__('Counter texts', 'essb'));
		$result['counter_total_text'] = array('type' => 'input', 'group' => 'menu0', 'title' => esc_html__('Change text "Total" used on Left/Right position', 'essb'));
		$result['activate_total_counter_text'] = array('type' => 'input', 'group' => 'menu0', 'title' => esc_html__('Change "Shares" text (plural)', 'essb'));
		$result['activate_total_counter_text_singular'] = array('type' => 'input', 'group' => 'menu0', 'title' => esc_html__('Change "Share" text (singular)', 'essb'));
		$result['total_counter_afterbefore_text'] = array('type' => 'input', 'group' => 'menu0', 'title' => esc_html__('Change total counter text when before/after styles are active', 'essb'));
		
		$result['menu1'] = array('type' => 'menu', 'title' => esc_html__('Message Before/Above Share Buttons', 'essb'));
		$result['message_share_before_buttons'] = array('type' => 'textarea', 'group' => 'menu1', 'title' => esc_html__('Message before share buttons', 'essb'));
		$result['message_above_share_buttons'] = array('type' => 'textarea', 'group' => 'menu1', 'title' => esc_html__('Message above share buttons', 'essb')); 

		$result['menu4'] = array('type' => 'menu', 'title' => esc_html__('Localization texts', 'essb'));
		$result['heading2'] = array('type' => 'heading', 'group' => 'menu4', 'title' => esc_html__('Email form customization'));
		$result['translate_mail_title'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Share this with a friend', 'essb'));
		$result['translate_mail_email'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Your Email', 'essb'));
		$result['translate_mail_recipient'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Recipient Email', 'essb'));
		$result['translate_mail_custom'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Custom user message', 'essb'));
		$result['translate_mail_cancel'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Cancel', 'essb'));
		$result['translate_mail_send'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Send', 'essb'));
		$result['translate_mail_message_sent'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Message sent!', 'essb'));
		$result['translate_mail_message_invalid_captcha'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Invalid Captcha code!', 'essb'));
		$result['translate_mail_message_error_send'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Error sending message!', 'essb'));

		$result['heading3'] = array('type' => 'heading', 'group' => 'menu4', 'title' => esc_html__('Love this texts'));
		$result['translate_love_thanks'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Thank you for loving this.', 'essb'));
		$result['translate_love_loved'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('You already love this today.', 'essb'));

		$result['heading4'] = array('type' => 'heading', 'group' => 'menu4', 'title' => esc_html__('Translate Click To Tweet text', 'essb'));
		$result['translate_clicktotweet'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Translate Click To Tweet text', 'essb'));

		$result['heading41'] = array('type' => 'heading', 'group' => 'menu4', 'title' => esc_html__('Subscribe forms', 'essb'));
		$result['translate_subscribe_invalidemail'] = array('type' => 'input', 'group' => 'menu4', 'title' => esc_html__('Invalid email address', 'essb'));
		
		
		$result['menu5'] = array('type' => 'menu', 'title' => esc_html__('Subscribe Forms', 'essb'));
		$result['heading51'] = array('type' => 'heading', 'group' => 'menu5', 'title' => esc_html__('Customize MailChimp List'));
		$result['subscribe_mc_list'] = array('type' => 'field', 'group' => 'menu5', 'tab_id' => 'display', 'menu_id' => 'optin-1');
		
		$result = essb_wpml_subscribe_forms_translate('', 'menu5', esc_html__('Design #1', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('2', 'menu5', esc_html__('Design #2', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('3', 'menu5', esc_html__('Design #3', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('4', 'menu5', esc_html__('Design #4', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('5', 'menu5', esc_html__('Design #5', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('6', 'menu5', esc_html__('Design #6', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('7', 'menu5', esc_html__('Design #7', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('8', 'menu5', esc_html__('Design #8', 'essb'), $result);
		$result = essb_wpml_subscribe_forms_translate('9', 'menu5', esc_html__('Design #9', 'essb'), $result);
		
				
		$result['menu6'] = array('type' => 'menu', 'title' => esc_html__('Display Methods', 'essb'));
		$result['heading12'] = array('type' => 'heading', 'group' => 'menu6', 'title' => esc_html__('Pop-up'));
		$result['popup_window_title'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-11');
		$result['popup_user_message'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-11');

		$result['heading14'] = array('type' => 'heading', 'group' => 'menu6', 'title' => esc_html__('Top Bar'));
		$result['topbar_usercontent'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-9');
		
		$result['heading15'] = array('type' => 'heading', 'group' => 'menu6', 'title' => esc_html__('Bottom Bar'));
		$result['bottombar_usercontent'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-10');
		
		$result['heading16'] = array('type' => 'heading', 'group' => 'menu6', 'title' => esc_html__('Share Booster'));
		$result['booster_title'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-20');
		$result['booster_message'] = array('type' => 'field', 'group' => 'menu6', 'tab_id' => 'where', 'menu_id' => 'display-20');
		
		
		// @since 5.0 followers counter integration
		if (class_exists('ESSBSocialFollowersCounterHelper')) {
			if (!essb_option_bool_value('deactivate_module_followers') && essb_options_bool_value('fanscounter_active')) {
				$result['menu7'] = array('type' => 'menu', 'title' => esc_html__('Followers Counter', 'essb'));
					
				$network_list = ESSBSocialFollowersCounterHelper::available_social_networks();
				foreach ($network_list as $key => $name) {
					$result['socialheading_'.$key] = array('type' => 'heading', 'group' => 'menu7', 'title' => $name);
					$result['essb3fans_'.$key.'_text'] = array('type' => 'field', 'group' => 'menu7', 'tab_id' => 'display', 'menu_id' => 'follow-2', 'followers' => 'true');
					
				}
			}
		}
		
		/**
		 * @since 8.4 Widget titles are added to the WPML
		 */
		$result['menu10'] = array('type' => 'menu', 'title' => esc_html__('Widget Titles', 'essb'));
		$result['widget_title_followers_counter'] = array('type' => 'input', 'group' => 'menu10', 'title' => 'Followers Counter');
		$result['widget_title_profiles'] = array('type' => 'input', 'group' => 'menu10', 'title' => 'Social Profiles');
		
		return $result;
	}
	
	function essb_wpml_subscribe_forms_translate($field_index, $group, $title = '', $result = '') {
		$result['subscribe_heading_'.$field_index] = array('type' => 'heading', 'group' => $group, 'title' => $title);

		$result['subscribe_mc_title'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Title', 'essb'));
		$result['subscribe_mc_text'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Text below title', 'essb'));
		$result['subscribe_mc_name'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Name field text', 'essb'));
		$result['subscribe_mc_email'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Email field text', 'essb'));
		$result['subscribe_mc_button'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Subscribe button text', 'essb'));
		$result['subscribe_mc_footer'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Footer text', 'essb'));
		$result['subscribe_mc_success'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Success message', 'essb'));
		$result['subscribe_mc_error'.$field_index] = array('type' => 'input', 'group' => $group, 'title' => esc_html__('Error message', 'essb'));
		
		
		return $result;
	}
}


if (!function_exists('essb4_options_multilanguage_load')) {
	function essb4_options_multilanguage_load($options) {
		
		$site_language = ESSBWpmlBridge::getFrontEndLanugage();
		
		$translatable_options = essb_wpml_translatable_fields();
		foreach ($translatable_options as $key => $data) {
			$type = isset($data['type']) ? $data['type'] : '';
			if ($type == 'field' || $type == 'input' || $type == 'textarea') {
				
				$is_followers = isset($data['followers']) ? $data['followers'] : '';
				if ($is_followers == 'true') { 
					continue;
				}
				
				$translation = essb_wpml_option_value('wpml_'.$key, $site_language);
				if ($translation != '') {
					$options[$key] = $translation;
				}
			}
			if ($type == 'networks') {
				$networks_list = essb_available_social_networks();
				foreach ($networks_list as $network => $network_data) {
					$translation = essb_wpml_option_value('wpml_user_network_name_'.$network, $site_language);
					if ($translation != '') {
						$options['user_network_name_'.$network] = $translation;
					}
					
					$translation = essb_wpml_option_value('wpml_hovertext_'.$network, $site_language);
					if ($translation != '') {
						$options['hovertext_'.$network] = $translation;
					}
				}
				
			}
		}
		
		return $options;
	}
	
	add_filter('essb4_options_multilanguage', 'essb4_options_multilanguage_load');
	
	function essb4_followeroptions_multilanguage_load($options) {
		
		global $essb_socialfans_options;
		
		$site_language = ESSBWpmlBridge::getFrontEndLanugage();
		
		$translatable_options = essb_wpml_translatable_fields();
		foreach ($translatable_options as $key => $data) {
			$type = isset($data['type']) ? $data['type'] : '';
			if ($type == 'field') {
		
				$is_followers = isset($data['followers']) ? $data['followers'] : '';
				if ($is_followers != 'true') {
					continue;
				}
		
				$translate_key = 'wpml_'.$key.'_'.$site_language;
				
				$translation = isset($essb_socialfans_options[$translate_key]) ? $essb_socialfans_options[$translate_key] : '';
				
				if ($translation != '') {
					$options[$key] = $translation;
				}
			}			
		}		
		
		return $options;
	}
	
	add_filter('essb4_followeroptions_multilanguage', 'essb4_followeroptions_multilanguage_load');
}