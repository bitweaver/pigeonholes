<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/view.php,v 1.3 2006/01/17 13:40:49 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: view.php,v 1.3 2006/01/17 13:40:49 squareing Exp $
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

// set up structure related stuff
global $gStructure;
$gStructure = new LibertyStructure( $gPigeonholes->mInfo['root_structure_id'] );
$gStructure->load();

// order matters for these conditionals
if( empty( $gStructure ) || !$gStructure->isValid() ) {
	$gBitSystem->fatalError( 'Invalid structure' );
}

$gBitSmarty->assign_by_ref( 'gStructure', $gStructure );
$gBitSmarty->assign( 'structureInfo', $gStructure->mInfo );
$gBitSmarty->assign( 'subtree', $gStructure->getSubTree( $gStructure->mStructureId ) );

$pigeonList = array(
	'root_structure_id' => $gPigeonholes->mInfo['root_structure_id'],
	'structure_id' => $gPigeonholes->mInfo['structure_id'],
	'load_extras' => TRUE
);
$gPigeonholes->getList( $pigeonList );
$gBitSmarty->assign( 'pigeonList', $pigeonList['data'] );
$gBitSmarty->assign( 'list_style', $gBitSystem->getPreference( 'pigeonholes_list_style', 'dynamic' ) );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/view_structure.tpl', tra( 'View Pigeonhole' ) );
?>
