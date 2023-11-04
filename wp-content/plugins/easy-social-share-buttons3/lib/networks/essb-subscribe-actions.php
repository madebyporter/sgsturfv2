<?php
/**
 * Subsctibe Actions
 *
 * @since 3.6
 *
 * @package EasySocialShareButtons
 * @author  appscreo <https://codecanyon.net/user/appscreo/portfolio>
 */

class ESSBNetworks_SubscribeActions {

	private static $version = "2.0";

	public static function process_subscribe() {
		global $essb_options;
		// send no caching headers

		define ( 'DOING_AJAX', true );

		send_nosniff_header ();
		header ( 'content-type: application/json' );
		header ( 'Cache-Control: no-cache' );
		header ( 'Pragma: no-cache' );

		$output = array("code" => "", "message" => "");

		$user_email = isset ( $_REQUEST ['mailchimp_email'] ) ? $_REQUEST ['mailchimp_email'] : '';
		$user_name = isset ($_REQUEST['mailchimp_name']) ? $_REQUEST['mailchimp_name'] : '';
		$output['request_mail'] = $user_email;
		
		$validate_captcha = isset($_REQUEST['validate_recaptcha']) ? $_REQUEST['validate_recaptcha'] : '';
		$recaptcha = isset($_REQUEST['recaptcha']) ? $_REQUEST['recaptcha'] : '';
		
		if ($validate_captcha == 'true') {
			$result = self::validate_recaptha($recaptcha);
			if (!$result['valid']) {
				$output['code'] = '90';
				$output['message'] = $result['error'];
			}
		}

		if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
			$output['code'] = "90";
			$output['message'] = esc_html__('Invalid email address', 'essb');

			$translate_subscribe_invalidemail = essb_option_value('translate_subscribe_invalidemail');
			if ($translate_subscribe_invalidemail != '') {
				$output['message'] = $translate_subscribe_invalidemail;
			}
		}
		else {
			$output = self::subscribe($user_email, $user_name);
		}


