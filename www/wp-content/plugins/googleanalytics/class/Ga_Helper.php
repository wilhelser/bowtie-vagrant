<?php

class Ga_Helper {

	const ROLE_ID_PREFIX = "role-id-";

	const GA_DEFAULT_WEB_ID = "UA-0000000-0";

	const GA_STATISTICS_PAGE_URL = "admin.php?page=googleanalytics";
	
	const GA_SETTINGS_PAGE_URL = "admin.php?page=googleanalytics/settings";
	
	const DASHBOARD_PAGE_NAME = "dashboard";

	/**
	 * Init plugin actions.
	 *
	 */
	public static function init() {
		if ( ! is_admin() ) {
			Ga_Frontend::add_actions();
		}

		if ( is_admin() ) {
			Ga_Admin::add_filters();
			Ga_Admin::add_actions();
			Ga_Admin::init_oauth();
			Ga_Admin::handle_actions();
		}
	}

	/**
	 * Checks if current page is a WordPress dashboard.
	 * @return int
	 */
	public static function is_plugin_page() {
		$site = get_current_screen();

		return preg_match( '/' . GA_NAME . '/', $site->base );
	}

	/**
	 * Checks if current page is a WordPress dashboard.
	 * @return number
	 */
	public static function is_dashboard_page() {
		$site = get_current_screen();

		return preg_match( '/' . self::DASHBOARD_PAGE_NAME . '/', $site->base );
	}

	/**
	 * Check whether the plugin is configured.
	 *
	 * @param String $web_id
	 *
	 * @return boolean
	 */
	public static function is_configured( $web_id ) {
		return $web_id !== self::GA_DEFAULT_WEB_ID;
	}

	/**
	 * Prepare an array of current site's user roles
	 *
	 * return array
	 */
	public static function get_user_roles() {
		global $wp_roles;
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}

