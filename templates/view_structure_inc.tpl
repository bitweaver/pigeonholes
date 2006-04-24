{strip}
<ul>
	<li>
		{section name=ix loop=$subtree}
			{if $subtree[ix].pos eq ''}
				{include file="bitpackage:pigeonholes/section_inc.tpl"}
			{else}
				{if $subtree[ix].first}<ul>{else}</li>{/if}
				{if $subtree[ix].last}</ul>{else}
					<li>
						<div class="floaticon">
							{if $gBitUser->hasPermission( 'p_pigeonholes_edit' ) && !$no_edit}
								{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" ibiticon="liberty/new" structure_id=`$subtree[ix].structure_id` action=create}
								{smartlink ititle="Edit Category" ifile="edit_pigeonholes.php" ibiticon="liberty/edit" structure_id=`$subtree[ix].structure_id` action="edit"}
								{smartlink ititle="Remove Category" ifile="edit_pigeonholes.php" ibiticon="liberty/delete" action="remove" structure_id=`$subtree[ix].structure_id`}
							{/if}
						</div>
						{include file="bitpackage:pigeonholes/section_inc.tpl"}
						{biticon ipackage=liberty iname=spacer}
				{/if}
			{/if}
		{/section}
	</li>
</ul><!-- end outermost .toc -->
{/strip}
