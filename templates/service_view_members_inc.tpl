{strip}
{if $gBitSystem->isFeatureActive( 'pigeonholes_display_members' ) and $pigeonData}
	<div class="service pigeonholes">
		{if $gBitSystem->getConfig('pigeonholes_limit_member_number') == 0}
			<h2>{tr}Categories{/tr}</h2>
			{foreach from=$pigeonData item=pigeonItem}
				{$pigeonItem.display_path}
				{if $gBitSystem->isFeatureActive( 'pigeonholes_display_description' )}
					: {$pigeonItem.parsed_data} <br />
				{else}
					&nbsp; &bull; &nbsp;
				{/if}
			{/foreach}
		{else}
			<h2>{tr}Related Items{/tr}</h2>
			{foreach from=$pigeonData item=pigeonItem}
				<h3>{$pigeonItem.display_path}</h3>

				<p>
					{if $pigeonItem.parsed_data and $gBitSystem->isFeatureActive( 'pigeonholes_display_description' )}
						{$pigeonItem.parsed_data}<br />
					{/if}

					{* reset vars *}
					{counter start=0 assign=member_count}
					{assign var=more value=0}

					{foreach from=$pigeonItem.members item=member name=members}
						{assign var=ctg1 value=$member.content_type_guid}

						{if $ctg1 ne $ctg2 && $gBitSystem->isFeatureActive( 'pigeonholes_display_content_type' )}{if $ctg2}<br />{/if}{$gLibertySystem->getContentTypeName($ctg1)}:&nbsp;{/if}

						{if !$gBitSystem->getConfig('pigeonholes_limit_member_number') or $member_count lt $gBitSystem->getConfig('pigeonholes_limit_member_number')}
							{if $serviceHash.content_id == $member.content_id}<strong>{/if}
								{$member.display_link}
								{if $serviceHash.content_id == $member.content_id}</strong>{/if}
							{if !$smarty.foreach.members.last}&nbsp; &bull; &nbsp;{/if}
						{else}
							{assign var=more value=1}
						{/if}

						{counter assign=member_count}
						{assign var=ctg2 value=$member.content_type_guid}
					{/foreach}

					{if $more eq 1}
						{if $gBitSystem->isFeatureActive('pretty_urls_extended')}
							<a href="{$smarty.const.PIGEONHOLES_PKG_URL}view/structure/{$pigeonItem.structure_id}">[ &hellip; ]</a>
						{else}
							<a href="{$smarty.const.PIGEONHOLES_PKG_URL}view.php?structure_id={$pigeonItem.structure_id}">[ &hellip; ]</a>
						{/if}
					{/if}
				</p>
				{* reset the ctg2 value *}
				{assign var=ctg2 value=''}
			{/foreach}
		{/if}
	</div>
{/if}
{/strip}
