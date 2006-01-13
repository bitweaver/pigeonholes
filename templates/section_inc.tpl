{strip}
{$list_style}
{if $list_style == "table"}

	{* ======= this display method requires "alphabetisation" ======= *}
	<h3><a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$subtree[ix].structure_id}">{$subtree[ix].title}</a></h3>

	{foreach from=$pigeonList item=pigeonItem}
		{if $pigeonItem.structure_id eq $subtree[ix].structure_id && $pigeonItem.members}
			{$pigeonItem.data|escape}
			<table class="data"><tr>
				{*use a fixed number here for now, we can change this eventually with a preference*}
				{math equation="100 / x" x=3 assign=width format="%u"}
				{foreach from=$pigeonItem.members item=pigeonColumn}
					<td style="vertical-align:top; width:{$width}%;">
						{foreach from=$pigeonColumn item=members key=index}
							<h2>{$index}</h2>
							<ul>
								{foreach from=$members item=member}
									<li>
										<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$member.content_id}">{$member.title}</a>
										<br />
										<small>{$member.content_type_description}</small>
									</li>
								{/foreach}
							</ul>
						{/foreach}
					</td>
				{/foreach}
			</tr></table>
		{/if}
	{/foreach}

{elseif $list_style == "dynamic"}

	{* ======= crazy display for only few category memebers - only display method that allows custom sorting ======= *}
	{if $gPigeonholes->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}
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

		<a href="javascript:icntoggle('sid{$subtree[ix].structure_id}');">
			{biticon ipackage=liberty iname=$iname id=sid`$subtree[ix].structure_id`img"} {$subtree[ix].title}
		</a> &nbsp;

		<script type="text/javascript">
			setfoldericonstate('sid{$subtree[ix].structure_id}');
		</script>

		{foreach from=$pigeonList item=pigeonItem}
			{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
				<small> {tr}{$pigeonItem.members_count} Item(s){/tr} </small>
			{/if}
		{/foreach}

		<noscript>
			<div style="padding-left:18px;" class="small"><a href="{$smarty.const.PIGEONHOLES_PKG_URL}{if $edit}edit_pigeonholes{else}index{/if}.php?structure_id={$subtree[ix].structure_id}">{tr}Expand{/tr}</a></div>
		</noscript>
	</div>

	{foreach from=$pigeonList item=pigeonItem}
		{if $pigeonItem.structure_id eq $subtree[ix].structure_id}
			<small>{$pigeonItem.data|escape}</small>

			{if $pigeonItem.members}
				<ul id="sid{$subtree[ix].structure_id}" style="display:{if $gPigeonholes->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}block{else}none{/if};" class="data">
					{foreach from=$pigeonItem.members item=pigeonMember}
						{if $gBitSystem->isFeatureActive( 'custom_member_sorting' )}
							<li>
								{if $edit && $gBitSystem->isFeatureActive( 'custom_member_sorting' )}
									{if $pigeonMember.pos ne 1}
										{smartlink ititle="Move item up" ibiticon="liberty/nav_up" expand_all=$smarty.request.expand_all ifile="edit_pigeonholes.php" action=move orientation=north structure_id=$pigeonItem.structure_id parent_id=$pigeonItem.content_id member_id=$pigeonMember.content_id}
									{else}
										{biticon ipackage="liberty" iname="spacer"}
									{/if}

									{if $pigeonMember.pos ne $pigeonItem.members_count}
										{smartlink ititle="Move item down" ibiticon="liberty/nav_down" expand_all=$smarty.request.expand_all ifile="edit_pigeonholes.php" action=move orientation=south structure_id=$pigeonItem.structure_id parent_id=$pigeonItem.content_id member_id=$pigeonMember.content_id}
									{else}
										{biticon ipackage="liberty" iname="spacer"}
									{/if}
								{/if}
								&nbsp; <a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$pigeonMember.content_id}">{$pigeonMember.title}</a> &nbsp;
								{if $edit}
									{smartlink ititle="Remove Item" ibiticon="liberty/delete_small" expand_all=$smarty.request.expand_all ifile="edit_pigeonholes.php" action=demember structure_id=$pigeonItem.structure_id parent_id=$pigeonMember.content_id content_id=$pigeonItem.content_id}
								{/if}
							</li>
						{else}
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
								<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$pigeonMember.content_id}">{$pigeonMember.title}</a>
								{if $edit}
									&nbsp; {smartlink ititle="Remove Item" ibiticon="liberty/delete_small" expand_all=$smarty.request.expand_all ifile="edit_pigeonholes.php" action=demember structure_id=$pigeonItem.structure_id parent_id=$pigeonMember.content_id content_id=$pigeonItem.content_id}
								{/if}
							</li>

							{assign var=ctg2 value=$pigeonMember.content_type_guid}
						{/if}
					{/foreach}

					{if !$gBitSystem->isFeatureActive( 'custom_member_sorting' )}
							</ul>
						</li>
					{/if}
				</ul>
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

	<h3><a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$subtree[ix].structure_id}">{$subtree[ix].title}</a></h3>

	{foreach from=$pigeonList item=pigeonItem}
		{if $pigeonItem.structure_id eq $subtree[ix].structure_id and $pigeonItem.members_count}
			 {tr}{$pigeonItem.members_count} Item(s){/tr}
		{/if}
	{/foreach}
{/if}
{/strip}
