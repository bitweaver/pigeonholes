<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_pigeonholes/Pigeonholes.php,v 1.1 2005/08/21 16:22:44 squareing Exp $
 *
 * +----------------------------------------------------------------------+
 * | Copyright ( c ) 2004, bitweaver.org
 * +----------------------------------------------------------------------+
 * | All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * | Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details
 * |
 * | For comments, please use phpdocu.sourceforge.net documentation standards!!!
 * | -> see http://phpdocu.sourceforge.net/
 * +----------------------------------------------------------------------+
 * | Authors: xing <xing@synapse.plus.com>
 * +----------------------------------------------------------------------+
 *
 * Pigeonholes class
 *
 * @author   xing <xing@synapse.plus.com>
 * @version  $Revision: 1.1 $
 * @package  pigeonholes
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyAttachable.php' );
require_once( LIBERTY_PKG_PATH.'LibertyStructure.php' );

/**
 * Pigeonholes
 *
 * @package  pigeonholes
 */
class Pigeonholes extends LibertyAttachable {
	/**
	* initiate class
	* @param $pContentId content id of the pigeonhole - use either one of the ids.
	* @param $pStructureId structure id of the pigeonhole - use either one of the ids.
	* @param $pAutoLoad boolean - if set to FALSE, no pigeonhole data is loaded
	* @param $pExtras boolean - if set to TRUE, pigeonhole content is added as well - 1 additional db access
	* @return none
	* @access public
	**/
	function Pigeonholes( $pStructureId=NULL, $pContentId=NULL, $pAutoLoad=TRUE, $pExtras=FALSE ) {
		LibertyAttachable::LibertyAttachable();
		$this->registerContentType( PIGEONHOLES_CONTENT_TYPE_GUID, array(
			'content_type_guid' => PIGEONHOLES_CONTENT_TYPE_GUID,
			'content_description' => 'Pigeonhole',
			'handler_class' => 'Pigeonholes',
			'handler_package' => 'pigeonholes',
			'handler_file' => 'Pigeonholes.php',
			'maintainer_url' => 'http://www.bitweaver.org'
		) );
		$this->mContentId = $pContentId;
		$this->mStructureId = $pStructureId;
		$this->mContentTypeGuid = PIGEONHOLES_CONTENT_TYPE_GUID;
		if( $pAutoLoad ) {
			$this->load( $pExtras );
		}
	}

