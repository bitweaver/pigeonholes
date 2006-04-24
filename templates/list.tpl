{strip}
<div class="listing pigeonholes">
	<div class="header">
		<h1>{tr}Categories Listing{/tr}</h1>
	</div>

	{* user sort related assigning *}
	{if $gBitSystem->getConfig( 'users_display_name' ) eq 'login'}
		{assign var=isort_author value=creator_user}
		{assign var=isort_editor value=modifier_user}
	{else}
		{assign var=isort_author value=creator_real_name}
		{assign var=isort_editor value=modifier_real_name}
	{/if}

	<div class="body">
		{minifind}
		<div class="navbar">
			<ul>
				<li>{biticon ipackage=liberty iname=sort iexplain=sort}</li>
				<li>{smartlink ititle="Title" isort=title idefault=1 icontrol=$listInfo}</li>
				<li>{smartlink ititle="Description" isort=data icontrol=$listInfo}</li>
			</ul>
		</div>
		<div class="clear"></div>

		<h1>{tr}Categories{/tr} <span class="total">[ {$listInfo.total_records|default:0} ]</span></h1>

		{foreach from=$pigeonList item=item}
			{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
				<div class="floaticon">
					{smartlink ititle="Change Structure" ifile="edit_structure.php" ibiticon="pigeonholes/organise" structure_id=`$item.structure_id`}
					{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" ibiticon="liberty/new" structure_id=`$item.structure_id` action=create}
					{smartlink ititle="Edit Category" ifile="edit_pigeonholes.php" ibiticon="liberty/edit" structure_id=`$item.structure_id` action="edit"}
					{smartlink ititle="Remove Category" ifile="edit_pigeonholes.php" ibiticon="liberty/delete" action="remove" structure_id=`$item.structure_id`}
				</div>
			{/if}
			<h2>{$item.display_link}</h2>
			{$item.data}
			{include file="bitpackage:pigeonholes/view_structure_inc.tpl" no_edit=true subtree=$item.subtree no_details=true}
			<hr />
		{foreachelse}
			<div class="norecords">
				<td colspan="5">{tr}No Records Found{/tr}</td>
			</div>
		{/foreach}

		{pagination}
	</div><!-- end .body -->
</div><!-- end .liberty -->
{/strip}
