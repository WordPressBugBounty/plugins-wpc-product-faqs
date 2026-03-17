<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCPF_LITE' ) ? WPCPF_LITE : WPCPF_FILE, 'wpcpf_activate' );
register_deactivation_hook( defined( 'WPCPF_LITE' ) ? WPCPF_LITE : WPCPF_FILE, 'wpcpf_deactivate' );
add_action( 'admin_init', 'wpcpf_check_version' );

function wpcpf_check_version() {
	if ( ! empty( get_option( 'wpcpf_version' ) ) && ( get_option( 'wpcpf_version' ) < WPCPF_VERSION ) ) {
		wpc_log( 'wpcpf', 'upgraded' );
		update_option( 'wpcpf_version', WPCPF_VERSION, false );
	}
}

function wpcpf_activate() {
	wpc_log( 'wpcpf', 'installed' );
	update_option( 'wpcpf_version', WPCPF_VERSION, false );
}

function wpcpf_deactivate() {
	wpc_log( 'wpcpf', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}