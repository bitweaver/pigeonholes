{foreach from=$pigeonList item=pigeonItem}
	{if $pigeonItem.members}
		<hr />
		<a name="members"></a>
		<h2>{$gContent->getTitle()}</h2>
		<p>
			{if !empty($pigeonItem.parsed_data) && $gBitSystem->isFeatureActive('pigeonholes_display_description')}
				{$pigeonItem.parsed_data}
				<br />
			{/if}
			<small>{tr}This category contains {$pigeonItem.members_count} item(s){/tr}</small>
		</p>
		{* calculate column width *}
		{foreach from=$pigeonItem.members item=pigeonColumn}
			{counter assign=columns}
		{/foreach}
		{math equation="100 / x" x=$columns assign=width format="%u"}
		<table class="data" summary="Category listing"><tr>
			{foreach from=$pigeonItem.members item=pigeonColumn}
				<td style="vertical-align:top; width:{$width}%;">
					{foreach from=$pigeonColumn item=members key=index}
						<h3 class="section">{$index}</h3>
						<ul>
							{foreach from=$members item=member}
								<li>
									{assign var=size value=$gBitSystem->getConfig('pigeonholes_member_thumb')}
									<a href="{$member.display_url}">
										{if $gBitSystem->isFeatureActive( 'pigeonholes_member_thumb' ) && $member.thumbnail_url.$size}
											<img src="{$member.thumbnail_url.$size}" alt="{$member.title|escape}" title="{$member.title|escape}" /><br />
										{/if}
										{$member.title|escape}{if $gBitSystem->isFeatureActive( 'pigeonholes_display_content_type' )} &nbsp; <small>{tr}{$member.content_name}{/tr}</small>{/if}
									</a>
								</li>
							{/foreach}
						</ul>
					{/foreach}
				</td>
			{/foreach}
		</tr></table>
	{/if}
{/foreach}
