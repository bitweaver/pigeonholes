{strip}
{if $gBitSystem->isFeatureActive( 'display_pigeonhole_members' ) and $pigeonData}
	<div class="display pigeonholes">
		<h2>{tr}Related Items{/tr}</h2>
		{foreach from=$pigeonData item=pigeonItem}
			<div class="box">
				<h3>{$pigeonItem.display_path}</h3>

				<div class="boxcontent">
					{if $pigeonItem.data and $gBitSystem->isFeatureActive( 'display_pigeonhole_description' )}
						{$pigeonItem.data}<br />
					{/if}

					{* reset vars *}
					{counter start=0 assign=member_count}
					{assign var=more value=0}

					{if $gBitSystem->isFeatureActive( 'custom_member_sorting' )}
						{foreach from=$pigeonItem.members item=member}
							{if !$gBitSystemPrefs.limit_member_number or $member_count lt $gBitSystemPrefs.limit_member_number}
								<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$member.content_id}">{$member.title}</a> &bull;&nbsp;
							{else}
								{assign var=more value=1}
							{/if}
							{counter assign=member_count}
						{/foreach}
					{else}
						{foreach from=$pigeonItem.members item=member}
							{assign var=ctg1 value=$member.content_type_guid}

							{if $ctg1 ne $ctg2}{if $ctg2}<br />{/if}{$gLibertySystem->mContentTypes.$ctg1.content_description}: {/if}

							{if !$gBitSystemPrefs.limit_member_number or $member_count lt $gBitSystemPrefs.limit_member_number}
								<a href="{$smarty.const.BIT_ROOT_URL}index.php?content_id={$member.content_id}">{$member.title}</a> &bull;&nbsp;
							{else}
								{assign var=more value=1}
							{/if}

							{counter assign=member_count}
							{assign var=ctg2 value=$member.content_type_guid}
						{/foreach}
					{/if}

					{if $more eq 1}
						<a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$pigeonItem.structure_id}">[ &hellip; ]</a>
					{/if}
				</div>
			</div>
			{* reset the ctg2 value *}
			{assign var=ctg2 value=''}
		{/foreach}
	</div>
{/if}
{/strip}
