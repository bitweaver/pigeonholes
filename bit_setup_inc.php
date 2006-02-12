<?php
/**
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.8 $
 * @package  Pigeonholes
 * @subpackage functions
 */
global $gBitSystem, $gBitUser;
$gBitSystem->registerPackage( 'pigeonholes', dirname( __FILE__).'/', TRUE, LIBERTY_SERVICE_CATEGORIZATION );

define( 'PIGEONHOLES_CONTENT_TYPE_GUID', 'pigeonholes' );

if( $gBitSystem->isPackageActive( 'pigeonholes' ) ) {
	$gLibertySystem->registerService( LIBERTY_SERVICE_CATEGORIZATION, PIGEONHOLES_PKG_NAME, array(
		'content_display_function' => 'pigeonholes_content_display',
		'content_preview_function' => 'pigeonholes_content_preview',
		'content_edit_function' => 'pigeonholes_content_input',
		'content_store_function' => 'pigeonholes_content_store',
		'content_expunge_function' => 'pigeonholes_content_expunge',
		'content_edit_tab_tpl' => 'bitpackage:pigeonholes/pigeonholes_input_inc.tpl',
		'content_view_tpl' => 'bitpackage:pigeonholes/display_members.tpl',
		'content_nav_tpl' => 'bitpackage:pigeonholes/display_paths.tpl',
	) );

	// include service functions
	require_once( PIGEONHOLES_PKG_PATH.'servicefunctions_inc.php' );

	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		$gBitSystem->registerAppMenu( PIGEONHOLES_PKG_DIR, 'Categories', PIGEONHOLES_PKG_URL.'index.php', 'bitpackage:pigeonholes/menu_pigeonholes.tpl', 'Pigeonholes' );
	}
}
?>
