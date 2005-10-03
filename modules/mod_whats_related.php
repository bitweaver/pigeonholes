<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_whats_related.php,v 1.1 2005/10/03 09:06:55 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_whats_related.php,v 1.1 2005/10/03 09:06:55 squareing Exp $
 * @package categories
 * @subpackage modules
 */

global $gQueryUserId, $module_rows, $module_params, $categlib, $gContent;

/**
 * required setup
 */
if( isset( $gContent ) ) {
	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
		$pigeonholes = new Pigeonholes( NULL, NULL, FALSE );

		if( $pigeons = $pigeonholes->getPigeonholesFromContentId( $gContent->mContentId ) ) {
			foreach( $pigeons as $pigeon ) {
				$pigeonholes->mContentId = $pigeon['content_id'];
				$pigeonholes->load( TRUE );
				$relatedPigeon[] = $pigeonholes->mInfo;
			}
			$gBitSmarty->assign( 'relatedPigeon', !empty( $relatedPigeon ) ? $relatedPigeon : FALSE );
		}
	}
}
?>
