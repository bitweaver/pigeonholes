<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.13 $
 * @package  Pigeonholes
 * @subpackage functions
 */
global $gBitSystem, $gBitUser;

$registerHash = array(
	'package_name' => 'pigeonholes',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => LIBERTY_SERVICE_CATEGORIZATION,
);
$gBitSystem->registerPackage( $registerHash );

define( 'PIGEONHOLES_CONTENT_TYPE_GUID', 'pigeonholes' );

if( $gBitSystem->isPackageActive( 'pigeonholes' ) ) {
	// include service functions
	require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );

	$gLibertySystem->registerService( LIBERTY_SERVICE_CATEGORIZATION, PIGEONHOLES_PKG_NAME, array(
		'content_display_function' => 'pigeonholes_content_display',
		'content_preview_function' => 'pigeonholes_content_preview',
		'content_edit_function' => 'pigeonholes_content_edit',
		'content_store_function' => 'pigeonholes_content_store',
		'content_expunge_function' => 'pigeonholes_content_expunge',
		'content_edit_'.( $gBitSystem->isFeatureActive( 'pigeonholes_use_jstab' ) ? 'tab_' : 'mini_' ).'tpl' => 'bitpackage:pigeonholes/pigeonholes_input_'.( $gBitSystem->isFeatureActive( 'pigeonholes_use_jstab' ) ? '' : 'mini_' ).'inc.tpl',
		'content_view_tpl' => 'bitpackage:pigeonholes/display_members.tpl',
		'content_nav_tpl' => 'bitpackage:pigeonholes/display_paths.tpl',
		'content_list_sql_function' => 'pigeonholes_content_list_sql',
	) );

	if( $gBitUser->hasPermission( 'p_pigeonholes_view' ) ) {
		$gBitSystem->registerAppMenu( PIGEONHOLES_PKG_DIR, 'Categories', PIGEONHOLES_PKG_URL.'index.php', 'bitpackage:pigeonholes/menu_pigeonholes.tpl', 'Pigeonholes' );
	}
}
?>