		return $wp_roles->get_names();
	}

	/**
	 * Prepare a role ID.
	 *
	 * The role ID is derived from the role's name and will be used
	 * in its setting name in the additional settings.
	 *
	 * @param string $role_name Role name
	 *
	 * @return string
	 */
	public static function prepare_role_id( $role_name ) {
		return self::ROLE_ID_PREFIX . strtolower( preg_replace( '/[\W]/', '-', before_last_bar( $role_name ) ) );
	}

	/**
	 * Prepares role id.
	 *
	 * @param $v
	 * @param $k
	 */
	public static function prepare_role( &$v, $k ) {
		$v = self::prepare_role_id( $v );
	}

	/**
	 * Checks whether user role is excluded from adding UA code.
	 *
	 * @return boolean
	 */
	public static function can_add_ga_code() {
		$current_user  = wp_get_current_user();
		$user_roles    = ! empty( $current_user->roles ) ? $current_user->roles : array();
		$exclude_roles = json_decode( get_option( Ga_Admin::GA_EXCLUDE_ROLES_OPTION_NAME ), true );

		array_walk( $user_roles, 'Ga_Helper::prepare_role' );

		$return = true;
		foreach ( $user_roles as $role ) {
			if ( ! empty( $exclude_roles[ $role ] ) ) {
				$return = false;
				break;
			}
		}

		return $return;
	}

	/**
	 * Adds ga dashboard widget HTML code for a WordPress
	 * Dashboard widget hook.
	 */
	public static function add_ga_dashboard_widget() {
		echo self::get_ga_dashboard_widget();
	}

	/**
	 * Generates dashboard widget HTML code.
	 *
	 * @param string $date_range Google Analytics specific date range string.
	 * @param boolean $text_mode
	 * @param boolean $ajax
	 *
	 * @return null | string HTML dashboard widget code.
	 */
	public static function get_ga_dashboard_widget( $date_range = null, $text_mode = false, $ajax = false ) {
		if ( empty( $date_range ) ) {
			$date_range = '30daysAgo';
		}

		// Get chart and boxes data
		$data = self::get_dashboard_widget_data( $date_range );

		if ( $text_mode ) {
			return self::get_chart_page( 'ga_dashboard_widget' . ( $ajax ? "_ajax" : "" ), array(
				'chart' => $data['chart'],
				'boxes' => $data['boxes']
			) );
		} else {
			echo self::get_chart_page( 'ga_dashboard_widget' . ( $ajax ? "_ajax" : "" ), array(
				'chart'            => $data['chart'],
				'boxes'            => $data['boxes'],
				'more_details_url' => admin_url( self::GA_STATISTICS_PAGE_URL )
			) );
		}

		return null;
	}

	/**
	 * Generates JSON data string for AJAX calls.
	 *
	 * @param string $date_range
	 * @param string $metric
	 * @param boolean $text_mode
	 * @param boolean $ajax
	 *
	 * @return string|false Returns JSON data string
	 */
	public static function get_ga_dashboard_widget_data_json( $date_range = null, $metric = null, $text_mode = false, $ajax = false ) {
		if ( empty( $date_range ) ) {
			$date_range = '30daysAgo';
		}

		if ( empty( $metric ) ) {
			$metric = 'pageviews';
		}

		$data = self::get_dashboard_widget_data( $date_range, $metric );

		return wp_json_encode( $data );
	}

	/**
	 * Gets dashboard widget data.
	 *
	 * @param date_range
	 * @param metric
	 *
	 * @return array Return chart and boxes data
	 */
	private static function get_dashboard_widget_data( $date_range, $metric = null ) {
		$selected = self::get_selected_account_data( true );

		$query_params = Ga_Stats::get_query( 'main_chart', $selected['view_id'], $date_range, $metric );
		$stats_data   = Ga_Admin::api_client()->call( 'ga_api_data', array(

			$query_params
		) );

		$boxes_query = Ga_Stats::get_query( 'dashboard_boxes', $selected['view_id'], $date_range );
		$boxes_data  = Ga_Admin::api_client()->call( 'ga_api_data', array(

			$boxes_query
		) );

		$chart = ! empty( $stats_data ) ? Ga_Stats::get_dashboard_chart( $stats_data->getData() ) : array();
		$boxes = ! empty( $boxes_data ) ? Ga_Stats::get_dashboard_boxes_data( $boxes_data->getData() ) : array();
		
		return array(

			'chart' => $chart,
			'boxes' => $boxes
		);
	}

	public static function is_account_selected() {
		return self::get_selected_account_data();
	}

	/**
	 * Returns HTML code of the chart page or a notice.
	 *
	 * @param chart
	 *
	 * @return string Returns HTML code
	 */
	public static function get_chart_page( $view, $params ) {

		$message = sprintf( __( 'Statistics can only be seen after you authenticate with your Google account on the <a href="%s">Settings page</a>.' ), admin_url( self::GA_SETTINGS_PAGE_URL ) );

		if ( self::is_authorized() && ! self::is_code_manually_enabled() ) {
			if ( self::is_account_selected() ) {
				if ( $params ) {
					return Ga_View::load( $view, $params, true );
				} else {
					return self::ga_oauth_notice( sprintf( 'Please configure your <a href="%s">Google Analytics settings</a>.', admin_url( self::GA_SETTINGS_PAGE_URL ) ) );
				}
			} else {
				return self::ga_oauth_notice( $message );
			}
		} else {
			return self::ga_oauth_notice( $message );
		}
	}

	/**
	 * Checks whether users is authorized with Google.
	 *
	 * @return boolean
	 */
	public static function is_authorized() {
		return Ga_Admin::api_client()->get_instance()->is_authorized();
	}

	/**
	 * Wrapper for WordPress method get_option
	 *
	 * @param string $name Option name
	 *
	 * @return NULL|mixed|boolean
	 */
	public static function get_option( $name ) {
		$opt = get_option( $name );

		return ! empty( $opt ) ? $opt : null;
	}

	/**
	 * Wrapper for WordPress method update_option
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return NULL|boolean
	 */
	public static function update_option( $name, $value ) {
		$opt = update_option( $name, $value );

		return ! empty( $opt ) ? $opt : null;
	}

	/**
	 * Loads ga notice HTML code with gicen message included.
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public static function ga_oauth_notice( $message ) {
		return Ga_View::load( 'ga_oauth_notice', array(

			'msg' => $message
		), true );
	}

	/**
	 * Gets data according to selected GA account.
	 *
	 * @param boolean $assoc
	 *
	 * @return mixed
	 */
	public static function get_selected_account_data( $assoc = false ) {
		$data = json_decode( self::get_option( Ga_Admin::GA_SELECTED_ACCOUNT ) );
		$data = ( ! empty( $data ) && count( $data ) == 3 ) ? $data : false;

		if ( $data ) {
			if ( $assoc ) {
				return array(

					'account_id'      => $data[0],
					'web_property_id' => $data[1],
					'view_id'         => $data[2]
				);
			} else {
				return $data;
			}
		}

		return false;
	}

	/**
	 * Chekcs whether option for manually UA-code
	 * @return NULL|mixed|boolean
	 */
	public static function is_code_manually_enabled() {
		return Ga_Helper::get_option( Ga_Admin::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
	}

	/**
	 * Adds percent sign to the given text.
	 *
	 * @param $text
	 *
	 * @return string
	 */
	public static function format_percent( $text ) {
		$text = self::add_plus( $text );
		return $text . '%';
	}
	
	/**
	 * Adds plus sign before number.
	 *
	 * @param $number
	 *
	 * @return string
	 */
	public static function add_plus( $number ) {
		if ( $number > 0 ){
			return '+' . $number;
		}
		return $number;
	}

	/**
	 * Check whether current user has administrator privileges.
	 *
	 * @return bool
	 */
	public static function is_administrator() {
		if ( current_user_can( 'administrator' ) ) {
			return true;
		}

		return false;
	}

	public static function is_wp_version_valid() {
		$wp_version = get_bloginfo( 'version' );

		return version_compare( $wp_version, Ga_Admin::MIN_WP_VERSION, 'ge' );
	}
	
	/**
	 * Check if terms are accepted
	 *
	 * @return bool
	 */	
	public static function are_terms_accepted() {
		return self::get_option( Ga_Admin::GA_SHARETHIS_TERMS_OPTION_NAME );
	}
	
	/**
	 * Check if sharethis scripts enabled
	 *
	 * @return bool
	 */	
	public static function is_sharethis_included() {
		return GA_SHARETHIS_SCRIPTS_INCLUDED;
	}	
}