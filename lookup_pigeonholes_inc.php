<?php
/**
 * Provide a list of pigenoholes
 *
 * @package pigeonholes
 * @subpackage functions
 * @version $Header: /cvsroot/bitweaver/_bit_pigeonholes/lookup_pigeonholes_inc.php,v 1.3 2006/01/24 10:21:23 squareing Exp $
 *
 * Copyright ( c ) 2005 bitweaver.org
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 */

/**
 * Required Files
 */
require_once( PIGEONHOLES_PKG_PATH.'/Pigeonholes.php' );

$gContent = new Pigeonholes( ( !empty( $_REQUEST['structure_id'] ) ? $_REQUEST['structure_id'] : NULL ), ( !empty( $_REQUEST['content_id'] ) ? $_REQUEST['content_id'] : NULL ) );
$gContent->load( TRUE );
$gBitSmarty->assign( 'gContent', $gContent );
?>
