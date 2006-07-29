{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?action=create">{biticon ipackage=liberty iname=new iexplain="Create Category" iforce="icon"} {tr}Create Category{/tr}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_view' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}list.php">{biticon ipackage=liberty iname=list iexplain="List Categories" iforce="icon"} {tr}List Categories{/tr}</a></li>
		{if $gContent->mStructureId and $gContent->mType.content_type_guid == $smarty.const.PIGEONHOLES_CONTENT_TYPE_GUID}
			<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$gContent->mStructureId}">{biticon ipackage=liberty iname=spacer iexplain="" iforce="icon"} {tr}View Category{/tr}</a></li>
			{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
				<li><a class="head" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->mInfo.structure_id}&amp;action=edit">{biticon ipackage=liberty iname=edit iexplain="Edit Category" iforce="icon"} {tr}Edit Category{/tr}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->mInfo.root_structure_id}&amp;action=create">{biticon ipackage=liberty iname=insert iexplain="Insert Category" iforce="icon"} {tr}Insert Category{/tr}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_structure.php?structure_id={$gContent->mInfo.structure_id}">{biticon ipackage=pigeonholes iname=organise iexplain="Change Structure" iforce="icon"} {tr}Change Structure{/tr}</a></li>
			{/if}
		{/if}
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}assign_content.php">{biticon ipackage=liberty iname=assign iexplain="Assign Content" iforce="icon"} {tr}Assign Content{/tr}</a></li>
	{/if}
</ul>
{/strip}
