<?php

$tables = array(
	'pigeonholes' => "
		content_id I4 NOTNULL PRIMARY,
		structure_id I4 NOTNULL PRIMARY
		CONSTRAINT '
			, CONSTRAINT `pigeonholes_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )'
	",
	'pigeonhole_members' => "
		parent_id I4 NOTNULL PRIMARY,
		content_id I4 NOTNULL PRIMARY
		CONSTRAINT '
			, CONSTRAINT `pigeonhole_members_parent_ref` FOREIGN KEY (`parent_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )
			, CONSTRAINT `pigeonhole_members_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )'
	"
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( PIGEONHOLES_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( PIGEONHOLES_PKG_NAME, array(
	'description' => "A Categorisation system that makes it easy to keep an overview of your data. Has a simple, yet powerful interface for categorising multiple pages at once.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Sequences
$sequences = array (
	'pigeonholes_id_seq' => array( 'start' => 1 )
);

$gBitInstaller->registerSchemaSequences( PIGEONHOLES_PKG_NAME, $sequences );

// ### Default Preferences
$gBitInstaller->registerPreferences( PIGEONHOLES_PKG_NAME, array(
	array( PIGEONHOLES_PKG_NAME, 'pigeonholes_display_members','y' ),
	array( PIGEONHOLES_PKG_NAME, 'pigeonholes_limit_member_number','100' ),
	array( PIGEONHOLES_PKG_NAME, 'pigeonholes_list_style','table' ),
	array( PIGEONHOLES_PKG_NAME, 'pigeonholes_menu_text', 'Categories' ),
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( PIGEONHOLES_PKG_NAME, array(
	array( 'p_pigeonholes_view', 'Can view pigeonholes', 'basic', PIGEONHOLES_PKG_NAME ),
	array( 'p_pigeonholes_insert_member', 'Can insert content into an existing pigeonhole', 'registered', PIGEONHOLES_PKG_NAME ),
	array( 'p_pigeonholes_edit', 'Can edit pigeonholes', 'editors', PIGEONHOLES_PKG_NAME ),
	//array( 'p_pigeonholes_admin', 'Can administer all aspects of pigeonholes', 'editors', PIGEONHOLES_PKG_NAME ),
) );

?>