	/**
	* load the pigeonhole
	* @param $pExtras boolean - if set to true, pigeonhole content is added as well
	* @return bool TRUE on success, FALSE if it's not valid
	* @access public
	**/
	function load( $pExtras=FALSE ) {
		if( $this->verifyId( $this->mContentId ) || $this->verifyId( $this->mStructureId ) ) {
			global $gBitSystem;
			$lookupColumn = ( !empty( $this->mContentId ) ? 'tc.`content_id`' : 'ts.`structure_id`' );
			$lookupId = ( !empty( $this->mContentId ) ? $this->mContentId : $this->mStructureId );
			$query = "SELECT bp.*, ts.`root_structure_id`, ts.`parent_id`, tc.`title`, tc.`data`, tc.`content_type_guid`,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				FROM `".BIT_DB_PREFIX."bit_pigeonholes` bp
				INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON ( tc.`content_id` = bp.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."tiki_structures` ts ON ( ts.`structure_id` = bp.`structure_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = tc.`modifier_user_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = tc.`user_id` )
				WHERE $lookupColumn=?";
			$result = $this->mDb->query( $query, array( $lookupId ) );

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mStructureId = $result->fields['structure_id'];
				$this->mInfo['creator'] = ( isset( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = ( isset( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_link'] = $this->getDisplayLink();
			}

			// if the content for the pigeonhole is requested, get it
			if( $pExtras ) {
				$this->mInfo['path'] = $this->getPigeonholePath();
				$this->mInfo['display_path'] = $this->getDisplayPath( $this->mInfo['path'] );
				$this->mInfo['members'] = $this->getPigeonholeMembers();
				$this->mInfo['members_count'] = count( $this->mInfo['members'] );
			}
		}
		return( count( $this->mInfo ) );
	}

	/**
	* get all content inserted in a given pigeonhole. if no id is given, it gets all content for all pigeonholes
	* @param $pContentId content id of the pigeonhole
	* @return array of pigeonhole members with according title and content type guid
	* @access public
	**/
	function getPigeonholeMembers( $pContentId=NULL ) {
		global $gBitUser, $gLibertySystem, $gBitSystem;
		$ret = FALSE;
		if( !empty( $this->mContentId ) || !empty( $pContentId ) ) {
			if( $gBitSystem->isFeatureActive( 'custom_member_sorting' ) ) {
				$order = "ORDER BY bpm.`pos` ASC";
			} else {
				$order = "ORDER BY tc.`content_type_guid`, tc.`title` ASC";
			}

			$bindVars[] = $this->verifyId( $pContentId ) ? $pContentId : $this->mContentId;
			$ret = array();
			$query = "SELECT bpm.*, tc.`content_id`, tc.`user_id`, tc.`title`, tc.`content_type_guid`, uu.`login`, uu.`real_name`
				FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` bpm
				RIGHT JOIN `".BIT_DB_PREFIX."bit_pigeonholes` bp ON ( bp.`content_id` = bpm.`parent_id` )
				INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON ( tc.`content_id` = bpm.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uu ON ( uu.`user_id` = tc.`user_id` )
				WHERE bp.`content_id`=?
				$order";
			$result = $this->mDb->query( $query, $bindVars );
			$contentTypes = $gLibertySystem->mContentTypes;
			while( !$result->EOF ) {
				$i = $result->fields['content_id'];
				$ret[$i] = $result->fields;
				if( !empty( $contentTypes[$ret[$i]['content_type_guid']] ) ) {
					$type = &$contentTypes[$ret[$i]['content_type_guid']];
					if( empty( $type['content_object'] ) ) {
						// create *one* object for each object *type* to  call virtual methods.
						include_once( $gBitSystem->mPackages[$type['handler_package']]['path'].$type['handler_file'] );
						$type['content_object'] = new $type['handler_class']();
					}
					$ret[$i]['display_link'] = $type['content_object']->getDisplayLink( $ret[$i]['title'], $ret[$i] );
					$ret[$i]['title'] = $type['content_object']->getTitle( $ret[$i] );
				}
				$result->MoveNext();
			}
		} else {
			$this->mErrors['get_members'] = tra( 'No valid pigeonhole id given to collect members from.' );
		}
		return( !empty( $ret ) ? $ret : NULL );
	}

	/**
	* get all items that are not part of a pigeonhole yet
	* @param $pContentType content type guid of items to be collected. if empty, all content is collected
	* @param $pIncludeMembers if set to TRUE (boolean), it will return members as well, where the pigeonholes they are assigned to, are in the sub array 'assigned'
	* @return array of content not in any pigeonhole yet
	* @access public
	**/
	function getNonPigeonholeMembers( $pListHash=NULL, $pContentType=NULL, $pIncludeMembers=FALSE ) {
		global $gBitUser, $gLibertySystem, $gBitSystem;
		$where = '';
		$bindVars = array();

		if( !$pIncludeMembers ) {
			$where .= "WHERE tc.`content_id` NOT IN ( SELECT DISTINCT `content_id` FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` )";
		}

		if( !empty( $pListHash['find'] ) && is_string( $pListHash['find'] ) ) {
			$where .= empty( $where ) ? ' WHERE ' : ' AND ';
			$where .= " UPPER( tc.`title` ) LIKE ?";
			$bindVars[] = ( '%'.strtoupper( $pListHash['find'] ).'%');
		}

		if( $pContentType ) {
			$where .= empty( $where ) ? ' WHERE ' : ' AND ';
			$where .= " tc.`content_type_guid`=?";
			$bindVars[] = $pContentType;
		}

		if( !empty( $pListHash['sort_mode'] ) ) {
			$where .= " ORDER BY ".$this->mDb->convert_sortmode( $pListHash['sort_mode'] )." ";
		} else {
			$where .= " ORDER BY tc.`content_type_guid`, tc.`title` ASC";
		}

		$query = "SELECT bpm.`parent_id`, tc.`content_id`, tc.`user_id`, tc.`title`, tc.`content_type_guid`, uu.`login`, uu.`real_name`
			FROM `".BIT_DB_PREFIX."tiki_content` tc
			LEFT JOIN `".BIT_DB_PREFIX."bit_pigeonhole_members` bpm ON ( bpm.`content_id` = tc.`content_id` )
			LEFT JOIN `".BIT_DB_PREFIX."users_users` uu ON ( uu.`user_id` = tc.`user_id` )
			$where";
		$result = $this->mDb->query( $query, $bindVars, empty( $pListHash['max_rows'] ) ? NULL : $pListHash['max_rows'] );

		$contentTypes = $gLibertySystem->mContentTypes;
		while( !$result->EOF ) {
			$i = $result->fields['content_id'];
			$ret[$i] = $result->fields;
			if( !empty( $contentTypes[$ret[$i]['content_type_guid']] ) ) {
				$type = &$contentTypes[$ret[$i]['content_type_guid']];
				if( empty( $type['content_object'] ) ) {
					// create *one* object for each object *type* to  call virtual methods.
					include_once( $gBitSystem->mPackages[$type['handler_package']]['path'].$type['handler_file'] );
					$type['content_object'] = new $type['handler_class']();
				}
				$ret[$i]['display_link'] = $type['content_object']->getDisplayLink( $ret[$i]['title'], $ret[$i] );
				$ret[$i]['title'] = $type['content_object']->getTitle( $ret[$i] );
			}

			// generate a map of what items are assigned to what pigeonholes
			if( $pIncludeMembers && !empty( $result->fields['parent_id'] ) ) {
				$map[$i][] = $result->fields['parent_id'];
			}

			$result->MoveNext();
		}

		// complete the output
		if( $pIncludeMembers && !empty( $ret ) ) {
			foreach( $ret as $i => $r ) {
				$ret[$i]['assigned'] = !empty( $map[$i] ) ? $map[$i] : NULL;
			}
		}

		return( !empty( $ret ) ? $ret : NULL );
	}

	/**
	* get an array of paths for all pigeonholes. used for pages where data can be inserted into pigeonholes
	* @param $pContentId content id of pigeonhole.
	* @return path in form of an array on success, FALSE ( boolean ) if content is in no pigeonhole
	* @access public
	* @TODO sort the array somehow to make sure that the path is always incrementing and looks nice...
	**/
	function getPigeonholesPathList( $pContentId=NULL ) {
		$query = "SELECT bp.`content_id`, bp.`structure_id`
			FROM `".BIT_DB_PREFIX."bit_pigeonholes` bp
			ORDER BY bp.`content_id` ASC";
		$result = $this->mDb->query( $query );
		$pigeonholes = $result->getRows();
		foreach( $pigeonholes as $pigeonhole ) {
			$ret[$pigeonhole['content_id']] = $this->getPigeonholePath( $pigeonhole['structure_id'] );
		}

		if( !empty( $pContentId ) && $assigned = $this->getPigeonholesFromContentId( $pContentId ) ) {
			foreach( $assigned as $a ) {
				$ret[$a['content_id']][0]['selected'] = TRUE;
			}
		}

		return( !empty( $ret ) ? $ret : FALSE );
	}

	/**
	* get all pigeonholes where the contenent has been inserted
	* @param $pContentId content id of item in question
	* @return basic information about item requested
	* @access public
	**/
	function getPigeonholesFromContentId( $pContentId ) {
		if( $this->verifyId( $pContentId ) ) {
			$query = "SELECT bp.*
				FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` bpm
				INNER JOIN `".BIT_DB_PREFIX."bit_pigeonholes` bp ON ( bp.`content_id` = bpm.`parent_id` )
				WHERE bpm.`content_id`=?";
			$result = $this->mDb->query( $query, array( $pContentId ) );
			$ret = $result->getRows();
		}
		return( !empty( $ret ) ? $ret : FALSE );
	}

	/**
	* get the path of a pigeonhole
	* @param $pStructureId structure id of pigeonhole, if no id is given, it gets the id from $this->mStructureId
	* @return path in form of an array
	* @access public
	**/
	function getPigeonholePath( $pStructureId=NULL ) {
		if( !$this->verifyId( $pStructureId ) ) {
			$pStructureId = $this->mStructureId;
		}

		if( $this->verifyId( $pStructureId ) ) {
			global $gStructure;
			// create new object if needed
			if( empty( $gStructure ) ) {
				$gStructure = new LibertyStructure();
			}
			// get the structure path
			$ret = $gStructure->getPath( $pStructureId );
		}
		return( !empty( $ret ) ? $ret : FALSE );
	}

	/**
	* Converts a structure path into valid html links
	* @param $pPath path given by getPigenholePath()
	* @return the link to display the page.
	*/
	function getDisplayPath( $pPath ) {
		$ret = '';
		if( !empty( $pPath ) && is_array( $pPath ) ) {
			foreach( $pPath as $node ) {
				$ret .= ( !empty( $node['parent_id'] ) ? ' &raquo; ' : '' ).'<a href="'.PIGEONHOLES_PKG_URL.'view.php?structure_id='.$node['structure_id'].'">'.$node['title'].'</a>';
			}
		}
		return $ret;
	}

	/**
	* get list of all pigeonholes
	* @param $pListHash contains array of items used to limit search results
	* @param $pListHash[sort_mode] column and orientation by which search results are sorted
	* @param $pListHash[find] search for a pigeonhole title - case insensitive
	* @param $pListHash[max_rows] maximum number of rows to return
	* @param $pListHash[offset] number of results data is offset by
	* @return array of pigeonholes in 'data' and count of pigeonholes in 'cant'
	* @access public
	**/
	function getList( $pListHash=NULL, $pOnlyGetRoot=FALSE, $pExtras=FALSE ) {
		$bindVars = array();
		$where = '';
		if( !empty( $pListHash['find'] ) ) {
			$where .= " WHERE UPPER( tc.`title` ) LIKE ? ";
			$bindVars[] = '%'.strtoupper( $pListHash['find'] ).'%';
		}

		if( !empty( $pListHash['root_structure_id'] ) && $this->verifyId( $pListHash['root_structure_id'] ) ) {
			$where .= empty( $where ) ? ' WHERE ' : ' AND ';
			$where .= " ts.`root_structure_id`=? ";
			$bindVars[] = $pListHash['root_structure_id'];
		}

		if( $pOnlyGetRoot ) {
			$where .= empty( $where ) ? ' WHERE ' : ' AND ';
			$where .= " ts.`structure_id`=ts.`root_structure_id` ";
		}

		if( !empty( $pListHash['sort_mode'] ) ) {
			$where .= " ORDER BY ".$this->mDb->convert_sortmode( $pListHash['sort_mode'] )." ";
		}

		$query = "SELECT bp.`content_id`,
			uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
			uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
			FROM `".BIT_DB_PREFIX."bit_pigeonholes` bp
			INNER JOIN `".BIT_DB_PREFIX."tiki_content` tc ON ( tc.`content_id` = bp.`content_id` )
			LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON ( uue.`user_id` = tc.`modifier_user_id` )
			LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON ( uuc.`user_id` = tc.`user_id` )
			INNER JOIN `".BIT_DB_PREFIX."tiki_structures` ts ON ( ts.`structure_id` = bp.`structure_id` )
			$where";

		if( isset( $pListHash['max_rows'] ) && is_numeric( $pListHash['max_rows'] ) && isset( $pListHash['offset'] ) && is_numeric( $pListHash['offset'] ) ) {
			$result = $this->mDb->query( $query, $bindVars, $pListHash['max_rows'], $pListHash['offset'] );
		} else {
			$result = $this->mDb->query( $query, $bindVars );
		}

		$pigeonIds = $result->getRows();
		$ret['data'] = array();
		foreach( $pigeonIds as $id ) {
			$tmpPigeon = new Pigeonholes( NULL, $id['content_id'], TRUE, $pExtras );
			$ret['data'][] = $tmpPigeon->mInfo;
		}

		$query = "SELECT COUNT( bp.`content_id` )
			FROM `".BIT_DB_PREFIX."bit_pigeonholes` bp
			INNER JOIN `".BIT_DB_PREFIX."tiki_structures` ts ON ( ts.`structure_id` = bp.`structure_id` )
			WHERE  ts.`structure_id`=ts.`root_structure_id`";
		$ret['cant'] = $this->mDb->getOne( $query );

		return $ret;
	}

	/**
	* Store pigeonhole data
	* @param $pParamHash contains all data to store the pigeonholes
	* @param $pParamHash[title] title of the new pigeonhole
	* @param $pParamHash[edit] description of the pigeonhole
	* @param $pParamHash[members] array of content_ids that are associated with this pigeonhole
	* @param $pParamHash[root_structure_id] if this is set, it will add the pigeonhole to this structure. if it's not set, a new structure / top level pigeonhole is created
	* @param $pParamHash[parent_id] set the structure_id that will server as the parent in the structure
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access public
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) && LibertyAttachable::store( $pParamHash ) ) {
			$table = BIT_DB_PREFIX."bit_pigeonholes";
			$this->mDb->StartTrans();

			// this really confusing, strange order way of saving items is due to strange behaviour of GenID
			// probably has to do with not null default nextval('public.tiki_structures_structure_id_seq'::text)
			if( $this->mContentId ) {
				if( !empty( $pParamHash['pigeonhole_store'] ) ) {
					$locId = array ( "name" => "content_id", "value" => $this->mContentId );
					$result = $this->mDb->associateUpdate( $table, $pParamHash['pigeonhole_store'], $locId );
				}
				$pParamHash['structure_location_id'] = $this->mStructureId;
			} else {
				// update the pigeonhole_store and structure_store content_id with the one from LibertyAttachable::store()
				$pParamHash['structure_store']['content_id'] = $pParamHash['content_id'];
				$pParamHash['pigeonhole_store']['content_id'] = $pParamHash['content_id'];

				// we need to store the new structure node now
				global $gStructure;
				// create new object if needed
				if( empty( $gStructure ) ) {
					$gStructure = new LibertyStructure();
				}
				$pParamHash['structure_location_id'] = $gStructure->storeNode( $pParamHash['structure_store'] );

				// get the corrent structure_id
				// structure_id has to be done like this since it's screwed up in the schema
				$pParamHash['pigeonhole_store']['structure_id'] =  $this->mDb->getOne( "SELECT MAX( `structure_id` ) FROM `".BIT_DB_PREFIX."tiki_structures`" );
				$this->mContentId = $pParamHash['pigeonhole_store']['content_id'];
				$result = $this->mDb->associateInsert( $table, $pParamHash['pigeonhole_store'] );
			}

			// store content items
			if( !empty( $pParamHash['pigeonhole_members_store'] ) ) {
				// remove items first
				$this->expungePigeonholeMember( $this->mContentId );
				if( !$this->insertPigeonholeMember( $pParamHash['pigeonhole_members_store'] ) ) {
					$this->mErrors['store'] = 'The content could not be inserted into the respective categories.';
				}
			}

			$this->mDb->CompleteTrans();
			$this->load();
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* verify, clean up and prepare data to be stored
	* @param $pParamHash all information that is being stored. will update $pParamHash by reference with fixed array of itmes
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verify( &$pParamHash ) {
		// make sure we're all loaded up if everything is valid
		if( $this->isValid() && empty( $this->mInfo ) ) {
			$this->load( TRUE );
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( !empty( $this->mContentId ) ) {
			$pParamHash['content_id'] = $this->mContentId;
		}

		// content store
		// check for name issues, first truncate length if too long
		if( !empty( $pParamHash['title'] ) )  {
			if( empty( $this->mContentId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this category.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
				}
			} else {
				$pParamHash['content_store']['title'] = ( isset( $pParamHash['title'] ) ) ? substr( $pParamHash['title'], 0, 160 ) : $this->mPigeonholeName;
			}
		} elseif( empty( $pParamHash['title'] ) ) {
			// no name specified
			$this->mErrors['title'] = 'You must specify a name';
		}

		// sort out the description
		if( $this->isValid() && !empty( $this->mInfo['data'] ) && empty( $pParamHash['edit'] ) ) {
			$pParamHash['edit'] = '';
		} elseif( empty( $pParamHash['edit'] ) ) {
			unset( $pParamHash['edit'] );
		} else {
			$pParamHash['edit'] = substr( $pParamHash['edit'], 0, 250 );
		}

		// pigeonhole member store
		// work out what to do with the content of the pigeonhole
		if( $this->isValid() && !empty( $this->mInfo['members'] ) && empty( $pParamHash['members'] ) ) {
			$pParamHash['pigeonhole_members_store']['members'] = '';
		} elseif( empty( $pParamHash['members'] ) ) {
			unset( $pParamHash['members'] );
		} else {
			$i = 1;
			$pos = 1;

			// if this is not the first save, we need to get positional data from members and insert them
			if( !empty( $this->mContentId ) ) {
				$members = $this->getPigeonholeMembers( $this->mContentId );
				$pos = count( $members ) + 1;
			}

			foreach( $pParamHash['members'] as $c_id ) {
				if( !empty( $members[$c_id]['pos'] ) ) {
					$pParamHash['pigeonhole_members_store'][$i]['pos'] = $members[$c_id]['pos'];
				} else {
					$pParamHash['pigeonhole_members_store'][$i]['pos'] = $pos++;
				}
				$pParamHash['pigeonhole_members_store'][$i]['content_id'] = $c_id;
				$i++;
			}
		}

		// structure store
		if( !empty( $pParamHash['root_structure_id'] ) && $this->verifyId( $pParamHash['root_structure_id'] ) ) {
			$pParamHash['structure_store']['root_structure_id'] = $pParamHash['root_structure_id'];
		} else {
			$pParamHash['structure_store']['root_structure_id'] = NULL;
		}

		if( !empty( $pParamHash['parent_id'] ) && $this->verifyId( $pParamHash['parent_id'] ) ) {
			$pParamHash['structure_store']['parent_id'] = $pParamHash['parent_id'];
		} else {
			$pParamHash['structure_store']['parent_id'] = NULL;
		}

		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Move content member either up or down when using custom sorting
	* @param $pParentId pigeonhole id the member belongs to
	* @param $pMemberId content id of the pigeonhole member
	* @param $pOrientation requires either north or south as value
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access public
	**/
	function moveMember( $pParentId, $pMemberId, $pOrientation ) {
		if( $this->isValid() && !empty( $pParentId ) && is_numeric( $pParentId ) && !empty( $pMemberId ) && is_numeric( $pMemberId ) ) {
			if( !empty( $pOrientation ) && $pOrientation == 'north' ) {
				$query = "SELECT `parent_id`, `content_id`, `pos` FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` WHERE `pos`<? AND `parent_id`=? ORDER BY `pos` DESC";
			} elseif ( !empty( $pOrientation ) && $pOrientation == 'south' ) {
				$query = "SELECT `parent_id`, `content_id`, `pos` FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` WHERE `pos`>? AND `parent_id`=? ORDER BY `pos` ASC";
			} else {
				$this->mErrors['orientation'] = tra( 'The member could not be moved since the orientation is not known.' );
			}

			// execute sql if everything is in order so far
			if( !empty( $query ) ) {
				$this->mDb->StartTrans();
				$result = $this->mDb->query( $query, array( $this->mInfo['members'][$pMemberId]['pos'], $pParentId ) );
				$res = $result->fetchRow();
				if( $res ) {
					//Swap positional values
					$query = "UPDATE `".BIT_DB_PREFIX."bit_pigeonhole_members` SET `pos`=? WHERE `parent_id`=? AND `content_id`=?";
					$this->mDb->query( $query, array( $res['pos'], $pParentId, $pMemberId ) );
					$this->mDb->query( $query, array( $this->mInfo['members'][$pMemberId]['pos'], $res['parent_id'], $res['content_id'] ) );
				}
				$this->mDb->CompleteTrans();
			}
		} else {
			$this->mErrors['move_member'] = tra( 'The category member could not be moved up, due to faulty data.' );
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store pigeonhole member
	* @param $pParamHash an array of conent to be stored.
	* @param $pParamHash[parent_id] id of pigeonhole it belongs to, default is $this->mContentId
	* @param $pParamHash[content_id] content_id of the item to be stored
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access public
	**/
	function insertPigeonholeMember( &$pParamHash ) {
		if( $this->verifyPigeonholeMember( $pParamHash ) ) {
			foreach( $pParamHash['member_store'] as $item ) {
				$result = $this->mDb->associateInsert( BIT_DB_PREFIX."bit_pigeonhole_members", $item );
			}
		} else {
			vd( $this->mErrors );
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* verify, clean up and prepare data to be stored
	* @param $pParamHash all information that is being stored. will update $pParamHash by reference with fixed array of itmes
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verifyPigeonholeMember( &$pParamHash ) {
		$this->mDb->StartTrans();
		foreach( $pParamHash as $key => $item ) {
			if( isset( $item['parent_id'] ) && $this->verifyId( $item['parent_id'] ) ) {
				$tmp['member_store'][$key]['parent_id'] = $item['parent_id'];
			} elseif( !empty( $this->mContentId ) ) {
				$tmp['member_store'][$key]['parent_id'] = $this->mContentId;
				$pParamHash[$key]['parent_id'] = $this->mContentId;
			} else {
				$this->mErrors['store_members'] = tra( 'The content could not be inserted because the parent_id was missing.' );
			}

			if( isset( $item['content_id'] ) && $this->verifyId( $item['content_id'] ) ) {
				$tmp['member_store'][$key]['content_id'] = $item['content_id'];
			} else {
				$this->mErrors['store_members'] = 'The content id is not valid.';
			}

			// if no positional info is given, we just append the items.
			if( isset( $item['pos'] ) && is_numeric( $item['content_id'] ) ) {
				$tmp['member_store'][$key]['pos'] = $item['pos'];
			} elseif( !empty( $tmp['member_store'][$key-1]['pos'] ) ) {
				$tmp['member_store'][$key]['pos'] = $tmp['member_store'][$key-1]['pos'] + 1;
			} else {
				$query = "SELECT COUNT(*) FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` WHERE `parent_id`=?";
				$tmp['member_store'][$key]['pos'] = $this->mDb->getOne( $query, array( $tmp['member_store'][$key]['parent_id'] ) ) + 1;
			}
		}

		$this->mDb->CompleteTrans();
		$pParamHash = $tmp;
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Expunge pigeonhole member
	* @param $pMemberId content_id of content to be deleted
	*	Note if only one of the 2 ids is given, all items with that id will be removed. if both are given, only that one particular entry is removed
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access public
	**/
	function expungePigeonholeMember( $pParentId=NULL, $pMemberId=NULL ) {
		if( !empty( $pParentId ) && $this->verifyId( $pParentId ) || !empty( $pMemberId ) && $this->verifyId( $pMemberId ) ) {
			$where = '';
			if( $this->verifyId( $pParentId ) ) {
				$where .= "WHERE `parent_id`=?";
				$bindVars[] = $pParentId;
			}

			if( $this->verifyId( $pMemberId ) ) {
				$where .= ( empty( $where ) ? "WHERE" : "AND" )." `content_id`=?";
				$bindVars[] = $pMemberId;
			}

			$this->mDb->StartTrans();
			// depending on what data we've been given, we need to shift several items up to keep pos continuous
			if( !empty( $pMemberId ) ) {
				$query = "SELECT * FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` $where";
				$result = $this->mDb->query( $query, $bindVars );
				$members = $result->getRows();
				foreach( $members as $member ) {
					$query = "UPDATE `".BIT_DB_PREFIX."bit_pigeonhole_members` SET `pos`=`pos`-1 WHERE `pos`>? AND `parent_id`=?";
					$this->mDb->query( $query, array( $member['pos'], $member['parent_id'] ) );
				}
			}

			// now we're ready to remove the actual members
			$query = "DELETE FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` $where";
			$result = $this->mDb->query( $query, $bindVars );
			$this->mDb->CompleteTrans();
		} else {
			$this->mErrors['members_store'] = 'The category member(s) could not be removed.';
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Expunge currently loaded pigeonhole
	* @return bool TRUE on success, FALSE if store could not occur.
	* @access public
	**/
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			// get all items that are part of the sub tree
			require_once( LIBERTY_PKG_PATH.'LibertyStructure.php' );
			$struct = new LibertyStructure();

			$tree = $struct->getSubTree( $this->mStructureId );
			foreach( $tree as $node ) {
				$structureIds[] = $node['structure_id'];
			}

			$structureIds = array_unique( $structureIds );
			foreach( $structureIds as $structureId ) {
				$contentIds[] = $this->mDb->getOne( "SELECT `content_id` FROM `".BIT_DB_PREFIX."tiki_structures` WHERE `structure_id`=?", array( $structureId ) );
			}

			foreach( $contentIds as $contentId ) {
				// now we have the content ids - let the nuking begin
				$query = "DELETE FROM `".BIT_DB_PREFIX."bit_pigeonholes` WHERE `content_id` = ?";
				$result = $this->mDb->query( $query, array( $contentId ) );
				$query = "DELETE FROM `".BIT_DB_PREFIX."bit_pigeonhole_members` WHERE `parent_id` = ?";
				$result = $this->mDb->query( $query, array( $contentId ) );

				// remove all entries from content tables
				$this->mContentId = $contentId;
				if( LibertyAttachable::expunge() ) {
					$ret = TRUE;
					$this->mDb->CompleteTrans();
				} else {
					$this->mDb->RollbackTrans();
				}
			}

			// finally nuke the structure in tiki_structures
			$struct->s_remove_page( $this->mStructureId, FALSE );
		}
		return $ret;
	}

	/**
	* Generates the URL to this pigeonhole
	* @param $pContentId is the pigeonhole id we want to see
	* @return the link to display the page.
	*/
	function getDisplayUrl( $pContentId=NULL ) {
		global $gBitSystem;
		if( empty( $pContentId ) ) {
			$pContentId = $this->mContentId;
		}

		$rewrite_tag = $gBitSystem->isFeatureActive( 'feature_pretty_urls_extended' ) ? 'view/' : '';
		if( $gBitSystem->isFeatureActive( 'pretty_urls' ) || $gBitSystem->isFeatureActive( 'feature_pretty_urls_extended' ) ) {
			$baseUrl = PIGEONHOLES_PKG_URL.$rewrite_tag.$pContentId;
		} else {
			$baseUrl = PIGEONHOLES_PKG_URL.'view.php?content_id='.$pContentId;
		}

		return $baseUrl;
	}

	/**
	* Returns HTML link to display a pigeonhole
	* @param $pPigeonholeTitle is the pigeonhole we want to see
	* @return the link to display the page.
	*/
	function getDisplayLink( $pPigeonholeTitle=NULL ) {
		global $gBitSystem;
		if( empty( $pPigeonholeTitle ) && !empty( $this ) ) {
			$pPigeonholeTitle = $this->mInfo['title'];
		}

		$ret = $pPigeonholeTitle;
		if( $gBitSystem->isPackageActive( 'pigeonholes' ) ) {
			$ret = '<a href="'.$this->getDisplayUrl().'">'.$pPigeonholeTitle.'</a>';
		}

		return $ret;
	}
}
?>