		print json_encode($output);
	}
	
	public static function should_add_turnstile() {
	    return essb_option_bool_value('subscribe_turnstile') && ! empty( essb_sanitize_option_value('subscribe_turnstile_site') ) && ! empty( essb_sanitize_option_value('subscribe_turnstile_secret') );
	}
	
	public static function validate_recaptha($recaptcha = '') {
		$valid = true;
		$error = '';
		
		if ( empty( $recaptcha ) ) {
			$valid = false;
			$error = esc_html__('Code not filled', 'essb');
		}
		
		//
		if (self::should_add_turnstile()) {
		    $api_results = wp_remote_get( 'https://challenges.cloudflare.com/turnstile/v0/siteverify?secret=' . essb_sanitize_option_value('subscribe_recaptcha_secret') . '&response=' . $recaptcha );
		}
		else {
		  $api_results = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . essb_sanitize_option_value('subscribe_recaptcha_secret') . '&response=' . $recaptcha );
		}
		$results     = json_decode( wp_remote_retrieve_body( $api_results ) );
		if ( empty( $results->success ) ) {
			$valid = false;
			$error = esc_html__( 'Incorrect reCAPTCHA, please try again.', 'essb' );		
		}

		return array('valid' => $valid, 'error' => $error );
	}

	public static function subscribe($user_email, $user_name = '') {
		global $essb_options;

		$debug_mode = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : '';

		$connector = essb_object_value ( $essb_options, 'subscribe_connector', 'mailchimp' );
		if ($connector == '') {
			$connector = 'mailchimp';
		}

		$external_connectors = array();
		if (has_filter('essb_external_subscribe_connectors')) {
			$external_connectors_base = array();
			$external_connectors_base = apply_filters('essb_external_subscribe_connectors', $external_connectors_base);

			foreach($external_connectors_base as $excon => $exname) {
				$external_connectors[] = $excon;
			}
		}


		$output = array();
		$output['name'] = $user_name;
		$output['email'] = $user_email;

		switch ($connector) {
			case "mailchimp":
				if (has_filter('essb_custom_mailing_list_mailchimp')) {
					$mc_list = apply_filters('essb_custom_mailing_list_mailchimp', $mc_list);
				}

				$mc_api = essb_object_value ( $essb_options, 'subscribe_mc_api' );
				$mc_list = essb_object_value ( $essb_options, 'subscribe_mc_list' );
				$mc_welcome = essb_object_bool_value($essb_options, 'subscribe_mc_welcome');
				$mc_double = essb_object_bool_value($essb_options, 'subscribe_mc_double');
				
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$mc_list = $custom_list;
				}

				$result = self::subscribe_mailchimp($mc_api, $mc_list, $user_email, $mc_double, $mc_welcome, $user_name);

				if ($debug_mode == 'true') {
					print_r($result);
				}

				$output['name'] = $user_name;
				$output['email'] = $user_email;

				if ($result) {					
					if ($result == '200') {
						$output['code'] = '1';
						$output['message'] = 'Thank you';
					}
					else {
						$output['code'] = "99";
						$output['message'] = esc_html__('Missing connection', 'essb');

					}
				}
				else {
					$output['code'] = "99";
					$output['message'] = esc_html__('Missing connection', 'essb');
				}
				break;
			case "getresponse":
				$gr_api = essb_object_value ( $essb_options, 'subscribe_gr_api' );
				$gr_list = essb_object_value ( $essb_options, 'subscribe_gr_list' );
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$gr_list = $custom_list;
				}

				$output = self::subscribe_getresponse($gr_api, $gr_list, $user_email, $user_name);
				break;
			case "mymail":
				$mm_list = essb_object_value ( $essb_options, 'subscribe_mm_list' );
				$output = self::subscribe_mymail($mm_list, $user_email, $user_name);
				break;
			case "mailpoet":
				$mp_list = essb_object_value ( $essb_options, 'subscribe_mp_list' );
				$output = self::subscribe_mailpoet($mp_list, $user_email, $user_name);
				break;
			case "mailerlite":
				$ml_api = essb_object_value ( $essb_options, 'subscribe_ml_api' );
				$ml_list = essb_object_value ( $essb_options, 'subscribe_ml_list' );
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$ml_list = $custom_list;
				}
				$output = self::subscribe_mailerlite($ml_api, $ml_list, $user_email, $user_name);
				break;
			case "activecampaign":
				$ac_api_url = essb_object_value ( $essb_options, 'subscribe_ac_api_url' );
				$ac_api = essb_object_value ( $essb_options, 'subscribe_ac_api' );
				$ac_list = essb_object_value ( $essb_options, 'subscribe_ac_list' );
				$ac_form = essb_option_value('subscribe_ac_form');
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$ac_list = $custom_list;
				}
				$output = self::subscribe_activecampaign($ac_api_url, $ac_api, $ac_list, $user_email, $user_name, $ac_form);
				break;
			case "campaignmonitor":
				$cm_api = essb_object_value ( $essb_options, 'subscribe_cm_api' );
				$cm_list = essb_object_value ( $essb_options, 'subscribe_cm_list' );
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$cm_list = $custom_list;
				}
				$output = self::subscribe_campaignmonitor($cm_api, $cm_list, $user_email, $user_name);
				break;
			case "sendinblue":
				$sib_api = essb_object_value ( $essb_options, 'subscribe_sib_api' );
				$sib_list = essb_object_value ( $essb_options, 'subscribe_sib_list' );
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$sib_list = $custom_list;
				}
				$output = self::subscribe_sendinblue($sib_api, $sib_list, $user_email, $user_name);
				break;
			case "madmimi":
				$subscribe_madmimi_login = essb_object_value ( $essb_options, 'subscribe_madmimi_login' );
				$subscribe_madmimi_api = essb_object_value ( $essb_options, 'subscribe_madmimi_api' );
				$subscribe_madmimi_list = essb_object_value ( $essb_options, 'subscribe_madmimi_list' );
				$custom_list = self::design_specific_list();
				if ($custom_list != '') {
					$subscribe_madmimi_list = $custom_list;
				}
				$output = self::subscribe_madmimi($subscribe_madmimi_login, $subscribe_madmimi_api, $subscribe_madmimi_list, $user_email, $user_name);
				break;
			case "conversio":
				$subscribe_conv_api = essb_object_value ( $essb_options, 'subscribe_conv_api' );
				$subscribe_conv_list = essb_object_value ( $essb_options, 'subscribe_conv_list' );
				$subscribe_conv_text = essb_object_value ( $essb_options, 'subscribe_conv_text' );

				$output = self::subscribe_conversio($subscribe_conv_api, $subscribe_conv_list, $subscribe_conv_text, $user_email, $user_name);
				break;
			case 'fluentcrm':
			    $subscribe_fcrm_list = essb_object_value($essb_options, 'subscribe_fcrm_list');
			    self::subscribe_fluentcrm($subscribe_fcrm_list, $user_email, $user_name);
			    break;
			case 'acelle':
			    $subscribe_acelle_url = essb_option_value('subscribe_acelle_url');
			    $subscribe_acelle_api = essb_option_value('subscribe_acelle_api');
			    $subscribe_acelle_listid = essb_option_value('subscribe_acelle_listid');
			    
			    $output = self::subscribe_acelle($subscribe_acelle_url, $subscribe_acelle_api, $subscribe_acelle_listid, $user_email, $user_name);
			    break;
			default:
				$output['code'] = '99';
				$output['message'] = esc_html__('Service is not supported', 'essb');

				if (in_array($connector, $external_connectors)) {
					$output['external_connector'] = $connector;

					$output = apply_filters("essb_subscribe_{$connector}", $user_email, $user_name, $output);
				}

				break;
		}

		// @since 5.3 add if exising thank you redirect
		$thankyou_redirect = essb_option_value('subscribe_success');
		if ($thankyou_redirect != '') {
			$output['redirect_new'] = essb_option_bool_value('subscribe_success_new');
			$output['redirect'] = $thankyou_redirect;
		}

		return $output;
	}
	
	public static function subscribe_acelle($api_url, $api_token, $api_list, $email, $name = '') {
	    $debug_mode = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : '';
	    $response = array();	    
	    
	    try {
            
	        if (!empty($api_url) && !empty($api_token) && !empty($api_list)) {
	            $headers = array(
	                'Content-Type: application/json;charset=UTF-8',
	                'Accept: application/json'
	            );
	            
	            $url_create_user = $api_url . '/api/v1/subscribers';
	            $data_create_user = array(
	                'api_token' => $api_token,
	                'EMAIL' => $email,
	                'FIRST_NAME' => $name,
	                'list_uid' => $api_list
	            );
	            
	            $request = http_build_query($data_create_user);
	            
	            $curl = curl_init($url_create_user);
	            curl_setopt ( $curl, CURLOPT_HTTPHEADER, $headers );	
	            curl_setopt($curl, CURLOPT_POST, true);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
	            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	            curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	            curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	            $response_api = curl_exec($curl);
	            curl_close($curl);
	            
	            $result = json_decode($response_api, true);
	            
	            if ($debug_mode == 'true') {
	                print_r($result);
	            }
	            
	            if (isset($result->subscriber_uid) && !empty($result->subscriber_uid)) {
	                // Add user to the list
	                $url_add_to_list = $api_url . '/api/v1/subscribers/'.esc_attr($result->subscriber_uid).'/subscribe';
	                $data_add_to_list = array(
	                    'api_token' => $api_token,
	                    'list_uid' => $api_list,
	                    'uid ' => $result->subscriber_uid
	                );
	                
	                $request = http_build_query($data_add_to_list);
	                
	                $curl = curl_init($url_add_to_list);
	                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );	
	                curl_setopt($curl, CURLOPT_POST, true);
	                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
	                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
	                curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	                curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	                $response_api = curl_exec($curl);
	                curl_close($curl);
	                
	                if ($debug_mode == 'true') {
	                    print_r($response_api);
	                }
	                
	                $response ['code'] = '1';
	                $response ['message'] = 'Thank you';
	            }
	        }
	        else {
	            // not configured
	            $response ['code'] = "99";
	            $response ['message'] = esc_html__( 'Missing connection', 'essb' );
	        }
	        
    	} catch (Exception $e) {
    	    
    	    if ($debug_mode == 'true') {
    	        print_r($e);
    	    }
    	    $result = false;
    	    
    	    $response ['code'] = "99";
    	    $response ['message'] = esc_html__( 'Missing connection', 'essb' );
    	}
    	
    	return $response;
	}

	public static function subscribe_mailchimp($api_key, $list_id, $email, $double_option = false, $send_welcome = false, $name = '') {

		$position = isset ( $_REQUEST ['position'] ) ? $_REQUEST ['position'] : '';
		$design = isset ( $_REQUEST ['design'] ) ? $_REQUEST ['design'] : '';
		$title = isset ( $_REQUEST ['title'] ) ? $_REQUEST ['title'] : '';
		
		/**
		 * @since 8.0 Adding support for tags
		 */
		$user_tags = essb_sanitize_option_value('subscribe_mc_tags');
		
		$design_tags = self::design_specific_tags();
		if (!empty($design_tags)) {
		    $user_tags = $design_tags;
		}

		$dc = "us1";
		if (strstr ( $api_key, "-" )) {
			list ( $key, $dc ) = explode ( "-", $api_key, 2 );
			if (! $dc)
				$dc = "us1";
		}
		$mailchimp_url = 'https://' . $dc . '.api.mailchimp.com/2.0/lists/subscribe.json';
		$data = array ('apikey' => $api_key,
				'id' => $list_id,
				'email' => array ('email' => $email ),
				'merge_vars' => array (
						'optin_ip' => $_SERVER ['REMOTE_ADDR'] ),
				'replace_interests' => false,
				'double_optin' => ($double_option ? true : false),
				'send_welcome' => ($send_welcome == 'on' ? true : false),
				'update_existing' => true );

		if (!empty($name)) {
			$fname = $name;
			$lname = '';
			if ($space_pos = strpos($name, ' ')) {
				$fname = substr($name, 0, $space_pos);
				$lname = substr($name, $space_pos);
			}

			$data['merge_vars']['FNAME'] = $fname;
			$data['merge_vars']['LNAME'] = $lname;
		}

		$gdpr_field = essb_option_value('subscribe_terms_field');
		if ($gdpr_field != '') {
			$data['merge_vars'][$gdpr_field] = 'Yes';
		}

		$subscribe_mc_custompos = essb_option_value('subscribe_mc_custompos');
		if ($subscribe_mc_custompos != '' && $position != '') {
			$data['merge_vars'][$subscribe_mc_custompos] = $position;
		}

		$subscribe_mc_customdes = essb_option_value('subscribe_mc_customdes');
		if ($subscribe_mc_customdes != '' && $design != '') {
			$data['merge_vars'][$subscribe_mc_customdes] = $design;
		}

		$subscribe_mc_customtitle = essb_option_value('subscribe_mc_customtitle');
		if ($subscribe_mc_customtitle != '' && $title != '') {
			$data['merge_vars'][$subscribe_mc_customtitle] = $title;
		}
		
		/**
		 * @since 8.6 custom fields
		 */
		if (has_filter('essb_custom_subscribe_form_fields')) {
		    $custom_fields = array();
		    $custom_fields = apply_filters('essb_custom_subscribe_form_fields', $custom_fields);
		    
		    foreach ($custom_fields as $key => $field_data) {
		        $param = 'mailchimp_' . $key;
		        $bind = isset($field_data['map']) ? $field_data['map'] : '';
		        $value = isset($_REQUEST[$param]) ? $_REQUEST[$param] : '';
		        
		        if ($param != '' && $value != '') {
		            $data['merge_vars'][$bind] = $value;
		        }
		    }
		}

		$request = json_encode ( $data );
		$response = array();
		try {
			/*$curl = curl_init ( $mailchimp_url );
			curl_setopt ( $curl, CURLOPT_POST, 1 );
			curl_setopt ( $curl, CURLOPT_POSTFIELDS, $request );
			curl_setopt ( $curl, CURLOPT_TIMEOUT, 10 );
			curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $curl, CURLOPT_FORBID_REUSE, 1 );
			curl_setopt ( $curl, CURLOPT_FRESH_CONNECT, 1 );
			curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
			curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
		    

			$response = curl_exec ( $curl );
			curl_close ( $curl );*/
		    
		    $response = self::subscribe_mailchimp_api3($api_key, $list_id, $email, $name, $double_option);
			
			if (!empty($user_tags)) {
			    $tags_raw = explode(',', $user_tags);
			    foreach ($tags_raw as $tag_raw) {
			        $tag_raw = trim($tag_raw);
			        if (!empty($tag_raw)) {
			            $tags_sanitized[] = $tag_raw;
			        }
			    }
			    if (sizeof($tags_sanitized) > 0) {
			        $tags = array('tags' => array());
			        foreach ($tags_sanitized as $tag_sanitized) {
			            $tags['tags'][] = array('name' => $tag_sanitized, 'status' => 'active');
			        }
			        
			        $url = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.urlencode($list_id).'/members/'.md5(strtolower($email)).'/tags';
			        try {
			            $headers = array(
			                'Content-Type: application/json;charset=UTF-8',
			                'Accept: application/json'
			            );
			            
			            $curl = curl_init ( $url );
			            curl_setopt ( $curl, CURLOPT_HTTPHEADER, $headers );		
			            curl_setopt ( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			            curl_setopt ( $curl, CURLOPT_USERPWD, 'lepopup:'.$api_key );			            
			            curl_setopt ( $curl, CURLOPT_POST, 1 );
			            curl_setopt ( $curl, CURLOPT_POSTFIELDS, json_encode($tags) );
			            curl_setopt ( $curl, CURLOPT_TIMEOUT, 10 );
			            curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
			            curl_setopt ( $curl, CURLOPT_FORBID_REUSE, 1 );
			            curl_setopt ( $curl, CURLOPT_FRESH_CONNECT, 1 );
			            curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
			            curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 );
			            
			            $response_tags = curl_exec ( $curl );
			            curl_close ( $curl );
			        }
			        catch (Exception $ex) {
			        }
			    }
			    
			}
		}
		catch ( Exception $e ) {
		}

		return $response;
	}
	
	public static function subscribe_mailchimp_api3($apiKey, $listId, $email, $name, $double_option) {
	    $fname = '';
	    $lname = '';
	    
	    if (!empty($name)) {
	        $fname = $name;
	        $lname = '';
	        if ($space_pos = strpos($name, ' ')) {
	            $fname = substr($name, 0, $space_pos);
	            $lname = substr($name, $space_pos);
	        }
	        
	    }
	    
	    $memberId = md5(strtolower($email));
	    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
	    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
	    
	    $json = json_encode([
	        'email_address' => $email,
	        'status'        => $double_option ? 'pending': 'subscribed', // "subscribed","unsubscribed","cleaned","pending"
	        'merge_fields'  => [
	            'FNAME'     => $fname,
	            'LNAME'     => $lname
	        ]
	    ]);
	    	    
	    
	    $ch = curl_init($url);
	    
	    curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	    
	    $result = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);	    
	    
	    return $httpCode;
	}

	public static function subscribe_getresponse($api_key, $list_id, $email, $name = '') {

		if (!class_exists('GetResponse')) {
			include_once (ESSB3_PLUGIN_ROOT . 'lib/external/getresponse/getresponse3.php');

		}

		$response = array();

		$api = new GetResponse ( $api_key );
		$campaignName = $list_id;
		$subscriberName = $name;
		$subscriberEmail = $email;


		$campaignId = $api->getCampaignId($campaignName);
		if ($name == '') {
			$parts = explode('@', $email);
			$name = $parts[0];
		}

		if ($campaignId != '') {
			$data = array(
					'campaign' => array('campaignId' => $campaignId),
					'name' => $name,
					'email' => $email,
					'dayOfCycle' => 0,
			);

			$gdpr_field = essb_option_value('subscribe_terms_field');
			if ($gdpr_field != '') {
				$data['customFieldValues'][] = array('customFieldId' => $gdpr_field, 'value' => 'Yes');
			}


			$result = $api->subscribe($data);

			$response ['code'] = '1';
			$response ['message'] = 'Thank you';


		}
		else {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );

		}

		return $response;
	}

	public static function subscribe_mailerlite($api_key, $list_id, $email, $name = '') {

	    
	    $response = array();
	    
	    $headers = array(
	        'X-MailerLite-ApiKey: '.$api_key,
	        'Content-Type: application/json;charset=UTF-8',
	        'Accept: application/json'
	    );
	    
	    $_data = array('email' => $email, 'name' => $name, 'group_id' => $list_id, 'resubscribe' => '1');
	    
	    try {
	        $url = 'https://api.mailerlite.com/api/v2/'.ltrim('groups/'.urlencode($list_id).'/subscribers', '/');
	        $curl = curl_init($url);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	        if (!empty($_data)) {
	            curl_setopt($curl, CURLOPT_POST, true);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($_data));
	        }
	        if (!empty($_method)) {
	            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	        }
	        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        $response_api = curl_exec($curl);
	        curl_close($curl);
	        $result = json_decode($response_api, true);
	        
	        $response ['code'] = '1';
	        $response ['message'] = 'Thank you';
	    } catch (Exception $e) {
	        $result = false;
	        
	        $response ['code'] = "99";
	        $response ['message'] = esc_html__( 'Missing connection', 'essb' );
	    }

	    return $response;
	}

	public static function subscribe_mymail($list_id, $email, $name = '') {
		$response = array();


		if (function_exists('mymail_subscribe') || function_exists('mymail') || function_exists('mailster')) {
			$response ['code'] = '1';
			$response ['message'] = 'Thank you';

			if (function_exists('mailster')) {
				$list = mailster('lists')->get($list_id);
			} else {
				$list = get_term_by('id', $list_id, 'newsletter_lists');
			}

			if (!empty($list)) {
				try {
					// set as pending state when double opt-in is set to Yes
					$double = essb_option_bool_value('subscribe_mm_double');
					if (function_exists('mailster')) {
						$entry = array(
								'firstname' => $name,
								'email' => $email,
								'status' => $double ? 0 : 1,
								'ip' => $_SERVER['REMOTE_ADDR'],
								'signup_ip' => $_SERVER['REMOTE_ADDR'],
								'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
								'signup' =>time()
						);

						$subscriber_id = mailster('subscribers')->add($entry, true);
						if (is_wp_error( $subscriber_id )) {
							$response['code'] = '99';
							return $response;
						}
						$result = mailster('subscribers')->assign_lists($subscriber_id, array($list->ID));
					} else {
						$result = mymail_subscribe($_subscriber['{subscription-email}'], array('firstname' => $_subscriber['{subscription-name}']), array($list->slug), $double);
					}
				} catch (Exception $e) {
					$response['code'] = '99';
				}
			}
		}
		else {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}

	// add support of MailPoet3
	// http://beta.docs.mailpoet.com/article/195-add-subscribers-through-your-own-form-or-plugin

	public static function subscribe_mailpoet($list_id, $email, $name = '') {
		$response = array();



			if (class_exists('WYSIJA')) {
				try {
					$response ['code'] = '1';
					$response ['message'] = 'Thank you';
					$user_data = array(
							'email' => $email,
							'firstname' => $name,
							'lastname' => '');
					$data_subscriber = array(
							'user' => $user_data,
							'user_list' => array('list_ids' => array($list_id))
					);
					$helper_user = WYSIJA::get('user','helper');
					$helper_user->addSubscriber($data_subscriber);
				} catch (Exception $e) {
					$response['code'] = '99';
					$response ['message'] = esc_html__( 'Missing connection', 'essb' );
				}
			}

			if (class_exists('\MailPoet\API\API')) {
				try {
					$response ['code'] = '1';
					$response ['message'] = 'Thank you';
					$user_data = array(
							'email' => $email,
							'first_name' => $name,
							'last_name' => '');
							$data_subscriber = array(
								'user' => $user_data,
								'user_list' => array('list_ids' => array($list_id))
					);

					$gdpr_field = essb_option_value('subscribe_terms_field');
					if ($gdpr_field != '') {
						$user_data['cf_'.$gdpr_field] = 'Yes';
					}

					$subscriber = \MailPoet\API\API::MP('v1')->addSubscriber($user_data, array($list_id));
				} catch (Exception $e) {
					$response['code'] = '99';
					$response ['message'] = esc_html__( 'Missing connection', 'essb' );
				}
			}

		return $response;
	}

	public static function subscribe_activecampaign($api_url, $api_key, $list_id, $email, $name = '', $ac_form = '') {

		$response = array();

		$data = array(
				'api_action' => 'contact_add',
				'api_key' => $api_key,
				'api_output' => 'serialize',
				'p['.$list_id.']' => $list_id,
				'email' => $email
		);

		if ($name != '') {
			$data['first_name'] = $name;
			$data['last_name'] = '';
		}

		$gdpr_field = essb_option_value('subscribe_terms_field');
		if ($gdpr_field != '') {
			$data['field['.$gdpr_field.',0]'] = 'Yes';
		}

		if ($ac_form != '') {
			$data['form'] = $ac_form;
		}

		$request = http_build_query($data);

		try {
			$url = str_replace('https://', 'http://', $api_url);
			$curl = curl_init($url.'/admin/api.php?api_action=contact_add');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			$server_response = curl_exec($curl);
			curl_close($curl);

			$response ['code'] = '1';
			$response ['message'] = 'Thank you';
		}
		catch (Exception $e) {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}

	public static function subscribe_campaignmonitor($api_key, $list_id, $email, $name = '') {

		$response = array();


		try {
			$options['EmailAddress'] = $email;
			$options['Name'] = $name;
			$options['Resubscribe'] = 'true';
			$options['RestartSubscriptionBasedAutoresponders'] = 'true';

			$gdpr_field = essb_option_value('subscribe_terms_field');
			if ($gdpr_field != '') {
				$options['CustomFields'][] = array('Key' => $gdpr_field, 'Value' => 'Yes');
			}

			$post = json_encode($options);

			$curl = curl_init('http://api.createsend.com/api/v3.2/subscribers/'.urlencode($list_id).'.json');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			

			$header = array(
				'Content-Type: application/json',
				'Content-Length: '.strlen($post),
				'Authorization: Basic '.base64_encode($api_key)
				);

			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

			$server_response = curl_exec($curl);
			curl_close($curl);

			$response ['code'] = '1';
			$response ['message'] = 'Thank you';
		}
		catch (Exception $e) {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}

	public static function subscribe_sendinblue($api_key, $list_id, $email, $name = '') {
	    $response = array();
	    
	    $list_id = str_replace('#', '', $list_id);
	    
	    try {
	        $post_data = array(
	            'listIds' => array(intval($list_id)),
	            'email' => $email,
	            'emailBlacklisted' => false,
	            'updateEnabled' => true
	        );
	        
	        $attributes = array();
	        
	        if (!empty($name)) {
	            
	            $name_field = essb_option_value('subscribe_sib_name_param');
	            
	            if ($name_field) {
	                $attributes[$name_field] = $name;
	            }
	            else {
	               $attributes['LASTNAME'] = $name;
	            }
	        }
	        
	        $gdpr_field = essb_option_value('subscribe_terms_field');
	        if (!empty($gdpr_field)) {
    	        $attributes[$gdpr_field] = 'Yes';
	        }

	        if (!empty($attributes)) $post_data['attributes'] = $attributes;	        
	        
	        $headers = array(
	            'api-key: '.$api_key,
	            'Content-Type: application/json;charset=UTF-8',
	            'Accept: application/json'
	        );	        
	        
	        $url = 'https://api.sendinblue.com/v3/contacts';
	        $curl = curl_init($url);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	        if (!empty($post_data)) {
	            curl_setopt($curl, CURLOPT_POST, true);
	            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
	        }

	        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
	        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        $response_api = curl_exec($curl);
	        curl_close($curl);
	        $result = json_decode($response_api, true);
	        
	        $response ['code'] = '1';
	        $response ['message'] = 'Thank you';
	    }
	    catch (Exception $e) {
	        $response ['code'] = "99";
	        $response ['message'] = esc_html__( 'Missing connection', 'essb' );
	    }
	    
	    return $response;
	}

	public static function subscribe_sendinblue_v2($api_key, $list_id, $email, $name = '') {

		$response = array();


		try {
			$headers = array(
				'api-key: '.$api_key,
				'Content-Type: application/json'
			);
			$data = array(
				'listid' => array($list_id),
				'email' => $email,
				'blacklisted' => 0
			);

			$gdpr_field = essb_option_value('subscribe_terms_field');
			if ($gdpr_field != '') {
				$data['attributes'][$gdpr_field] = 'Yes';
			}

			try {
				$curl = curl_init('https://api.sendinblue.com/v2.0/user/createdituser');
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				$response_api = curl_exec($curl);
				curl_close($curl);
			} catch (Exception $e) {
			}



			$response ['code'] = '1';
			$response ['message'] = 'Thank you';
		}
		catch (Exception $e) {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}

	public static function subscribe_madmimi($username, $api_key, $list_id, $email, $name = '') {

		$response = array();

		try {
			$request = http_build_query(array(
				'email' => $email,
				'first_name' => $name,
				'last_name' => '',
				'username' => $username,
				'api_key' => $api_key
			));

			$curl = curl_init('http://api.madmimi.com/audience_lists/'.$list_id.'/add');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_HEADER, 0);

			$response_api = curl_exec($curl);
			curl_close($curl);

			$response ['code'] = '1';
			$response ['message'] = 'Thank you';
		}
		catch (Exception $e) {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}

	public static function subscribe_conversio($api_key, $list_id, $optin_text, $email, $name = '') {

		$response = array();
		$debug_mode = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : '';

		try {
			$request = http_build_query(array(
				'email' => $email,
				'name' => $name,
				'source' => 'EasySocialShareButtons',
				'sourceType' => 'SubscriptionForm',
				'sourceId' => 'easysocialsharebuttons-subscribeforms',
				'optInText' => $optin_text
			));

			$curl = curl_init('https://app.conversio.com/api/v1/customer-lists/'.$list_id.'/subscriptions');
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-ApiKey: '.$api_key, 'Content-Type: application/json', 'Accept: application/json'));

			$response_api = curl_exec($curl);
			curl_close($curl);
			
			if ($debug_mode == 'true') {
				print_r($response_api);
			}

			$response ['code'] = '1';
			$response ['message'] = 'Thank you';
		}
		catch (Exception $e) {
			$response ['code'] = "99";
			$response ['message'] = esc_html__( 'Missing connection', 'essb' );
		}

		return $response;
	}
	
	public static function subscribe_fluentcrm($list_id, $email, $name = '') {
	    $response = array();
	    $debug_mode = isset($_REQUEST['debug']) ? $_REQUEST['debug'] : '';
	    
	    try {
	        $contactApi = FluentCrmApi('contacts');
	        
	        $user_lists = array();
	        $user_lists[] = $list_id;
	        
	        /*
	         * Update/Insert a contact
	         * You can create or update a contact in a single call
	         */
	        
	        $data = [
	            'first_name' => $name,
	            'last_name' => '',
	            'email' => $email, // requied
	            'status' => 'pending',
	            //'tags' => [1,2,3], // tag ids as an array
	            'lists' => $user_lists // list ids as an array
	        ];
	        
	        $contact = $contactApi->createOrUpdate($data);
	        
	        // send a double opt-in email if the status is pending
	        if($contact->status == 'pending') {
	            $contact->sendDoubleOptinEmail();
	        }
	        
	        $response ['code'] = '1';
	        $response ['message'] = 'Thank you';
	    }
	    catch (Exception $e) {
	        $response ['code'] = "99";
	        $response ['message'] = esc_html__( 'Missing connection', 'essb' );
	        	        
	        if ($debug_mode == 'true') {
	            print_r($e);
	        }
	    }
	    
	    return $response;
	}
	
	/**
	 * Getting the custom form list if set
	 * 
	 * @return string
	 */
	public static function design_specific_list() {
		$r = '';
		
		$custom_list = '';
		$design = isset($_REQUEST['design']) ? $_REQUEST['design'] : '';
		if ($design != '') {
			if ($design == 'design1') {
				$custom_list = essb_option_value('subscribe_mc_customlist');
			}
			else if ($design == 'design2') {
				$custom_list = essb_option_value('subscribe_mc_customlist2');
			}
			else if ($design == 'design3') {
				$custom_list = essb_option_value('subscribe_mc_customlist3');
			}
			else if ($design == 'design4') {
				$custom_list = essb_option_value('subscribe_mc_customlist4');
			}
			else if ($design == 'design5') {
				$custom_list = essb_option_value('subscribe_mc_customlist5');
			}
			else if ($design == 'design6') {
				$custom_list = essb_option_value('subscribe_mc_customlist6');
			}
			else if ($design == 'design7') {
				$custom_list = essb_option_value('subscribe_mc_customlist7');
			}
			else if ($design == 'design8') {
				$custom_list = essb_option_value('subscribe_mc_customlist8');
			}
			else if ($design == 'design9') {
				$custom_list = essb_option_value('subscribe_mc_customlist9');
			}
			else {
				// custom design
				if (! function_exists ( 'essb5_get_form_designs' )) {
					include_once (ESSB3_PLUGIN_ROOT . 'lib/admin/helpers/formdesigner-helper.php');
				}
				
				$key = str_replace('userdesign-', '', $design);
				$user_forms = essb5_get_form_designs();
				$options = isset($user_forms[$key]) ? $user_forms[$key] : array();
				
				$custom_list = stripslashes(essb_array_value('customlist', $options));
			}
		}
		
		if ($custom_list != '') { $r = $custom_list; }
		
		return $r;
	}
	

    /**
     * Get design specific MailChimp tags
     * 
     * @return string
     */
	public static function design_specific_tags() {
	    $r = '';
	    
	    $custom_list = '';
	    $design = isset($_REQUEST['design']) ? $_REQUEST['design'] : '';
	    if ($design != '') {
	        if ($design == 'design1') {
	            $custom_list = essb_option_value('subscribe_mc_customtags');
	        }
	        else if ($design == 'design2') {
	            $custom_list = essb_option_value('subscribe_mc_customtags2');
	        }
	        else if ($design == 'design3') {
	            $custom_list = essb_option_value('subscribe_mc_customtags3');
	        }
	        else if ($design == 'design4') {
	            $custom_list = essb_option_value('subscribe_mc_customtags4');
	        }
	        else if ($design == 'design5') {
	            $custom_list = essb_option_value('subscribe_mc_customtags5');
	        }
	        else if ($design == 'design6') {
	            $custom_list = essb_option_value('subscribe_mc_customtags6');
	        }
	        else if ($design == 'design7') {
	            $custom_list = essb_option_value('subscribe_mc_customtags7');
	        }
	        else if ($design == 'design8') {
	            $custom_list = essb_option_value('subscribe_mc_customtags8');
	        }
	        else if ($design == 'design9') {
	            $custom_list = essb_option_value('subscribe_mc_customtags9');
	        }
	        else {
	            // custom design
	            if (! function_exists ( 'essb5_get_form_designs' )) {
	                include_once (ESSB3_PLUGIN_ROOT . 'lib/admin/helpers/formdesigner-helper.php');
	            }
	            
	            $key = str_replace('userdesign-', '', $design);
	            $user_forms = essb5_get_form_designs();
	            $options = isset($user_forms[$key]) ? $user_forms[$key] : array();
	            
	            $custom_list = stripslashes(essb_array_value('customtags', $options));
	        }
	    }
	    
	    if ($custom_list != '') { $r = $custom_list; }
	    
	    return $r;
	}
	
}
