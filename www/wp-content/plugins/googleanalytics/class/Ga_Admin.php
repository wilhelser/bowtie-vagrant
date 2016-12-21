<?php

class Ga_Admin {

	const GA_WEB_PROPERTY_ID_OPTION_NAME = 'googleanalytics_web_property_id';

	const GA_EXCLUDE_ROLES_OPTION_NAME = 'googleanalytics_exclude_roles';

	const GA_SHARETHIS_TERMS_OPTION_NAME = 'googleanalytics_sharethis_terms';

	const GA_HIDE_TERMS_OPTION_NAME = 'googleanalytics_hide_terms';

	const GA_VERSION_OPTION_NAME = 'googleanalytics_version';

	const GA_SELECTED_ACCOUNT = 'googleanalytics_selected_account';

	const GA_OAUTH_AUTH_CODE_OPTION_NAME = 'googleanalytics_oauth_auth_code';

	const GA_OAUTH_AUTH_TOKEN_OPTION_NAME = 'googleanalytics_oauth_auth_token';

	const GA_ACCOUNT_DATA_OPTION_NAME = 'googleanalytics_account_data';

	const GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME = 'googleanalytics_web_property_id_manually';

	const GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME = 'googleanalytics_web_property_id_manually_value';

	const MIN_WP_VERSION = '3.8';

	/**
	 * Instantiate API client.
	 *
	 * @return Ga_Lib_Api_Client|null
	 */
	public static function api_client() {
		$instance = Ga_Lib_Api_Client::get_instance();
		$token    = Ga_Helper::get_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		try {
			if ( ! empty( $token ) ) {
				$token = json_decode( $token, true );
				$instance->set_access_token( $token );
			}
		} catch ( Exception $e ) {
			Ga_Helper::ga_oauth_notice( $e->getMessage() );
		}

		return $instance;
	}

