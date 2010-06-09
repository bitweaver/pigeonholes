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
$gBitSystem->verifyPermission( 'p_pigeonholes_insert_member' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$feedback = '';
$gBitSmarty->assign_by_ref( 'feedback', $feedback );

$contentTypes = array( '' => tra( 'All Content' ) );
foreach( $gLibertySystem->mContentTypes as $cType ) {
	if( $cType['content_type_guid'] != PIGEONHOLES_CONTENT_TYPE_GUID ) {
		$contentTypes[$cType['content_type_guid']] = $gLibertySystem->getContentTypeName( $cType['content_type_guid'] );
	}
}
$gBitSmarty->assign( 'contentTypes', $contentTypes );
$gBitSmarty->assign( 'contentSelect', $contentSelect = !isset( $_REQUEST['content_type'] ) ? NULL : $_REQUEST['content_type'] );

$listHash = array(
	'find' => ( empty( $_REQUEST['find'] ) ? NULL : $_REQUEST['find'] ),
	'sort_mode' => ( empty( $_REQUEST['sort_mode'] ) ? NULL : $_REQUEST['sort_mode'] ),
	'max_records' => ( @BitBase::verifyId( $_REQUEST['max_records'] ) ) ? $_REQUEST['max_records'] : 10,
	'include_members' => ( ( !empty( $_REQUEST['include'] ) && $_REQUEST['include'] == 'members' ) ? TRUE : FALSE ),
	'content_type' => $contentSelect,
);

// We need to handle insert and next where we are NOT actually doing an insert
if( !empty( $_REQUEST['insert_content'] ) || !empty( $_REQUEST['insert_content_and_next'] )) {
	$listHash['list_page'] = ( empty( $_REQUEST['list_page'] ) ? 2 : $_REQUEST['list_page'] + 1 );
} else {
	$listHash['list_page'] = ( empty( $_REQUEST['list_page'] ) ? NULL : $_REQUEST['list_page'] );
}

$assignableContent = $gContent->getAssignableContent( $listHash );

if(( !empty( $_REQUEST['insert_content'] ) || !empty( $_REQUEST['insert_content_and_next'] ))) {
	// here we need to limit all killing to the selected structure
	$deletableParentIds = array();
	if( empty( $gStructure ) && @BitBase::verifyId( $_REQUEST['root_structure_id'] ) ) {
		$gStructure = new LibertyStructure();
		$struct = $gStructure->getStructure( $_REQUEST );
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

	if( empty( $feedback['error'] ) && !empty( $memberHash )) {
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
	if( !empty( $_REQUEST['insert_content_and_next'] )) {
		$listHash['offset'] = $listHash['listInfo']['offset'] + $listHash['listInfo']['max_records'];
		unset( $listHash['list_page'] );
		unset( $listHash['listInfo'] );
	}
	$assignableContent = $gContent->getAssignableContent( $listHash );
}

$gBitSmarty->assign_by_ref( 'listInfo', $listHash['listInfo'] );

$listHash = array(
	'load_only_root' => TRUE,
	'max_records' => -1,
	'parse_data' => TRUE,
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
	'sort_mode' => 'ls.`parent_id_asc`',
	'parse_data' => TRUE,
);

if( $gBitSystem->isFeatureActive( 'pigeonholes_allow_forbid_insertion' )) {
	$listHash['insertable'] = TRUE;
}

$pigeonList = $gContent->getList( $listHash );

$gBitSmarty->assign( 'pigeonList', $pigeonList );
$gBitSmarty->assign( 'assignableContent', $assignableContent );
$gBitSmarty->assign( 'contentCount', count( $assignableContent ) );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/assign_content.tpl', tra( 'Assign Content to Categories' ) , array( 'display_mode' => 'display' ));
?>
