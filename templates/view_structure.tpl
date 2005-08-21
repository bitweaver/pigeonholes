{strip}
<div class="display pigeonholes">
	<div class="header">
		<h1>{tr}Categories Listing{/tr}</h1>
	</div>

	<div class="body">
		{if !$smarty.request.expand_all and !( $smarty.request.action eq 'edit' or $smarty.request.action eq 'create' )}
			{smartlink ititle="Expand All" expand_all=1 structure_id=$gPigeonholes->mStructureId}
		{/if}

		{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
