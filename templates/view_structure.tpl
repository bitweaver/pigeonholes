{strip}
<div class="display pigeonholes">
	<div class="header">
		<h1>{tr}Category Listing{/tr}</h1>
	</div>

	<div class="body">
		{if $gBitSystem->getConfig('pigeonholes_list_style') == "table"}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
			{formfeedback hash=$memberFeedback}
			{include file="bitpackage:pigeonholes/view_table_inc.tpl" no_details=true no_edit=true}
		{else}
			{if !$smarty.request.expand_all and !( $smarty.request.action eq 'edit' or $smarty.request.action eq 'create' )}
				{smartlink ititle="Expand All" expand_all=1 structure_id=$gContent->mStructureId}
			{/if}

			{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
		{/if}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
