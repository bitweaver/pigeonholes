<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/assign_content.php,v 1.1 2006/01/25 19:19:35 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: assign_content.php,v 1.1 2006/01/25 19:19:35 squareing Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_insert_pigeonhole_member' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$feedback = '';
$gBitSmarty->assign_by_ref( 'feedback', $feedback );

$contentTypes = array( '' => tra( 'All Content' ) );
foreach( $gLibertySystem->mContentTypes as $cType ) {
	$contentTypes[$cType['content_type_guid']] = $cType['content_description'];
}
$gBitSmarty->assign( 'contentTypes', $contentTypes );
$gBitSmarty->assign( 'contentSelect', $contentSelect = !isset( $_REQUEST['content_type'] ) ? NULL : $_REQUEST['content_type'] );

$listHash = array(
	'find' => ( empty( $_REQUEST['find_objects'] ) ? NULL : $_REQUEST['find_objects'] ),
	'sort_mode' => ( empty( $_REQUEST['sort_mode'] ) ? NULL : $_REQUEST['sort_mode'] ),
	'max_records' => ( @BitBase::verifyId( $_REQUEST['max_records'] ) ) ? $_REQUEST['max_records'] : 10,
	'include_members' => ( ( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) ? TRUE : FALSE ),
	'content_type' => $contentSelect,
);
$assignableContent = $gContent->getAssignableContent( $listHash );

if( !empty( $_REQUEST['insert_content'] ) && isset( $_REQUEST['pigeonhole'] ) ) {
	// here we need to limit all killing to the selected structure
	$deletableParentIds = array();
	if( empty( $gStructure ) && @BitBase::verifyId( $_REQUEST['root_structure_id'] ) ) {
		$gStructure = new LibertyStructure();
		$struct = $gStructure->getStructure( $_REQUEST['root_structure_id'] );
		foreach( $struct as $node ) {
			$deletableParentIds[] = $node['content_id'];
		}
	}

	// make an array that can be stored
	foreach( $assignableContent as $item ) {
		if( !empty( $_REQUEST['pigeonhole'][$item['content_id']] ) ) {
			foreach( $_REQUEST['pigeonhole'][$item['content_id']] as $parent_id ) {
				$memberHash[$parent_id][] = array(
					'parent_id' => $parent_id,
					'content_id' => $item['content_id'],
				);
			}
		}

		if( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) {
			if( !empty( $item['content_id'] ) && !$gContent->expungePigeonholeMember( array( 'member_id' => $item['content_id'], 'deletables' => $deletableParentIds ) ) ) {
				$feedback['error'] = 'The content could not be deleted before insertion.';
			}
		}
	}

	if( empty( $feedback['error'] ) ) {
		foreach( $memberHash as $memberStore ) {
			if( $gContent->insertPigeonholeMember( $memberStore ) ) {
				$feedback['success'] = 'The content was successfully inserted into the respective categories.';
			} else {
				$feedback['error'] = 'The content could not be inserted into the categories.';
			}
		}
	}

	// we need to reload the assignableContent, since settings have changed
	// reuse previous listhash since display settings aren't changed
	$assignableContent = $gContent->getAssignableContent( $listHash );
}

$listHash = array(
	'load_only_root' => TRUE,
	'max_records' => -1,
);
$pigeonRootData = $gContent->getList( $listHash );
$pigeonRoots[0] = 'All';
foreach( $pigeonRootData as $root ) {
	$pigeonRoots[$root['root_structure_id']] = $root['title'];
}
$gBitSmarty->assign( 'pigeonRoots', !empty( $pigeonRoots ) ? $pigeonRoots : NULL );

$listHash = array(
	'root_structure_id' => ( !empty( $_REQUEST['root_structure_id'] ) ? $_REQUEST['root_structure_id'] : NULL ),
	'force_extras' => TRUE,
	'max_records' => -1,
);
$pigeonList = $gContent->getList( $listHash );
$gBitSmarty->assign( 'pigeonList', $pigeonList );
$gBitSmarty->assign( 'assignableContent', $assignableContent );
$gBitSmarty->assign( 'contentCount', count( $assignableContent ) );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/assign_content.tpl', tra( 'Assign Content to Categories' ) );
?>
