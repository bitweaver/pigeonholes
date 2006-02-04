{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_whats_related.tpl,v 1.2 2006/02/04 11:18:31 squareing Exp $ *}
{strip}
{if $gBitSystem->isFeatureActive( 'display_pigeonhole_members' ) and $relatedPigeon}
	{bitmodule title="$moduleTitle" name="whats_related"}
		{foreach from=$relatedPigeon item=pigeonItem}
			<h4>{$pigeonItem.title}</h4>
			<ul>
				{foreach from=$pigeonItem.members item=member}
					<li{if $gContent->mContentId == $member.content_id} class="highlight"{/if}><a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$member.content_id}">{$member.title}</a></li>
				{/foreach}
			</ul>
		{/foreach}
	{/bitmodule}
{/if}
{/strip}
