<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.18 $
 * @package  Pigeonholes
 * @subpackage functions
 */
global $gBitSystem, $gBitUser, $gLibertySystem;

$registerHash = array(
	'package_name' => 'pigeonholes',
	'package_path' => dirname( __FILE__ ).'/',
	'service' => LIBERTY_SERVICE_CATEGORIZATION,
);
$gBitSystem->registerPackage( $registerHash );

define( 'PIGEONHOLES_CONTENT_TYPE_GUID', 'pigeonholes' );

if( $gBitSystem->isPackageActive( 'pigeonholes' )) {
	// include service functions
	require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );

	$tpl = $gBitSystem->isFeatureActive( 'pigeonholes_use_jstab' ) ? 'tab' : 'mini';
	$gLibertySystem->registerService( LIBERTY_SERVICE_CATEGORIZATION, PIGEONHOLES_PKG_NAME, array(
		// functions
		'content_display_function'  => 'pigeonholes_content_display',
		'content_preview_function'  => 'pigeonholes_content_preview',
		'content_edit_function'     => 'pigeonholes_content_edit',
		'content_store_function'    => 'pigeonholes_content_store',
		'content_expunge_function'  => 'pigeonholes_content_expunge',
		'content_list_function'     => 'pigeonholes_content_list',
		'content_list_sql_function' => 'pigeonholes_content_list_sql',

		// templates
		'content_edit_'.$tpl.'_tpl' => 'bitpackage:pigeonholes/service_edit_'.$tpl.'_inc.tpl',
		'content_view_tpl'          => 'bitpackage:pigeonholes/service_view_members_inc.tpl',
		'content_nav_tpl'           => 'bitpackage:pigeonholes/service_nav_path_inc.tpl',
		'content_list_options_tpl'  => 'bitpackage:pigeonholes/service_list_options_inc.tpl',
	));

	if( $gBitUser->hasPermission( 'p_pigeonholes_view' )) {
		$menuHash = array(
			'package_name'  => PIGEONHOLES_PKG_NAME,
			'index_url'     => PIGEONHOLES_PKG_URL.'index.php',
			'menu_template' => 'bitpackage:pigeonholes/menu_pigeonholes.tpl',
		);
		$gBitSystem->registerAppMenu( $menuHash );
	}
}
?>
