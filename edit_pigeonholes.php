<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/edit_pigeonholes.php,v 1.16 2006/02/10 21:11:41 lsces Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: edit_pigeonholes.php,v 1.16 2006/02/10 21:11:41 lsces Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_edit_pigeonholes' );

include_once( LIBERTY_PKG_PATH.'LibertyStructure.php' );
include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

// include edit structure file only when structure_id is known
if( !empty( $_REQUEST["structure_id"] ) && ( empty( $_REQUEST['action'] ) || $_REQUEST['action'] != 'remove' ) ) {
	$verifyStructurePermission = 'bit_p_edit_pigeonholes';
	include_once( LIBERTY_PKG_PATH.'edit_structure_inc.php' );

	// get all the nodes in this structure
	foreach( $rootTree as $node ) {
		$pigeonStructure[$node['structure_id']] = str_repeat( '-', $node['level'] ).' '.$node['title'];
	}
	$gBitSmarty->assign( 'pigeonStructure', $pigeonStructure );
}

global $gStructure;
// store the form if we need to
if( !empty( $_REQUEST['pigeonhole_store'] ) ) {
	if( ( empty( $_REQUEST['pigeonhole']['title'] ) ) ) {
		$gBitSmarty->assign( 'msg', tra( "You must specify a title." ) );
		$gBitSystem->display( 'error.tpl' );
		die;
	}

	// we need to get the root structure id
	$_REQUEST['pigeonhole']['root_structure_id'] = !empty( $rootStructure->mStructureId ) ?  $rootStructure->mStructureId : NULL;
	// store the pigeonhole
	$pigeonStore = new Pigeonholes();
	$pigeonStore->mContentId = !empty( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : NULL;
	$pigeonStore->load();
	if( $pigeonStore->store( $_REQUEST['pigeonhole'] ) ) {
		header( "Location: ".$_SERVER['PHP_SELF'].'?structure_id='.$pigeonStore->mStructureId.( !empty( $_REQUEST['action'] ) ? '&action='.$_REQUEST['action'] : '' )."&success=".urlencode( tra( "The category was successfully stored" ) ) );
	} else {
		vd( $gContent->mErrors );
		$gBitSmarty->assign( 'msg', tra( "There was a problem trying to store the pigeonhole." ) );
		$gBitSystem->display( 'error.tpl' );
		die;
	}
}

// if we are just changing the content that is being displayed, we treat it like a preview.
if( !empty( $_REQUEST['search_objects'] ) ) {
	$pigeonInfo['parent_id'] = !empty( $_REQUEST['pigeonhole']['parent_id'] ) ? $_REQUEST['pigeonhole']['parent_id'] : NULL;
	$pigeonInfo['title'] = !empty( $_REQUEST['pigeonhole']['title'] ) ? $_REQUEST['pigeonhole']['title'] : NULL;
	$pigeonInfo['data'] = !empty( $_REQUEST['pigeonhole']['edit'] ) ? $_REQUEST['pigeonhole']['edit'] : NULL;
	$pigeonInfo['selected_members'] = !empty( $_REQUEST['pigeonhole']['members'] ) ? $_REQUEST['pigeonhole']['members'] : NULL;
	$gBitSmarty->assign( 'pigeonInfo', !empty( $pigeonInfo ) ? $pigeonInfo : NULL );
} elseif( !empty( $_REQUEST['action'] ) || isset( $_REQUEST["confirm"] ) ) {
	// if we need to edit, show the information
	if( $_REQUEST['action'] == 'edit' ) {
		$pigeonInfo = $gContent->mInfo;
		$gContent->loadPreferences();

		// create usable array for selected items in content listing
		if( !empty( $pigeonInfo['members'] ) ) {
			foreach( $pigeonInfo['members'] as $member ) {
				if( $pigeonInfo['content_id'] == $member['parent_id'] ) {
					$pigeonInfo['selected_members'][] = $member['content_id'];
				}
			}
		}
	}

	if( $_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'create' ) {
		$gBitSmarty->assign( 'pigeonInfo', !empty( $pigeonInfo ) ? $pigeonInfo : NULL );
	}

	if( $_REQUEST["action"] == 'remove' || isset( $_REQUEST["confirm"] ) ) {
		if( isset( $_REQUEST["confirm"] ) ) {
			if( $gContent->expunge( $_REQUEST["structure_id"] ) ) {
				header( "Location: ".$_SERVER['PHP_SELF'].'?structure_id='.$gContent->mInfo["parent_id"] );
				die;
			} else {
				vd( $gContent->mErrors );
			}
		}
		$gBitSystem->setBrowserTitle( 'Confirm removal of '.$gContent->mInfo['title'] );
		$formHash['remove'] = TRUE;
		$formHash['structure_id'] = $_REQUEST['structure_id'];
		$formHash['action'] = 'remove';
		$msgHash = array(
			'label' => 'Remove Pigeonhole',
			'confirm_item' => $gContent->mInfo['title'].'<br />'.tra( 'and any subcategories' ),
			'warning' => 'This will remove the pigeonhole but will <strong>not</strong> modify or remove the content itself.',
		);
		$gBitSystem->confirmDialog( $formHash, $msgHash );
	}

	if( $_REQUEST['action'] == 'dismember' && !empty( $_REQUEST['content_id'] ) && !empty( $_REQUEST['parent_id'] ) ) {
		if( $gContent->expungePigeonholeMember( array( 'parent_id' => $_REQUEST['content_id'], 'member_id' => $_REQUEST['parent_id'] ) ) ) {
			$feedback['success'] = tra( 'The item was successfully removed' );
		} else {
			$feedback['error'] = tra( 'The item could not be removed' );
		}
		// used to avoid displaying edit form
		unset( $_REQUEST['action'] );
	}
}

if( !empty( $_REQUEST['success'] ) ) {
	$feedback['success'] = $_REQUEST['success'];
}

// get all available perms only when the admin is visiting here.
if ( $gBitSystem->isFeatureActive( 'pigeonholes_permissions' ) ) {
	if( $gBitUser->isAdmin() ) {
		$tmpPerms = $gBitUser->getGroupPermissions();
	} else {
		$tmpPerms = $gBitUser->mPerms;
	}

	$perms[''] = tra( 'None' );
	foreach( $tmpPerms as $perm => $info ) {
		$perms[$info['package']][$perm] = $perm;
	}
	$gBitSmarty->assign( 'perms', $perms );
}

// get available groups ready that we can assign the pigoenhole to one of them
if ( $gBitSystem->isFeatureActive( 'pigeonholes_groups' ) ) {
	$groups[''] = tra( 'None' );
	foreach( $gBitUser->mGroups as $group_id => $group ) {
		$groups[$group_id] = $group['group_name'];
	}
	$gBitSmarty->assign( 'groups', $groups );
}

// get content
include_once( LIBERTY_PKG_PATH.'get_content_list_inc.php' );
foreach( $contentList['data'] as $cItem ) {
	$cList[$contentTypes[$cItem['content_type_guid']]][$cItem['content_id']] = $cItem['title'].' [id: '.$cItem['content_id'].']';
}
$gBitSmarty->assign( 'contentList', $cList );
$gBitSmarty->assign( 'contentSelect', $contentSelect );
$gBitSmarty->assign( 'contentTypes', $contentTypes );
$gBitSmarty->assign( 'contentDescs', $contentDescs );

$listHash['root_structure_id'] = !empty( $gContent->mInfo['root_structure_id'] ) ? $gContent->mInfo['root_structure_id'] : NULL;
$listHash['force_extras'] = TRUE;
$pigeonList = $gContent->getList( $listHash );
$gBitSmarty->assign( 'pigeonList', $pigeonList );
$gBitSmarty->assign( 'feedback', !empty( $feedback ) ? $feedback : NULL );

// Get list of available styles
if ( $gBitSystem->isFeatureActive( 'pigeonholes_themes' ) ) {
	$styles = $gBitThemes->getStyles( NULL, TRUE );
	$gBitSmarty->assign( 'styles', $styles );
}

// Display the template
if ( !empty( $gStructure ) ) {
	$gBitSystem->display( 'bitpackage:pigeonholes/edit_pigeonholes.tpl', !empty( $gStructure->mInfo['title'] ) ? tra( 'Edit Pigeonhole' ).': '.$gStructure->mInfo["title"] : tra( 'Create Pigeonhole' ) );
} else {
	$gBitSystem->display( 'bitpackage:pigeonholes/edit_pigeonholes.tpl', tra( 'Create Pigeonhole' ) );
}
?>
