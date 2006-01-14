<?php
/**
 * $Header
 *
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.4 $
 * @package  pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once("../bit_setup_inc.php");

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_view_pigeonholes' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

// some specific offsets and pagination settings
if( !empty( $_REQUEST['sort_mode'] ) ) {
	$gBitSmarty->assign( 'sort_mode', $_REQUEST['sort_mode'] );
}

$gBitSmarty->assign( 'curPage', $page = !empty( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1 );
$listHash = array(
	'sort_mode' => !empty( $_REQUEST['sort_mode'] ) ? $_REQUEST['sort_mode'] : 'title_asc',
	'max_rows' => $gBitSystem->mPrefs['maxRecords'],
	'offset' => ( $page - 1 ) * $gBitSystem->mPrefs['maxRecords'],
	'find' => !empty( $_REQUEST['find'] ) ? $_REQUEST['find'] : NULL,
	'load_only_root' => TRUE,
);

$pigeonList = $gPigeonholes->getList( $listHash );

// set up structure related stuff
if( !empty( $pigeonList['data'] ) ) {
	foreach( $pigeonList['data'] as $key => $pigeonhole ) {
		$gStructure = new LibertyStructure( $pigeonhole['root_structure_id'] );
		$gStructure->load();
		$pigeonList['data'][$key]['subtree'] = $gStructure->getSubTree( $gStructure->mStructureId );
	}
	$gBitSmarty->assign( 'pigeonList', $pigeonList['data'] );
}

//$gBitSmarty->assign_by_ref('offset', $offset);
$gBitSmarty->assign( 'pigeonCount', $pigeonList['cant'] );
$gBitSmarty->assign( 'numPages', ceil( $pigeonList['cant'] / $gBitSystem->mPrefs['maxRecords'] ) );

$gBitSystem->display( 'bitpackage:pigeonholes/list.tpl', tra( 'List Pigeonholes' ) );
?>
