<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_whats_related.php,v 1.5 2006/02/24 12:06:08 squareing Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 *
 * $Id: mod_whats_related.php,v 1.5 2006/02/24 12:06:08 squareing Exp $
 * @package categories
 * @subpackage modules
 */

global $gContent;
if( !empty( $gContent->mContentId ) ) {
	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		$pigeonholes = new Pigeonholes();

		if( $pigeons = $pigeonholes->getPigeonholesFromContentId( $gContent->mContentId ) ) {
			foreach( $pigeons as $pigeon ) {
				$pigeonholes->mContentId = $pigeon['content_id'];
				$pigeonholes->load( TRUE );
				$modRelatedPigeon[] = $pigeonholes->mInfo;
			}
			$gBitSmarty->assign( 'modRelatedPigeon', !empty( $modRelatedPigeon ) ? $modRelatedPigeon : FALSE );
		}
	}
}
?>
