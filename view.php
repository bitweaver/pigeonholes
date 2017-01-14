<?php
/**
 * $Header$
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id$
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'p_pigeonholes_view' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

/* If we came in via structure_id redirect to content_id */
if (isset($_REQUEST['structure_id'])) {
	header("Location:".$gContent->getDisplayUrl());
}

$gBitSmarty->assignByRef( 'memberFeedback', $memberFeedback = array() );

// set up structure related stuff
global $gStructure;
if( !@BitBase::verifyId( $gContent->mInfo['root_structure_id'] ) ) {
	header( "Location:".PIGEONHOLES_PKG_URL."list.php" );
}

$gStructure = new LibertyStructure( $gContent->mInfo['root_structure_id'] );
$gStructure->load();

// expunge request
if( !empty( $_REQUEST['action'] ) ) {
	if( $_REQUEST['action'] == 'dismember' && !empty( $_REQUEST['content_id'] ) && !empty( $_REQUEST['parent_id'] ) && $gContent->verifyUpdatePermission() ) {
		if( $gContent->expungePigeonholeMember( array( 'parent_id' => $_REQUEST['content_id'], 'member_id' => $_REQUEST['parent_id'] ) ) ) {
			$feedback['success'] = tra( 'The item was successfully removed' );
		} else {
			$feedback['error'] = tra( 'The item could not be removed' );
		}
	}
}

// confirm that structure is valid
if( empty( $gStructure ) || !$gStructure->isValid() ) {
	$gBitSystem->fatalError( tra( 'Invalid structure' ));
}

$gBitSmarty->assignByRef( 'gStructure', $gStructure );
$gBitSmarty->assign( 'structureInfo', $gStructure->mInfo );
$gBitSmarty->assign( 'subtree', $gStructure->getSubTree( $gStructure->mStructureId ) );

if( $gContent->checkPathPermissions( $gContent->getField( 'path' ) ) ) {
	$listHash = array(
		'root_structure_id'   => $gContent->mInfo['root_structure_id'],
		'structure_id'        => $gContent->mInfo['structure_id'],
		'parse_data'          => TRUE,
		'max_records'         => -1,
		'load_extras'         => TRUE,
		'members_max_records' => -1,
	);
	$pigeonList = $gContent->getList( $listHash );
	$gBitSmarty->assign( 'pigeonList', $pigeonList );
} else {
	$memberFeedback['warning'] = tra( "You do not have the required permissions to view the content of this category" );
}

$gContent->addHit();
// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/view_structure.tpl', tra( 'View Pigeonhole' ) , array( 'display_mode' => 'display' ));
?>
