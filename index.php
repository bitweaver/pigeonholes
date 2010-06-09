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
include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );
if( !empty( $gContent->mStructureId ) ) {
	header( 'Location: '.$gContent->getDisplayUrl() );
} else {
	header( 'Location: '.PIGEONHOLES_PKG_URL.'list.php' );
}
die;