	/*
	 * Initializes plugin's options during plugin activation process.
	 */
	public static function activate_googleanalytics() {
		add_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, Ga_Helper::GA_DEFAULT_WEB_ID );
		add_option( self::GA_EXCLUDE_ROLES_OPTION_NAME, wp_json_encode( array() ) );
		add_option( self::GA_SHARETHIS_TERMS_OPTION_NAME, false );
		add_option( self::GA_HIDE_TERMS_OPTION_NAME, false );
		add_option( self::GA_VERSION_OPTION_NAME );
		add_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		add_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		add_option( self::GA_ACCOUNT_DATA_OPTION_NAME );
		add_option( self::GA_SELECTED_ACCOUNT );
		add_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
		add_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
	}

	/*
	 * Deletes plugin's options during plugin activation process.
	 */
	public static function deactivate_googleanalytics() {
		delete_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		delete_option( self::GA_EXCLUDE_ROLES_OPTION_NAME );
		delete_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		delete_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME );
		delete_option( self::GA_ACCOUNT_DATA_OPTION_NAME );
		delete_option( self::GA_SELECTED_ACCOUNT );
		delete_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
		delete_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
	}

	/**
	 * Deletes plugin's options during plugin uninstallation process.
	 */
	public static function uninstall_googleanalytics() {
		delete_option( self::GA_SHARETHIS_TERMS_OPTION_NAME );
		delete_option( self::GA_HIDE_TERMS_OPTION_NAME );
		delete_option( self::GA_VERSION_OPTION_NAME );
	}

	/**
	 * Do actions during plugin load.
	 */
	public static function loaded_googleanalytics() {
		self::update_googleanalytics();
	}

	/**
	 * Update hook fires when plugin is being loaded.
	 */
	public static function update_googleanalytics() {

		$version = get_option( self::GA_VERSION_OPTION_NAME );
		$installed_version = get_option( self::GA_VERSION_OPTION_NAME, '1.0.7' );
		$old_property_value = Ga_Helper::get_option( 'web_property_id' );
		if ( version_compare( $installed_version, GOOGLEANALYTICS_VERSION, 'eq' ) ) {
			return;
		}
		if ( empty( $old_property_value ) && empty( $version ) ) {
			update_option( self::GA_SHARETHIS_TERMS_OPTION_NAME, true );
		}

		if ( version_compare( $installed_version, GOOGLEANALYTICS_VERSION, 'lt' ) ) {

			if ( ! empty( $old_property_value ) ) {
				Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME, $old_property_value );
				Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME, 1 );
				delete_option( 'web_property_id' );
			}
		}

		update_option( self::GA_VERSION_OPTION_NAME, GOOGLEANALYTICS_VERSION );
	}


	public static function preupdate_exclude_roles( $new_value, $old_value ) {
		if ( !Ga_Helper::are_terms_accepted()){
			return '';
		}
		return wp_json_encode( $new_value );
	}

	/**
	 * Pre-update hook for preparing JSON structure.
	 *
	 * @param $new_value
	 * @param $old_value
	 *
	 * @return mixed
	 */
	public static function preupdate_selected_account( $new_value, $old_value ) {
		$data = explode( "_", $new_value );
		if ( ! empty( $data[1] ) ) {
			Ga_Helper::update_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, $data[1] );
		}

		return wp_json_encode( $data );
	}

	/**
	 * Registers plugin's settings.
	 */
	public static function admin_init_googleanalytics() {
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		register_setting( GA_NAME, self::GA_EXCLUDE_ROLES_OPTION_NAME );
		register_setting( GA_NAME, self::GA_SELECTED_ACCOUNT );
		register_setting( GA_NAME, self::GA_OAUTH_AUTH_CODE_OPTION_NAME );
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );
		register_setting( GA_NAME, self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
		add_filter( 'pre_update_option_' . Ga_Admin::GA_EXCLUDE_ROLES_OPTION_NAME, 'Ga_Admin::preupdate_exclude_roles', 1, 2 );
		add_filter( 'pre_update_option_' . Ga_Admin::GA_SELECTED_ACCOUNT, 'Ga_Admin::preupdate_selected_account', 1, 2 );
	}

	public static function admin_menu_googleanalytics() {
		if ( current_user_can( 'manage_options' ) ) {
			add_menu_page( 'Google Analytics', 'Google Analytics', 'manage_options', 'googleanalytics', 'Ga_Admin::statistics_page_googleanalytics', 'dashicons-chart-line', 1000 );
			add_submenu_page( 'googleanalytics', 'Google Analytics', __( 'Dashboard' ), 'manage_options', 'googleanalytics', 'Ga_Admin::statistics_page_googleanalytics' );
			add_submenu_page( 'googleanalytics', 'Google Analytics', __( 'Settings' ), 'manage_options', 'googleanalytics/settings', 'Ga_Admin::options_page_googleanalytics' );
		}
	}

	public static function update_terms() {
		if ( !empty( $_GET['accept-terms'] ) && ( 'Y' === $_GET['accept-terms'] ) ) {
			update_option( self::GA_SHARETHIS_TERMS_OPTION_NAME, true );
		}
	}
	/**
	 * Prepares and displays plugin's stats page.
	 */
	public static function statistics_page_googleanalytics() {

		if ( ! Ga_Helper::is_wp_version_valid() ) {
			return false;
		}
		self::update_terms();
		$data = self::get_stats_page();
		Ga_View::load( 'statistics', array(
			'data' => $data
		) );
	}

	/**
	 * Prepares and displays plugin's settings page.
	 */
	public static function options_page_googleanalytics() {

		if ( ! Ga_Helper::is_wp_version_valid() ) {
			return false;
		}
		self::update_terms();

		/**
		 * Keeps data to be extracted as variables in the view.
		 *
		 * @var array $data
		 */
		$data = array();

		$data[ self::GA_WEB_PROPERTY_ID_OPTION_NAME ]                = get_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME );
		$data[ self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME ] = get_option( self::GA_WEB_PROPERTY_ID_MANUALLY_VALUE_OPTION_NAME );
		$data[ self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME ]       = get_option( self::GA_WEB_PROPERTY_ID_MANUALLY_OPTION_NAME );

		$roles = Ga_Helper::get_user_roles();
		$saved = json_decode( get_option( self::GA_EXCLUDE_ROLES_OPTION_NAME ), true );

		$tmp = array();
		if ( ! empty( $roles ) ) {
			foreach ( $roles as $role ) {
				$role_id = Ga_Helper::prepare_role_id( $role );
				$tmp[]   = array(
					'name'    => $role,
					'id'      => $role_id,
					'checked' => ( ! empty( $saved[ $role_id ] ) && $saved[ $role_id ] === 'on' )
				);
			}
		}
		$data['roles'] = $tmp;

		if ( Ga_Helper::is_authorized() ) {
			$data['ga_accounts_selector'] = self::get_accounts_selector();
		} else {
			$data['popup_url'] = self::get_auth_popup_url();
		}

		Ga_View::load( 'page', array(
			'data' => $data
		) );
	}

	/**
	 * Prepares and returns a plugin's URL to be opened in a popup window
	 * during Google authentication process.
	 *
	 * @return mixed
	 */
	public static function get_auth_popup_url() {
		return admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL . '&ga_action=ga_auth' );
	}

	/**
	 * Prepares and returns Google Account's dropdown code.
	 *
	 * @return string
	 */
	public static function get_accounts_selector() {
		$selected = Ga_Helper::get_selected_account_data();

		return Ga_View::load( 'ga_accounts_selector', array(
			'selector'             => json_decode( get_option( self::GA_ACCOUNT_DATA_OPTION_NAME ), true ),
			'selected'             => $selected ? implode( "_", $selected ) : null,
			'add_manually_enabled' => Ga_Helper::is_code_manually_enabled()
		), true );
	}

	/**
	 * Adds Bootstrap scripts.
	 */
	public static function enqueue_bootstrap() {
		wp_register_script( GA_NAME . '-bootstrap-js', GA_PLUGIN_URL . '/js/bootstrap.min.js', array(
			'jquery'
		) );
		wp_register_style( GA_NAME . '-bootstrap-css', GA_PLUGIN_URL . '/css/bootstrap.min.css', false, null, 'all' );
		wp_enqueue_script( GA_NAME . '-bootstrap-js' );
		wp_enqueue_style( GA_NAME . '-bootstrap-css' );
	}

	/**
	 * Adds JS scripts for the settings page.
	 */
	public static function enqueue_ga_scripts() {
		wp_register_script( GA_NAME . '-page-js', GA_PLUGIN_URL . '/js/' . GA_NAME . '_page.js', array(
			'jquery'
		) );
		wp_enqueue_script( GA_NAME . '-page-js' );
	}

	/**
	 * Adds CSS plugin's scripts.
	 */
	public static function enqueue_ga_css() {
		wp_register_style( GA_NAME . '-css', GA_PLUGIN_URL . '/css/' . GA_NAME . '.css', false, null, 'all' );
		wp_enqueue_style( GA_NAME . '-css' );
	}

	/**
	 * Enqueues dashboard JS scripts.
	 */
	private static function enqueue_dashboard_scripts() {
		wp_register_script( GA_NAME . '-dashboard-js', GA_PLUGIN_URL . '/js/' . GA_NAME . '_dashboard.js', array(
			'jquery'
		) );
		wp_enqueue_script( GA_NAME . '-dashboard-js' );
	}

	/**
	 * Enqueues plugin's JS and CSS scripts.
	 */
	public static function enqueue_scripts() {
		if ( Ga_Helper::is_dashboard_page() || Ga_Helper::is_plugin_page() ) {
			wp_register_script( GA_NAME . '-js', GA_PLUGIN_URL . '/js/' . GA_NAME . '.js', array(
				'jquery'
			) );
			wp_enqueue_script( GA_NAME . '-js' );

			wp_register_script( 'googlecharts', 'https://www.gstatic.com/charts/loader.js', null, null, false );
			wp_enqueue_script( 'googlecharts' );

			self::enqueue_ga_css();
		}

		if ( Ga_Helper::is_dashboard_page() ) {
			self::enqueue_dashboard_scripts();
		}

		if ( Ga_Helper::is_plugin_page() ) {
			self::enqueue_bootstrap();
			self::enqueue_ga_scripts();
		}
	}

	/**
	 * Prepares plugin's statistics page and return HTML code.
	 *
	 * @return string HTML code
	 */
	public static function get_stats_page() {
		$selected = Ga_Helper::get_selected_account_data( true );

		$query_params = Ga_Stats::get_query( 'main_chart', $selected['view_id'] );
		$stats_data   = self::api_client()->call( 'ga_api_data', array(

			$query_params
		) );

		$boxes_data   = self::api_client()->call( 'ga_api_data', array(

			Ga_Stats::get_query( 'boxes', $selected['view_id'] )
		) );
		$sources_data = self::api_client()->call( 'ga_api_data', array(

			Ga_Stats::get_query( 'sources', $selected['view_id'] )
		) );
		$chart        = ! empty( $stats_data ) ? Ga_Stats::get_chart( $stats_data->getData() ) : array();
		$boxes        = ! empty( $boxes_data ) ? Ga_Stats::get_boxes( $boxes_data->getData() ) : array();
		$last_chart_date = ! empty( $chart ) ? $chart['date'] : strtotime( 'now' );
		unset( $chart['date'] );
		$labels  = array(
			'thisWeek' => date( 'M d, Y', strtotime( '-6 day', $last_chart_date ) ) . ' - ' . date( 'M d, Y', $last_chart_date ),
			'lastWeek' => date( 'M d, Y', strtotime( '-13 day', $last_chart_date ) ) . ' - ' . date( 'M d, Y', strtotime( '-7 day', $last_chart_date ) )
		);
		$sources = ! empty( $sources_data ) ? Ga_Stats::get_sources( $sources_data->getData() ) : array();

		return Ga_Helper::get_chart_page( 'stats', array(

			'chart'   => $chart,
			'boxes'   => $boxes,
			'labels'  => $labels,
			'sources' => $sources
		) );
	}

	/**
	 * Shows plugin's notice on the admin area.
	 */
	public static function admin_notice_googleanalytics() {

		if ( ( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && Ga_Helper::is_plugin_page() ) || ( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && ! get_option( self::GA_HIDE_TERMS_OPTION_NAME ) ) ) {
			if ( !empty( $_GET['accept-terms'] ) && ( 'Y' === $_GET['accept-terms'] ) ) {
				return;
			}
			$url = home_url( $_SERVER['REQUEST_URI'] . '&accept-terms=Y' );
			Ga_View::load( 'ga_notice', array(

				'url' => $url
			) );
		}

		if ( ! Ga_Helper::is_wp_version_valid() ) {
			Ga_View::load( 'ga_oauth_notice', array(
				'msg' => _( 'Google Analytics plugin requires at least WordPress version ' . self::MIN_WP_VERSION )
			) );
		}
	}

	/**
	 * Hides plugin's notice
	 */
	public static function admin_notice_hide_googleanalytics() {
		update_option( self::GA_HIDE_TERMS_OPTION_NAME, true );
	}

	/**
	 * Adds GA dashboard widget only for administrators.
	 */
	public static function add_dashboard_device_widget() {
		if ( Ga_Helper::is_administrator() ) {
			wp_add_dashboard_widget( 'ga_dashboard_widget', __( 'Google Analytics Dashboard' ), 'Ga_Helper::add_ga_dashboard_widget' );
		}
	}

	/**
	 * Adds plugin's actions
	 */
	public static function add_actions() {
		add_action( 'admin_init', 'Ga_Admin::admin_init_googleanalytics' );
		add_action( 'admin_menu', 'Ga_Admin::admin_menu_googleanalytics' );
		add_action( 'admin_enqueue_scripts', 'Ga_Admin::enqueue_scripts' );
		add_action( 'wp_dashboard_setup', 'Ga_Admin::add_dashboard_device_widget' );
		add_action( 'wp_ajax_ga_ajax_data_change', 'Ga_Admin::ga_ajax_data_change' );
		add_action( 'admin_notices', 'Ga_Admin::admin_notice_googleanalytics' );

		if ( ! get_option( self::GA_SHARETHIS_TERMS_OPTION_NAME ) && ! get_option( self::GA_HIDE_TERMS_OPTION_NAME ) ) {
			add_action( 'wp_ajax_googleanalytics_hide_terms', 'Ga_Admin::admin_notice_hide_googleanalytics' );
		}
	}

	/**
	 * Adds plugin's filters
	 */
	public static function add_filters() {
		add_filter( 'plugin_action_links', 'Ga_Admin::ga_action_links', 10, 5 );
	}

	/**
	 * Adds new action links on the plugin list.
	 *
	 * @param $actions
	 * @param $plugin_file
	 *
	 * @return mixed
	 */
	public static function ga_action_links( $actions, $plugin_file ) {

		if ( basename( $plugin_file ) == GA_NAME . '.php' ) {
			array_unshift( $actions, '<a href="' . esc_url( get_admin_url( null, Ga_Helper::GA_SETTINGS_PAGE_URL ) ) . '">' . _( 'Settings' ) . '</a>' );
		}

		return $actions;
	}

	public static function init_oauth() {
		// $code = ! empty( $_GET['code'] ) ? $_GET['code'] : null;
		$code = Ga_Helper::get_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME );

		if ( ! Ga_Helper::is_authorized() && $code ) {
			Ga_Helper::update_option( self::GA_OAUTH_AUTH_CODE_OPTION_NAME, "" );

			// Get access token
			$response = self::api_client()->call( 'ga_auth_get_access_token', $code );

			self::save_access_token( $response );
			self::api_client()->set_access_token( $response->getData() ); // sprawdzic

			// Get accounts data
			$account_summaries = self::api_client()->call( 'ga_api_account_summaries' );
			self::save_ga_account_summaries( $account_summaries->getData() );

			wp_redirect( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ) );
		}
	}

	public static function handle_actions() {
		$action = ! empty( $_GET['ga_action'] ) ? $_GET['ga_action'] : null;

		if ( $action ) {
			$class = __CLASS__;
			if ( is_callable( array(

				$class,
				$action
			) ) ) {
				$class::$action();
			}
		}
	}

	public static function ga_auth() {
		if ( Ga_Helper::are_terms_accepted() ) {
			header( 'Location:' . self::api_client()->create_auth_url() );
		} else {
			wp_die( Ga_Helper::ga_oauth_notice( __( 'Please accept the terms to use this feature' ) ) );
		}
	}

	/**
	 * Save access token.
	 *
	 * @param Ga_Lib_Api_Response $response
	 *
	 * @return boolean
	 */
	public static function save_access_token( $response ) {
		$access_token            = $response->getData();
		$access_token['created'] = time();

		return update_option( self::GA_OAUTH_AUTH_TOKEN_OPTION_NAME, wp_json_encode( $access_token ) );
	}

	/**
	 * Saves Google Analytics account data.
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public static function save_ga_account_summaries( $data ) {
		$return = array();
		if ( ! empty( $data['items'] ) ) {
			foreach ( $data['items'] as $item ) {
				$tmp         = array();
				$tmp['id']   = $item['id'];
				$tmp['name'] = $item['name'];
				if ( is_array( $item['webProperties'] ) ) {
					foreach ( $item['webProperties'] as $property ) {
						$profiles = array();
						if ( is_array( $property['profiles'] ) ) {
							foreach ( $property['profiles'] as $profile ) {
								$profiles[] = array(

									'id'   => $profile['id'],
									'name' => $profile['name']
								);
							}
						}

						$tmp['webProperties'][] = array(

							'webPropertyId' => $property['id'],
							'name'          => $property['name'],
							'profiles'      => $profiles
						);
					}
				}

				$return[] = $tmp;
			}

			update_option( self::GA_ACCOUNT_DATA_OPTION_NAME, wp_json_encode( $return ) );
			update_option( self::GA_WEB_PROPERTY_ID_OPTION_NAME, "" );
		}

		return $return;
	}

	public static function ga_ajax_data_change() {
		$date_range = ! empty( $_POST['date_range'] ) ? $_POST['date_range'] : null;
		$metric     = ! empty( $_POST['metric'] ) ? $_POST['metric'] : null;
		echo Ga_Helper::get_ga_dashboard_widget_data_json( $date_range, $metric, false, true );
		wp_die();
	}
}
