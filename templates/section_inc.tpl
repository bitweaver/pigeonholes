{strip}
{assign var=sid value=$subtree[ix].structure_id}

{if $gBitSystem->getConfig('pigeonholes_list_style') == "dynamic" && !$no_details}

	<h3 class="highlight"><a href="{$smarty.const.PIGEONHOLES_PKG_URL}{
			if $gBitSystem->isFeatureActive('pretty_urls')
				}structure/{
			elseif $gBitSystem->isFeatureActive('pretty_urls_extended')
				}view/structure/{
			else
				}view.php?structure_id={
			/if}{$subtree[ix].structure_id}">{$subtree[ix].title|escape}</a></h3>

	{if $pigeonList.$sid.members}
		{if $gBitSystem->isFeatureActive('pigeonholes_display_description')}
			{$pigeonList.$sid.parsed_data}
		{/if}

		<ul style="display:{if $gContent->mStructureId eq $subtree[ix].structure_id or $smarty.request.expand_all}block{else}none{/if}; padding:2em;" class="data">
			{foreach from=$pigeonList.$sid.members item=pigeonMember}
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
					{if $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
						&nbsp; {smartlink ititle="Remove From Category" ibiticon="icons/edit-delete" expand_all=$smarty.request.expand_all action=dismember structure_id=$sid parent_id=$pigeonMember.parent_id pigeonhole_content_id=$pigeonMember.content_id}
					{/if}
				</li>

				{assign var=ctg2 value=$pigeonMember.content_type_guid}
			{/foreach}

				</ul>
			</li>
		</ul>
	{/if}

	{if $gContent->mInfo.structure_id eq $subtree[ix].structure_id}
		{formfeedback hash=$memberFeedback}
	{/if}

{else}

	{* ======= very basic display of the pigoenhole structure ======= *}
	{if !$no_edit  and $gBitUser->hasPermission( 'p_pigeonholes_edit' )}
		<div class="floaticon">
			{smartlink ititle="Insert new Category" ifile="edit_pigeonholes.php" ibiticon="icons/document-new" structure_id=`$subtree[ix].structure_id` action=create}
			{smartlink ititle="Edit Category" ibiticon="icons/accessories-text-editor" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=edit}
			{smartlink ititle="Remove Category" ibiticon="icons/edit-delete" ifile="edit_pigeonholes.php" structure_id=$subtree[ix].structure_id action=remove}
		</div>
	{/if}

	{if $subtree[ix].content_id == $smarty.request.content_id || $subtree[ix].structure_id == $smarty.request.structure_id}
		{assign var=current value=1}
	{else}
		{assign var=current value=0}
	{/if}

	{if $current}<strong>{/if}
		<a href="{$smarty.const.PIGEONHOLES_PKG_URL}{
			if $gBitSystem->isFeatureActive('pretty_urls')
				}structure/{
			elseif $gBitSystem->isFeatureActive('pretty_urls_extended')
				}view/structure/{
			else
				}view.php?structure_id={
			/if}{$subtree[ix].structure_id}#members">{$subtree[ix].title|escape}</a>
		{if $current}</strong>{/if}{if $pigeonList.$sid.members_count}<small> ({$pigeonList.$sid.members_count}) </small>{/if}
	{biticon ipackage=liberty iname=spacer iforce=icon}

	{if !$no_details}
		<br />{$pigeonList.$sid.parsed_data}
	{/if}

{/if}
{/strip}
