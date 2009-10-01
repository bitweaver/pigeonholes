<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_whats_related.php,v 1.8 2009/10/01 14:17:03 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: mod_whats_related.php,v 1.8 2009/10/01 14:17:03 wjames5 Exp $
 * @package categories
 * @subpackage modules
 */

global $gContent;
if( !empty( $gContent->mContentId ) ) {
	if( $gBitUser->hasPermission( 'p_pigeonholes_view' ) ) {
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
