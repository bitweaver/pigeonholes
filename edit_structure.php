<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/edit_structure.php,v 1.1.1.1.2.1 2006/01/13 23:18:43 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: edit_structure.php,v 1.1.1.1.2.1 2006/01/13 23:18:43 squareing Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'bit_p_edit_pigeonholes' );

$gBitSmarty->assign( 'loadDynamicTree', TRUE );
include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$verifyStructurePermission = 'bit_p_edit_pigeonholes';
include_once( LIBERTY_PKG_PATH.'edit_structure_inc.php' );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/edit_structure.tpl', $gStructure->mInfo["title"] );
?>
