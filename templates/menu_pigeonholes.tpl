{strip}
<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="{$packageMenuClass}">
	{if $gBitUser->hasPermission( 'p_pigeonholes_create' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?action=create">{booticon iname="icon-file" iexplain="Create Category" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_view' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}list.php">{booticon iname="icon-list" iexplain="List Categories" ilocation=menu}</a></li>
		{if $gContent->mStructureId and $gContent->mType.content_type_guid == $smarty.const.PIGEONHOLES_CONTENT_TYPE_GUID}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}{if $gBitSystem->isFeatureActive('pretty_urls_extended')}view/structure/{else}view.php?structure_id={/if}{$gContent->mStructureId}">{booticon iname="icon-search"   iexplain="View Category" ilocation=menu}</a></li>
			{if $gBitUser->hasCreatePermission()}
				<li><a class="head" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('structure_id')}&amp;action=edit">{booticon iname="icon-edit"   iexplain="Edit Category" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('root_structure_id')}&amp;action=create">{biticon iname="insert-object" iexplain="Insert Sub-Category" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_structure.php?structure_id={$gContent->getField('structure_id')}">{booticon iname="icon-recycle"   iexplain="Change Structure" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('structure_id')}&amp;action=remove">{booticon iname="icon-trash" iexplain="Delete Category" ilocation=menu}</a></li>
			{/if}
		{/if}
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_insert_member' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}assign_content.php">{booticon iname="icon-paper-clip" iexplain="Assign Content" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
