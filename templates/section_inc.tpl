{strip}
{if $gBitSystem->getConfig('pigeonholes_list_style') == "dynamic" && !$no_details}

	<h3 class="highlight"><a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$subtree[ix].structure_id}">{$subtree[ix].title|escape}</a></h3>

	{foreach from=$pigeonList item=pigeonItem}
		{if $pigeonItem.structure_id eq $subtree[ix].structure_id}

			{if $pigeonItem.members}
				{$pigeonItem.data|escape}
				<ul style="display:{if $gContent->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}block{else}none{/if}; padding:2em;" class="data">
					{foreach from=$pigeonItem.members item=pigeonMember}
						{assign var=ctg1 value=$pigeonMember.content_type_guid}

						{* close off the content type <ul> *}
						{if $ctg1 ne $ctg2 and $ctg2}
								</ul>
							</li>
						{/if}

						{* open the content type <ul> *}
						{if $ctg1 ne $ctg2}
							<li>{$gLibertySystem->mContentTypes.$ctg1.content_description}
								<ul>
						{/if}

						<li>
							<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$pigeonMember.content_id}">{$pigeonMember.title|escape}</a>
							{if $gBitUser->hasPermission( 'edit_pigeonholes' )}
								&nbsp; {smartlink ititle="Remove Item" ibiticon="liberty/delete_small" expand_all=$smarty.request.expand_all action=dismember structure_id=$pigeonItem.structure_id parent_id=$pigeonMember.content_id content_id=$pigeonItem.content_id}
							{/if}
						</li>

						{assign var=ctg2 value=$pigeonMember.content_type_guid}
					{/foreach}

						</ul>
					</li>
				</ul>
			{/if}
		{/if}
	{/foreach}

	{if $gContent->mInfo.structure_id eq $subtree[ix].structure_id}
		{formfeedback hash=$memberFeedback}
	{/if}

{else}

	{* ======= very basic display of the pigoenhole structure ======= *}
	{if !$no_edit}
		<div class="floaticon">
			{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" ibiticon="liberty/new" structure_id=`$subtree[ix].structure_id` action=create}
			{smartlink ititle="Edit Category" ibiticon="liberty/edit" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=edit}
			{smartlink ititle="Remove Category" ibiticon="liberty/delete" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=remove}
		</div>
	{/if}

	{if $subtree[ix].content_id == $smarty.request.content_id || $subtree[ix].structure_id == $smarty.request.structure_id}
		{assign var=current value=1}
	{else}
		{assign var=current value=0}
	{/if}

	{if $current}<strong>{/if}
		<a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$subtree[ix].structure_id}">{$subtree[ix].title|escape}</a>
	{if $current}</strong>{/if}
	{biticon ipackage=liberty iname=spacer}

	{if !$no_details}
		{foreach from=$pigeonList item=pigeonItem}
			{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
				<br />{$pigeonItem.data|escape} <small> [ {tr}{$pigeonItem.members_count|default:0} Item(s){/tr} ] </small>
			{/if}
		{/foreach}
	{/if}

{/if}
{/strip}
