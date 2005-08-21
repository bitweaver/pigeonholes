<div class="floaticon">{bithelp}</div>

<div class="edit pigeonholes">
	<div class="header">
		<h1>{tr}Edit Categories{/tr}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}
		{if $gBitUser->hasPermission( 'bit_p_edit_pigeonholes' ) and ( $smarty.request.action eq 'create' or $smarty.request.action eq 'edit' or !$gPigeonholes->mContentId )}
			{include file="bitpackage:pigeonholes/edit_pigeonholes_inc.tpl"}
		{elseif $gBitUser->hasPermission( 'bit_p_edit_pigeonholes' )}
			{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" structure_id=`$gPigeonholes->mStructureId` action=create}
			<br />
		{/if}

		{include file="bitpackage:pigeonholes/view_structure_inc.tpl" edit=true}
	</div><!-- end .body -->
</div><!-- end .edit -->
