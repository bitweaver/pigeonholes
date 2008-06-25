<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/edit_pigeonholes.php,v 1.32 2008/06/25 22:21:17 spiderr Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: edit_pigeonholes.php,v 1.32 2008/06/25 22:21:17 spiderr Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'p_pigeonholes_edit' );

include_once( LIBERTY_PKG_PATH.'LibertyStructure.php' );
include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

// include edit structure file only when structure_id is known
if( !empty( $_REQUEST["structure_id"] ) && ( empty( $_REQUEST['action'] ) || $_REQUEST['action'] != 'remove' ) ) {
	$verifyStructurePermission = 'p_pigeonholes_edit';
	$noAjaxContent = TRUE;
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
	if(( empty( $_REQUEST['pigeonhole']['title'] ))) {
		$gBitSystem->fatalError( tra( "You must specify a title." ));
	}

	// we need to get the root structure id
	$_REQUEST['pigeonhole']['root_structure_id'] = !empty( $rootStructure->mStructureId ) ?  $rootStructure->mStructureId : NULL;
	// store the pigeonhole
	$pigeonStore = new Pigeonholes( NULL, !empty( $_REQUEST['pigeonhole_content_id'] ) ? $_REQUEST['pigeonhole_content_id'] : NULL );
	$pigeonStore->load();
	if( $pigeonStore->store( $_REQUEST['pigeonhole'] )) {
		header( "Location: ".$_SERVER['PHP_SELF'].'?structure_id='.$pigeonStore->mStructureId.( !empty( $_REQUEST['action'] ) ? '&action='.$_REQUEST['action'] : '' )."&success=".urlencode( tra( "The category was successfully stored" ) ) );
	} else {
		$feedback['error'] = $gContent->mErrors;
	}
}

if( !empty( $_REQUEST['action'] ) || isset( $_REQUEST["confirm"] ) ) {
	// if we need to edit, show the information
	if( $_REQUEST['action'] == 'edit' ) {
		$pigeonInfo = $gContent->mInfo;
		$gContent->loadPreferences();
	}

	if( $_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'create' ) {
		$gBitSmarty->assign( 'pigeonInfo', !empty( $pigeonInfo ) ? $pigeonInfo : NULL );
	}

	if( $_REQUEST["action"] == 'remove' || isset( $_REQUEST["confirm"] ) ) {
		if( isset( $_REQUEST["confirm"] ) ) {
			if( $gContent->expunge( $_REQUEST["structure_id"] ) ) {
				bit_redirect( $_SERVER['PHP_SELF'].'?structure_id='.$gContent->mInfo["parent_id"] );
			} else {
				$feedback['error'] = $gContent->mErrors;
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

	if( $_REQUEST['action'] == 'dismember' && !empty( $_REQUEST['pigeonhole_content_id'] ) && !empty( $_REQUEST['parent_id'] ) ) {
		if( $gContent->expungePigeonholeMember( array( 'parent_id' => $_REQUEST['parent_id'], 'member_id' => $_REQUEST['pigeonhole_content_id'] ) ) ) {
			$feedback['success'] = tra( 'The item was successfully removed' );
		} else {
			$feedback['error'] = tra( 'The item could not be removed' );
		}
		// Have we been asked to return somewhere else?
		if (!empty($_REQUEST['return_uri'])) {
			bit_redirect($_REQUEST['return_uri']);
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
	$listHash = array(
		'only_root_groups' => TRUE,
		'sort_mode' => !empty( $_REQUEST['sort_mode'] ) ? $_REQUEST['sort_mode'] : 'group_name_asc'
	);
	$allGroups = $gBitUser->getAllGroups( $listHash );

	// create a usable array for group selection
	$groups[0] = tra( 'None' );
	foreach( $allGroups as $group ) {
		$groups[$group['group_id']] = $group['group_name'];
	}
	$gBitSmarty->assign( 'groups', $groups );
}

$listHash = array(
	'root_structure_id' => !empty( $gContent->mInfo['root_structure_id'] ) ? $gContent->mInfo['root_structure_id'] : NULL,
	'force_extras'      => TRUE,
	'max_records'       => -1
);
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
	$gBitSystem->display( 'bitpackage:pigeonholes/edit_pigeonholes.tpl', !empty( $gStructure->mInfo['title'] ) ? tra( 'Edit Pigeonhole' ).': '.$gStructure->mInfo["title"] : tra( 'Create Pigeonhole' ) , array( 'display_mode' => 'edit' ));
} else {
	$gBitSystem->display( 'bitpackage:pigeonholes/edit_pigeonholes.tpl', tra( 'Create Pigeonhole' ) , array( 'display_mode' => 'edit' ));
}
?>
