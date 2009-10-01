<?php
/**
 * $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_category_menu.php,v 1.5 2009/10/01 13:45:46 wjames5 Exp $
 *
 * Copyright (c) 2004 bitweaver.org
 * Copyright (c) 2003 tikwiki.org
 * Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
 * All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * $Id: mod_category_menu.php,v 1.5 2009/10/01 13:45:46 wjames5 Exp $
 * @package categories
 * @subpackage modules
 */

global $gContent;
if( !empty( $gContent->mContentId ) ) {
	if( $gBitUser->hasPermission( 'p_pigeonholes_view' ) ) {
		$pigeonholes = new Pigeonholes();

		if( empty( $gStructure ) ) {
			$gStructure = new LibertyStructure();
		}
		if( $pigeons = $pigeonholes->getPigeonholesFromContentId( $gContent->mContentId ) ) {
			foreach( $pigeons as $pigeon ) {
				$modPigeonStructures[] = $gStructure->getSubTree( $pigeon['root_structure_id'], TRUE );
			}
			$gBitSmarty->assign( 'modPigeonStructures', !empty( $modPigeonStructures ) ? $modPigeonStructures : FALSE );
		}
	}
}
?>
