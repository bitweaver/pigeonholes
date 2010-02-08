<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/edit_structure.php,v 1.9 2010/02/08 21:27:24 wjames5 Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: edit_structure.php,v 1.9 2010/02/08 21:27:24 wjames5 Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );

$gBitSystem->verifyPackage( 'pigeonholes' );
$gBitSystem->verifyPermission( 'p_pigeonholes_create' );

// we need to load some javascript and css for this page
$gBitThemes->loadCss( UTIL_PKG_PATH.'javascript/libs/mygosu/DynamicTree.css' );
if( $gSniffer->_browser_info['browser'] == 'ie' && $gSniffer->_browser_info['maj_ver'] == 5 ) {
	$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/mygosu/ie5.js' );
}
$gBitThemes->loadJavascript( UTIL_PKG_PATH.'javascript/libs/mygosu/DynamicTreeBuilder.js' );

include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );

$verifyStructurePermission = 'p_pigeonholes_create';
include_once( LIBERTY_PKG_PATH.'edit_structure_inc.php' );

// Display the template
$gBitSystem->display( 'bitpackage:pigeonholes/edit_structure.tpl', $gStructure->mInfo["title"] , array( 'display_mode' => 'edit' ));
?>
