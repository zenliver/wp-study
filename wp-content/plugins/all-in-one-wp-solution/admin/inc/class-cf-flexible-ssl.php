<?php


class AIOWS_Cloudflare_Flexible_SSL {

	public function __construct() {}

	public function run() {

		$aiows_HttpsServerOpts = array( 'HTTP_CF_VISITOR', 'HTTP_X_FORWARDED_PROTO' );
		foreach( $aiows_HttpsServerOpts as $sOption ) {

			if ( isset( $_SERVER[ $sOption ] ) && ( strpos( $_SERVER[ $sOption ], 'https' ) !== false ) ) {
				$_SERVER[ 'HTTPS' ] = 'on';
				break;
			}
		}

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'aiows_maintainPluginLoadPosition') );
		}
	}

	/**
	 * Sets this plugin to be the first loaded of all the plugins.
	 */
	public function aiows_maintainPluginLoadPosition() {
		$sBaseFile = plugin_basename( __FILE__ );
		$nLoadPosition = $this->aiows_getActivePluginLoadPosition( $sBaseFile );
		if ( $nLoadPosition > 1 ) {
			$this->aiows_setActivePluginLoadPosition( $sBaseFile, 0 );
		}
	}

	/**
	 * @param string $sPluginFile
	 * @return int
	 */
	public function aiows_getActivePluginLoadPosition( $sPluginFile ) {
		$sOptionKey = is_multisite() ? 'active_sitewide_plugins' : 'active_plugins';
		$aActive = get_option( $sOptionKey );
		$nPosition = -1;
		if ( is_array( $aActive ) ) {
			$nPosition = array_search( $sPluginFile, $aActive );
			if ( $nPosition === false ) {
				$nPosition = -1;
			}
		}
		return $nPosition;
	}

	/**
	 * @param string $sPluginFile
	 * @param int $nDesiredPosition
	 */
	public function aiows_setActivePluginLoadPosition( $sPluginFile, $nDesiredPosition = 0 ) {

		$aActive = $this->aiows_setArrayValueToPosition( get_option( 'active_plugins' ), $sPluginFile, $nDesiredPosition );
		update_option( 'active_plugins', $aActive );

		if ( is_multisite() ) {
			$aActive = $this->aiows_setArrayValueToPosition( get_option( 'active_sitewide_plugins' ), $sPluginFile, $nDesiredPosition );
			update_option( 'active_sitewide_plugins', $aActive );
		}
	}

	/**
	 * @param array $aSubjectArray
	 * @param mixed $mValue
	 * @param int $nDesiredPosition
	 * @return array
	 */
	public function aiows_setArrayValueToPosition( $aSubjectArray, $mValue, $nDesiredPosition ) {

		if ( $nDesiredPosition < 0 || !is_array( $aSubjectArray ) ) {
			return $aSubjectArray;
		}

		$nMaxPossiblePosition = count( $aSubjectArray ) - 1;
		if ( $nDesiredPosition > $nMaxPossiblePosition ) {
			$nDesiredPosition = $nMaxPossiblePosition;
		}

		$nPosition = array_search( $mValue, $aSubjectArray );
		if ( $nPosition !== false && $nPosition != $nDesiredPosition ) {

			// remove existing and reset index
			unset( $aSubjectArray[ $nPosition ] );
			$aSubjectArray = array_values( $aSubjectArray );

			// insert and update
			// http://stackoverflow.com/questions/3797239/insert-new-item-in-array-on-any-position-in-php
			array_splice( $aSubjectArray, $nDesiredPosition, 0, $mValue );
		}

		return $aSubjectArray;
	}
}

$aiows_CfFlexibleSslCheck = new AIOWS_Cloudflare_Flexible_SSL();
$aiows_CfFlexibleSslCheck->run();