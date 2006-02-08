<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/view.php,v 1.9 2006/02/08 14:05:53 lsces Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: view.php,v 1.9 2006/02/08 14:05:53 lsces Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_view_pigeonholes' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$gBitSmarty->assign_by_ref( 'memberFeedback', $memberFeedback = array() );

// set up structure related stuff
global $gStructure;
$gStructure = new LibertyStructure( $gContent->mInfo['root_structure_id'] );
$gStructure->load();

if( !empty( $_REQUEST['action'] ) ) {
	if( $_REQUEST['action'] == 'dismember' && !empty( $_REQUEST['content_id'] ) && !empty( $_REQUEST['parent_id'] ) && $gBitUser->hasPermission( 'bit_p_edit_pigeonholes' ) ) {
		if( $gContent->expungePigeonholeMember( array( 'parent_id' => $_REQUEST['content_id'], 'member_id' => $_REQUEST['parent_id'] ) ) ) {
			$feedback['success'] = tra( 'The item was successfully removed' );
		} else {
			$feedback['error'] = tra( 'The item could not be removed' );
		}
	}
}

// confirm that structure is valid
if( empty( $gStructure ) || !$gStructure->isValid() ) {
	$gBitSystem->fatalError( 'Invalid structure' );
}

$gBitSmarty->assign_by_ref( 'gStructure', $gStructure );
$gBitSmarty->assign( 'structureInfo', $gStructure->mInfo );
/*
$subtree = $gStructure->getSubTree( $gStructure->mStructureId );
// get individual node preferences
foreach( $subtree as $k => $node ) {
	$subtree[$k]['preferences'] = $gContent->loadPreferences( $node['content_id'] );
	if( !empty( $subtree[$k]['preferences']['permission'] ) ) {
		$subtree[$k]['permissions'][] = $subtree[$k]['preferences']['permission'];
	}
	if( !empty( $subtree[$k]['preferences']['group_id'] ) ) {
		$subtree[$k]['groups'][] = $subtree[$k]['preferences']['group_id'];
	}
}

// this is a bit of a crazy setup to pass permissions on to child nodes, but i'm not sure how else to do this.
for( $i = 0; $i <= count( $subtree ); $i++ ) {
	foreach( $subtree as $key => $node ) {
		if( $node['level'] == $i ) {
			foreach( $subtree as $k => $n ) {
				if( $n['level'] == $i-1 ) {
					if( !empty( $n['preferences']['permission'] ) ) {
						$subtree[$key]['permissions'][] = $n['preferences']['permission'];
					}
					if( !empty( $n['preferences']['group_id'] ) ) {
						$subtree[$key]['groups'][] = $n['preferences']['group_id'];
					}
				}
			}
		}
	}
}
$gBitSmarty->assign( 'subtree', $subtree );
*/

$gBitSmarty->assign( 'subtree', $gStructure->getSubTree( $gStructure->mStructureId ) );
$listHash = array(
	'root_structure_id' => $gContent->mInfo['root_structure_id'],
	'structure_id' => $gContent->mInfo['structure_id'],
	'load_extras' => TRUE
);
$cpath = $gContent->getField( 'path' );
if( $gContent->checkPathPermissions( $cpath ) ) {
	$pigeonList = $gContent->getList( $listHash );
	$gBitSmarty->assign( 'pigeonList', $pigeonList );
} else {
	$memberFeedback['warning'] = tra( "You do not have the required permissions to view the content of this category" );
}

$gContent->addHit();
// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/view_structure.tpl', tra( 'View Pigeonhole' ) );
?>
