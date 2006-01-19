{strip}
<div class="display pigeonholes">
	<div class="header">
		<h1>{tr}Categories Listing{/tr}</h1>
	</div>

	<div class="body">
		{if $list_style == "table"}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_details=true no_edit=true}
			{include file="bitpackage:pigeonholes/view_table_list.tpl" no_details=true no_edit=true}
		{else}
			{if !$smarty.request.expand_all and !( $smarty.request.action eq 'edit' or $smarty.request.action eq 'create' )}
				{smartlink ititle="Expand All" expand_all=1 structure_id=$gPigeonholes->mStructureId}
			{/if}

			{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
		{/if}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
