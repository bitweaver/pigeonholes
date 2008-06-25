<?php
/**
 * $Header
 *
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.12 $
 * @package  pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once("../bit_setup_inc.php");

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'p_pigeonholes_view' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$listHash = &$_REQUEST;
$listHash['load_only_root'] = TRUE;
$listHash['sort_mode'] = !empty( $listHash['sort_mode'] ) ? $listHash['sort_mode'] : 'title_asc';
$listHash['parse_data'] = TRUE;
$pigeonList = $gContent->getList( $listHash );

// set up structure related stuff
if( !empty( $pigeonList ) ) {
	foreach( $pigeonList as $key => $pigeonhole ) {
		if( empty( $gStructure ) ) {
			$gStructure = new LibertyStructure();
		}
		$pigeonList[$key]['subtree'] = $gStructure->getSubTree( $pigeonhole['root_structure_id'] );
		// add permissions to all so we know if we can display pages within category
//		foreach( $pigeonList[$key]['subtree'] as $k => $node ) {
//			$pigeonList[$key]['subtree'][$k]['preferences'] = $gContent->loadPreferences( $node['content_id'] );
//		}
	}
	$gBitSmarty->assign( 'pigeonList', $pigeonList );
}
$gBitSmarty->assign( 'listInfo', $listHash['listInfo'] );

$gBitSystem->display( 'bitpackage:pigeonholes/list.tpl', tra( 'List Categories' ) , array( 'display_mode' => 'list' ));
?>
