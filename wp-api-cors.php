<?php

/**
 * Plugin Name: WP API CORS
 * Description: Enables sensible (and pluggable) CORS settings for WP-API
 * Author: Will Anderson
 * Author URI: http://willi.am/
 * Version 0.1
 * Plugin URI: https://github.com/itsananderson/wp-api-cors
 * License: GPL3
 */

 class WP_Api_Cors {
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'add_oauth_cors_headers' ), -101 );
		add_action( 'rest_api_init', array( $this, 'remove_default_cors_headers'), 11 );
		add_action( 'rest_api_init', array( $this, 'add_hookable_cors_headers'), 20 );
		add_action( 'login_form_oauth1_authorize', array( $this, 'add_external_host_filter' ), 0 );
	}

	public function remove_default_cors_headers() {
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
	}

	public function add_oauth_cors_headers() {
		if ( empty( $GLOBALS['wp']->query_vars['json_oauth_route'] ) ) {
			return;
		}

		$this->send_cors_headers();

		// Oauth1 plugin doesn't gracefully handle OPTIONS requests
		// So we short-circuit and just return headers with an empty response
		if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
			die();
		}
	}

	public function add_external_host_filter() {
		add_filter( 'http_request_host_is_external', array( $this, 'allow_local_oauth_callback' ), 100 );
	}

	public function allow_local_oauth_callback() {
		return true;
	}

	public function add_hookable_cors_headers() {
		add_filter( 'rest_pre_serve_request', array( $this, 'send_cors_headers' ) );
	}

	public function send_cors_headers() {
		$origin = get_http_origin();

		$should_send_allow_origin = apply_filters( 'cors_should_send_allow_origin', !empty( $origin ) );
		$should_send_allow_credentials = apply_filters( 'cors_should_send_allow_credentials', false );
		$should_send_expose_headers = apply_filters( 'cors_should_send_expose_headers', true );
		$should_send_max_age = apply_filters( 'cors_should_send_max_age', false );
		$should_send_allow_methods = apply_filters( 'cors_should_send_allow_methods', true );
		$should_send_allow_headers = apply_filters( 'cors_should_send_allow_headers', true );

		if ( $should_send_allow_origin ) {
			$allowed_origins = apply_filters( 'cors_allowed_origins', array( $origin ) );
			if ( in_array( $origin, $allowed_origins , true ) ) {
				header( 'Access-Control-Allow-Origin: ' . esc_url_raw( apply_filters( 'cors_allow_origin_value', $origin, $allowed_origins ) ) );
			} else {
				do_action( 'cors_origin_disallowed', $origin, $allowed_origins );
			}
		}

		if ( $should_send_allow_credentials ) {
			header( 'Access-Control-Allow-Credentials: ' . apply_filters( 'cors_allow_credentials_value', 'true' ) );
		}

		if ( $should_send_expose_headers ) {
			$exposed_headers = apply_filters( 'cors_exposed_headers', array( 'X-WP-Total', 'X-WP-TotalPages' ) );
			header( 'Access-Control-Expose-Headers: ' . apply_filters( 'cors_expose_headers_value', implode( ', ', $exposed_headers ) ) );
		}

		if ( $should_send_max_age ) {
			header( 'Access-Control-Max-Age: ' . apply_filters( 'cors_max_age_value', 600 ) ); // Default to 10 minutes, which is the max Chrome respects
		}

		if ( $should_send_allow_methods ) {
			$allowed_methods = apply_filters( 'cors_allowed_methods', array( 'POST', 'GET', 'OPTIONS', 'PUT', 'DELETE' ) );
			header( 'Acess-Control-Allow-Methods: ' . apply_filters( 'cors_allow_methods_value', implode( ', ', $allowed_methods ) ) );
		}

		if ( $should_send_allow_headers ) {
			$allowed_headers = apply_filters( 'cors_allowed_headers', array( 'Authorization' ) );
			header( 'Access-Control-Allow-Headers: ' . apply_filters( 'cors_allow_headers_value', implode( ', ', $allowed_headers ) ) );
		}
	}
 }

 global $wp_api_cors;
 $wp_api_cors = new WP_Api_Cors();
