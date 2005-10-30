<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/Attic/mod_last_changes.php,v 1.3 2005/10/30 21:03:50 lsces Exp $
 * @package liberty
 * @subpackage modules
 * Params:
 * - content_type_guid : if set, show only those content_type_guid's
 * - show_date : if set, show date of last modification
 */

/**
 * Initialization
 */
global $gBitUser, $module_rows, $module_params, $gLibertySystem, $module_title;

if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
	require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
	$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );

	$listHash = array(
		'title' => !empty( $module_params['category'] ) ? $module_params['category'] : NULL,
		'content_id' => !empty( $module_params['category_id'] ) ? $module_params['category_id'] : NULL,
		'content_type_guid' => !empty( $module_params['content_type_guid'] ) ? $module_params['content_type_guid'] : NULL,
		'max_records' => $module_rows,
		'sort_mode' => 'last_modified_desc',
	);
	$pigeonLastMod = $pigeonholes->getMemberList( $listHash );
	$gBitSmarty->assign( 'pigeonLastMod', !empty( $pigeonLastMod ) ? $pigeonLastMod : FALSE );
}

if( empty( $module_title ) ) {
	if( !empty( $module_params['content_type_guid'] ) && !empty( $gLibertySystem->mContentTypes[$module_params['content_type_guid']] ) ) {
		$title = tra( "Last Changes" ).': '.tra( $gLibertySystem->mContentTypes[$module_params['content_type_guid']]['content_description'] );
		$gBitSmarty->assign( 'contentType', $module_params['content_type_guid'] );
	} else {
		if( !empty( $module_params['hide_content_type'] ) ) {
			$gBitSmarty->assign( 'contentType', TRUE );
		} else {
			$gBitSmarty->assign( 'contentType', FALSE );
		}

		$title = tra( "Last Changes" );
	}
	$gBitSmarty->assign( 'moduleTitle', $title );
}

if( !empty( $module_params['show_date'] ) ) {
	$gBitSmarty->assign( 'showDate' , TRUE );
}
?>
