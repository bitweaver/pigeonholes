{foreach from=$pigeonList item=pigeonItem}
	{if $pigeonItem.members}
		<hr />
		<a name="members"></a>
		<h2>{$gContent->getTitle()}</h2>
		<p>
			{$pigeonItem.data|escape}
			<br />
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
									{$member.display_link}
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
