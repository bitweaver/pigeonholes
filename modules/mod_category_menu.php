<?php
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
