{strip}
{if $gBitSystemPrefs.pigeonholes_list_style == "dynamic"}

	{* ======= crazy display for only few category memebers - only display method that allows custom sorting ======= *}
	{if $gContent->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}
		{assign var=iname value=Expanded}
	{else}
		{assign var=iname value=Collapsed}
	{/if}

	<div class="highlight">
		{if $edit}
			<div class="floaticon">
				{smartlink ititle="Edit Category" ibiticon="liberty/edit" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=edit}
				{smartlink ititle="Remove Category" ibiticon="liberty/delete" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=remove}
			</div>
		{/if}

		<h3>
			<a href="javascript:icntoggle('sid{$subtree[ix].structure_id}');">
				{biticon ipackage=liberty iname=$iname id=sid`$subtree[ix].structure_id`img"} {$subtree[ix].title|escape}
				{foreach from=$pigeonList item=pigeonItem}
					{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
						<small> &nbsp; &nbsp; [ {$pigeonItem.members_count} ]</small>
					{/if}
				{/foreach}
			</a> &nbsp;
		</h3>

		<script type="text/javascript">
			setfoldericonstate('sid{$subtree[ix].structure_id}');
		</script>

		<noscript>
			<div style="padding-left:18px;" class="small"><a href="{$smarty.const.PIGEONHOLES_PKG_URL}{if $edit}edit_pigeonholes{else}index{/if}.php?structure_id={$subtree[ix].structure_id}">{tr}Expand{/tr}</a></div>
		</noscript>
	</div>

	{foreach from=$pigeonList item=pigeonItem}
		{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
			{$pigeonItem.data|escape}

			{if $pigeonItem.members}
				<ul id="sid{$subtree[ix].structure_id}" style="display:{if $gContent->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}block{else}none{/if}; padding:2em;" class="data">
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
			{else}
				<div id="sid{$subtree[ix].structure_id}" class="norecords">{tr}No Records Found{/tr}</div>
			{/if}
		{/if}
	{/foreach}

{else}

	{* ======= very basic display of the pigoenhole structure ======= *}
	{if !$no_edit}
		<div class="floaticon">
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

	{if !$no_details}
		{foreach from=$pigeonList item=pigeonItem}
			{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
				<br />{$pigeonItem.data|escape} <small> [ {tr}{$pigeonItem.members_count|default:0} Item(s){/tr} ] </small>
			{/if}
		{/foreach}
	{/if}
{/if}
{/strip}
