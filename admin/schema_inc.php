<?php

$tables = array(
	'pigeonholes' => "
		content_id I4 NOTNULL PRIMARY,
		structure_id I4 NOTNULL PRIMARY
		CONSTRAINTS ',
			CONSTRAINT `pigeonholes_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` ),
			CONSTRAINT `pigeonholes_ref` FOREIGN KEY (`structure_id`) REFERENCES `".BIT_DB_PREFIX."liberty_structures`( `structure_id` )'
	",
	'pigeonhole_members' => "
		parent_id I4 NOTNULL PRIMARY,
		content_id I4 NOTNULL PRIMARY
		CONSTRAINTS ',
			CONSTRAINT `pigeonhole_members_ref` FOREIGN KEY (`parent_id`) REFERENCES `".BIT_DB_PREFIX."pigeonholes`( `content_id` ),
			CONSTRAINT `pigeonhole_members_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )'
	"
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( PIGEONHOLES_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( PIGEONHOLES_PKG_NAME, array(
	'description' => "A Categorisation system that makes it easy to keep an overview of your data. Has a simple, yet powerful interface for categorising multiple pages at once.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'experimental',
	'dependencies' => '',
) );

//// ### Indexes
//$indices = array (
//	'pigeonholes_content_idx' => array( 'table' => 'pigeonholes', 'cols' => 'content_id', 'opts' => 'UNIQUE' ),
//);
//$gBitInstaller->registerSchemaIndexes( PIGEONHOLES_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'pigeonholes_id_seq' => array( 'start' => 1 )
);

$gBitInstaller->registerSchemaSequences( PIGEONHOLES_PKG_NAME, $sequences );

// ### Default Preferences
$gBitInstaller->registerPreferences( PIGEONHOLES_PKG_NAME, array(
	array( PIGEONHOLES_PKG_NAME, 'display_pigeonhole_members','y' ),
	array( PIGEONHOLES_PKG_NAME, 'limit_member_number','100' ),
	array( PIGEONHOLES_PKG_NAME, 'pigeonholes_list_style','table' ),
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( PIGEONHOLES_PKG_NAME, array(
	array( 'bit_p_view_pigeonholes', 'Can view pigeonholes', 'basic', PIGEONHOLES_PKG_NAME ),
	array( 'bit_p_insert_pigeonhole_member', 'Can insert content into an existing pigeonhole', 'registered', PIGEONHOLES_PKG_NAME ),
	array( 'bit_p_edit_pigeonholes', 'Can edit pigeonholes', 'editors', PIGEONHOLES_PKG_NAME ),
) );

?>
