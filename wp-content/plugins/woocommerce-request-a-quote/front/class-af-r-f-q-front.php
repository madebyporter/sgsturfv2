<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'AF_R_F_Q_Front' ) ) {

	class AF_R_F_Q_Front extends Addify_Request_For_Quote {

		public function __construct() {
			
			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'afrfq_front_script' ) );

			// Process form submit actions.
			add_action( 'wp_loaded', array( $this, 'addify_convert_to_order_customer' ) );
			add_action( 'wp_loaded', array( $this, 'addify_insert_customer_quote' ) );

			// Display Quote basket in menus.
			add_action( 'wp_nav_menu_items', array( $this, 'afrfq_quote_basket' ), 10, 2 );

			// Request a Quote page short code. 
			add_shortcode( 'addify-quote-request-page', array( $this, 'addify_quote_request_page_shortcode_function' ) );
			add_shortcode( 'addify-mini-quote', array( $this, 'addify_mini_quote_shortcode_function' ) );

			// Add endpoint of quote and process its content.
			add_action( 'init', array( $this, 'addify_add_endpoints' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'addify_new_menu_items' ) );
			add_action( 'woocommerce_account_request-quote_endpoint', array( $this, 'addify_endpoint_content' ) );
			add_filter( 'query_vars', array( $this, 'addify_add_query_vars' ), 0 );
			add_filter( 'the_title', array( $this, 'addify_endpoint_title' ) );
			
			// Start customer session for guest users.
			add_action( 'woocommerce_init', array( $this, 'afrfq_start_customer_session' ) );


			
		}

		

		public function afrfq_load_quote_from_session() {

			if ( isset( wc()->session ) && empty( wc()->session->get( 'quotes' ) ) ) {

				if ( is_user_logged_in() ) {

					$quotes = get_user_meta( get_current_user_id(), 'addify_quote', true );

					if ( ! empty( $quotes ) ) {
						wc()->session->set( 'quotes', $quotes );
					}
				}
			}
		}

		public function addify_insert_customer_quote() {

			if ( !isset( $_POST['afrfq_action'] ) ) {
				return;
			}

			if ( empty( $_REQUEST['afrfq_nonce'] ) || ! wp_verify_nonce( esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['afrfq_nonce'] ) ) ), 'save_afrfq' ) ) {
				die( esc_html__( 'Site security violated.', 'addify_rfq' ) );
			}

			

			unset( $_POST['afrfq_action'] );

			$data = (array) sanitize_meta( '', wp_unslash( $_POST ), '' );

			$af_quote = new AF_R_F_Q_Quote();

			$af_quote->insert_new_quote( array_merge( $data, (array) $_FILES ) );
		}

		public function addify_convert_to_order_customer() {

			if ( !isset( $_POST['addify_convert_to_order_customer'] ) ) {
				return;
			}

			if ( empty( $_REQUEST['_afrfq__wpnonce'] ) || ! wp_verify_nonce( esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['_afrfq__wpnonce'] ) ) ), '_afrfq__wpnonce' ) ) {
				wp_die( esc_html__( 'Site security violated.', 'addify_rfq' ) );
			}

			$quote_id = sanitize_text_field( wp_unslash( $_POST['addify_convert_to_order_customer'] ) );

			if ( empty( intval( $quote_id ) ) ) {
				return;
			}

			$af_quote = new AF_R_F_Q_Quote();

			$af_quote->convert_quote_to_order( $quote_id );
		}

		public function afrfq_start_customer_session() {

			if ( is_user_logged_in() || is_admin() ) {
				return;
			}

			if ( isset( WC()->session ) ) {
				if ( ! WC()->session->has_session() ) {
					WC()->session->set_customer_session_cookie( true );
				}
			}
		}

		public function afrfq_front_script() {

			wp_enqueue_style( 'afrfq-front', AFRFQ_URL . 'assets/css/afrfq_front.css', false, '1.1' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'afrfq-frontj', AFRFQ_URL . 'assets/js/afrfq_front.js', array( 'jquery' ), '1.3', true );

			$afrfq_data = array(
				'admin_url' => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'afquote-ajax-nonce' ),
				'redirect'  => get_option( 'afrfq_redirect_to_quote' ),
				'pageurl'   => get_page_link( get_option( 'addify_atq_page_id', true ) ),
			);
			wp_localize_script( 'afrfq-frontj', 'afrfq_phpvars', $afrfq_data );
			wp_enqueue_style( 'dashicons' );

			if ( 'yes' === get_option( 'afrfq_enable_captcha' ) ) {
				wp_enqueue_script( 'Google reCaptcha JS', '//www.google.com/recaptcha/api.js', array( 'jquery' ), '1.0', true );
			}
		}

		public function addify_mini_quote_shortcode_function() {

			ob_start();

			wc_get_template( 
				'quote/mini-quote.php',
				array(),
				'/woocommerce/addify/rfq/',
				AFRFQ_PLUGIN_DIR . 'templates/'
			);

			return ob_get_clean();
		}

		public function afrfq_quote_basket( $items, $args ) {

			if ( is_user_logged_in() ) {
				$user_role = current( wp_get_current_user()->roles );
			} else {
				$user_role = 'guest';
			}

			if ( !empty( get_option('afrfq_customer_roles') ) && in_array( $user_role , (array) get_option('afrfq_customer_roles') ) ) {
				return $items;
			}
			
			$menu_ids = is_serialized( get_option( 'quote_menu' ) ) ? unserialize( get_option( 'quote_menu' ) ) : get_option( 'quote_menu' );
			
			if ( empty( $menu_ids ) ) {
				return $items;
			}

			if ( isset( $args->menu->term_id ) ) {

				$menu_id = $args->menu->term_id;

			} elseif ( isset( $args->term_id ) ) {

				$menu_id = $args->term_id;

			} elseif ( isset( $args->menu ) ) {

				$menu_id = $args->menu;

			} else {

				$menu_id = 0;
			}
			
			$menu_match = in_array( (string) $menu_id, (array) $menu_ids, true ) ? true : false;

			if ( ! $menu_match ) {
				return $items;
			}

			ob_start();

			wc_get_template( 
				'quote/mini-quote.php',
				array(),
				'/woocommerce/addify/rfq/',
				AFRFQ_PLUGIN_DIR . 'templates/'
			);

			return $items . ob_get_clean();
		}

		public function addify_quote_request_page_shortcode_function() {

			ob_start();

			if ( file_exists( get_stylesheet_directory() . '/woocommerce/addify/rfq/front/addify-quote-request-page.php' ) ) {

				require_once get_stylesheet_directory() . '/woocommerce/addify/rfq/front/addify-quote-request-page.php';

			} else {

				wc_get_template(
					'quote/addify-quote-request-page.php',
					array(),
					'/woocommerce/addify/rfq/',
					AFRFQ_PLUGIN_DIR . 'templates/'
				);
			}

			return ob_get_clean();
		}

		public function addify_add_endpoints() {

			add_rewrite_endpoint( 'request-quote', EP_ROOT | EP_PAGES );
			flush_rewrite_rules();
		}

		public function addify_add_query_vars( $vars ) {
			$vars[] = 'request-quote';
			return $vars;
		}

		public function addify_endpoint_title( $title ) {
			global $wp_query;
			$is_endpoint = isset( $wp_query->query_vars['request-quote'] );
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = esc_html__( 'Quotes', 'addify_rfq' );
				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}
			return $title;
		}

		public function addify_new_menu_items( $items ) {
			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			// Insert your custom endpoint.
			$items['request-quote'] = esc_html__( 'Quotes', 'addify_rfq' );
			// Insert back the logout item.
			$items['customer-logout'] = $logout;
			return $items;
		}

		public function addify_endpoint_content() {

			//Single Quote

			$statuses = array(
				'af_pending'    => __( 'Pending', 'addify_rfq' ),
				'af_in_process' => __( 'In Process', 'addify_rfq' ),
				'af_accepted'   => __( 'Accepted', 'addify_rfq' ),
				'af_converted'  => __( 'Converted to Order', 'addify_rfq' ),
				'af_declined'   => __( 'Declined', 'addify_rfq' ),
				'af_cancelled'  => __( 'Cancelled', 'addify_rfq' ),
			);

			$afrfq_id = get_query_var( 'request-quote' );

			$quote = get_post( $afrfq_id );

			if ( ! empty( $afrfq_id ) && is_a( $quote, 'WP_Post' ) ) {
				$quotedataid = get_post_meta( $afrfq_id, 'quote_proid', true );

				if ( ! empty( $quotedataid ) ) {

					wc_get_template( 
						'my-account/quote-details-my-account-old-quotes.php',
						array(
							'afrfq_id' => $afrfq_id,
							'quote'    => $quote
						),
						'/woocommerce/addify/rfq/',
						AFRFQ_PLUGIN_DIR . 'templates/'
					);

				} else {

					wc_get_template( 
						'my-account/quote-details-my-account.php',
						array(
							'afrfq_id' => $afrfq_id,
							'quote'    => $quote,
							'statuses' => $statuses,
						),
						'/woocommerce/addify/rfq/',
						AFRFQ_PLUGIN_DIR . 'templates/'
					);
					
				}
			} else {

				$customer_quotes = get_posts(
					array(
						'numberposts' => -1,
						'meta_key'    => '_customer_user',
						'meta_value'  => get_current_user_id(),
						'post_type'   => 'addify_quote',
						'post_status' => 'publish',
					)
				);

				wc_get_template( 
					'my-account/quote-list-table.php',
					array(
						'customer_quotes' => $customer_quotes,
						'statuses'        => $statuses,
					),
					'/woocommerce/addify/rfq/',
					AFRFQ_PLUGIN_DIR . 'templates/'
				);
			}
		}
	}
	new AF_R_F_Q_Front();
}
