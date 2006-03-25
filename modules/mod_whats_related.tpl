{* $Header: /cvsroot/bitweaver/_bit_pigeonholes/modules/mod_whats_related.tpl,v 1.4 2006/03/25 20:51:55 squareing Exp $ *}
{strip}
{if $gBitSystem->isFeatureActive( 'display_pigeonhole_members' ) and $modRelatedPigeon}
	{bitmodule title="$moduleTitle" name="whats_related"}
		{foreach from=$modRelatedPigeon item=pigeonItem}
			<h4>{$pigeonItem.title|escape}</h4>
			<ul>
				{foreach from=$pigeonItem.members item=member}
					<li{if $gContent->mContentId == $member.content_id} class="highlight"{/if}><a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$member.content_id}">{$member.title|escape}</a></li>
				{/foreach}
			</ul>
		{/foreach}
	{/bitmodule}
{/if}
{/strip}
