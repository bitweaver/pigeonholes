{strip}
<ul>
	{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?action=create">{biticon iname="document-new" iexplain="Create Category" ilocation=menu}</a></li>
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_view' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}list.php">{biticon iname="format-justify-fill" iexplain="List Categories" ilocation=menu}</a></li>
		{if $gContent->mStructureId and $gContent->mType.content_type_guid == $smarty.const.PIGEONHOLES_CONTENT_TYPE_GUID}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}{if $gBitSystem->isFeatureActive('pretty_urls_extended')}view/structure/{else}view.php?structure_id={/if}{$gContent->mStructureId}">{biticon iname=edit-find iexplain="View Category" ilocation=menu}</a></li>
			{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
				<li><a class="head" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('structure_id')}&amp;action=edit">{biticon iname="accessories-text-editor" iexplain="Edit Category" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('root_structure_id')}&amp;action=create">{biticon iname="insert-object" iexplain="Insert Sub-Category" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_structure.php?structure_id={$gContent->getField('structure_id')}">{biticon iname="view-refresh" iexplain="Change Structure" ilocation=menu}</a></li>
				<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}edit_pigeonholes.php?structure_id={$gContent->getField('structure_id')}&amp;action=remove">{biticon iname="edit-delete" iexplain="Delete Category" ilocation=menu}</a></li>
			{/if}
		{/if}
	{/if}

	{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
		<li><a class="item" href="{$smarty.const.PIGEONHOLES_PKG_URL}assign_content.php">{biticon iname="mail-attachment" iexplain="Assign Content" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
