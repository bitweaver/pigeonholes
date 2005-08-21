<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/index.php,v 1.1 2005/08/21 16:22:44 squareing Exp $
 *
 * Copyright ( c ) 2004 bitweaver.org
 * Copyright ( c ) 2003 tikwiki.org
 * Copyright ( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: index.php,v 1.1 2005/08/21 16:22:44 squareing Exp $
 * @package pigeonholes
 * @subpackage functions
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );
include_once( PIGEONHOLES_PKG_PATH.'lookup_pigeonholes_inc.php' );
if( !empty( $gPigeonholes->mStructureId ) ) {
	header( 'Location: '.PIGEONHOLES_PKG_URL.'view.php?structure_id='.$gPigeonholes->mStructureId );
} else {
	header( 'Location: '.PIGEONHOLES_PKG_URL.'list.php' );
}
