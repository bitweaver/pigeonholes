<?php
global $gContent;
if( !empty( $gContent->mContentId ) ) {
	if( $gBitUser->hasPermission( 'bit_p_view_pigeonholes' ) ) {
		require_once( PIGEONHOLES_PKG_PATH.'Pigeonholes.php' );
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
