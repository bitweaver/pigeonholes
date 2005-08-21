{strip}
<div class="listing pigeonholes">
	<div class="header">
		<h1>{tr}Categories Listing{/tr}</h1>
	</div>

	{* user sort related assigning *}
	{if $gBitSystem->isFeatureActive( 'display_name' ) eq login}
		{assign var=isort_author value=creator_user}
		{assign var=isort_editor value=modifier_user}
	{else}
		{assign var=isort_author value=creator_real_name}
		{assign var=isort_editor value=modifier_real_name}
	{/if}

	<div class="body">
		{minifind}

		<table class="data">
			<caption>{tr}Available Categories{/tr} <span class="total">[ {$pigeonCount} ]</span></caption>
			<tr>
				<th>{smartlink ititle="Title" isort=title page=$page idefault=1} / {smartlink ititle="Description" isort=data page=$page}</th>
				<th>{tr}Categories{/tr}</th>
				{if $gBitUser->hasPermission( 'bit_p_edit_pigeonholes' )}
					<th>{tr}Actions{/tr}</th>
				{/if}
			</tr>

			{foreach from=$pigeonList item=item}
				<tr class="{cycle values='odd,even'}">
					<td>
						<h2>{$item.display_link}</h2>
						{$item.data}
					</td>
					<td>{include file="bitpackage:pigeonholes/view_structure_inc.tpl" plain=true subtree=$item.subtree}</td>
					{if $gBitUser->hasPermission( 'bit_p_edit_pigeonholes' )}
						<td class="actionicon">
							{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" ibiticon="liberty/new" structure_id=`$item.structure_id` action=create}
							{smartlink ititle="Edit Category" ifile="edit_pigeonholes.php" ibiticon="liberty/edit" structure_id=`$item.structure_id`}
							{smartlink ititle="Change Structure" ifile="edit_structure.php" ibiticon="pigeonholes/organise" structure_id=`$item.structure_id`}
							{smartlink ititle="Remove Category" ifile="edit_pigeonholes.php" ibiticon="liberty/delete" action="remove" structure_id=`$item.structure_id`}
						</td>
					{/if}
				</tr>
			{foreachelse}
				<tr class="norecords">
					<td colspan="5">{tr}No Records Found{/tr}</td>
				</tr>
			{/foreach}
		</table>

		{libertypagination numPages=$numPages page=$curPage sort_mode=$sort_mode content_type=$contentSelect user_id=$user_id}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
