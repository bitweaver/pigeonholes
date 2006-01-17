<?php
/**
 * $Header
 *
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.5 $
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

$listHash = &$_REQUEST;
$listHash['load_only_root'] = TRUE;
$listHash['sort_mode'] = !empty( $listHash['sort_mode'] ) ? $listHash['sort_mode'] : 'title_asc';
$pigeonList = $gPigeonholes->getList( $listHash );

// set up structure related stuff
if( !empty( $pigeonList ) ) {
	foreach( $pigeonList as $key => $pigeonhole ) {
		$gStructure = new LibertyStructure( $pigeonhole['root_structure_id'] );
		$gStructure->load();
		$pigeonList[$key]['subtree'] = $gStructure->getSubTree( $gStructure->mStructureId );
	}
	$gBitSmarty->assign( 'pigeonList', $pigeonList );
}
$gBitSmarty->assign( 'listInfo', $listHash['control'] );

$gBitSystem->display( 'bitpackage:pigeonholes/list.tpl', tra( 'List Pigeonholes' ) );
?>
