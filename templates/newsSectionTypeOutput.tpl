{if $type == 1}
<ul class="plainList">
    {foreach from=$newsList item=news}
    <li>
        <div class="box24">
             {if $news->getImage() != null}
				<a class="framed" href="{link controller='News' object=$news application='cms'}{/link}">
					{@$news->getImage()->getImageTag('24')}
				</a>
			{else}
				<a class="framed" href="{link controller='User' object=$news->getUserProfile()}{/link}">
					{@$news->getUserProfile()->getAvatar()->getImageTag(24)}
			</a>
				{/if}
            <div>
                <div class="containerHeadline">
                    <h3><a class="newsLink" data-news-id="{$news->newsID}" href="{link controller='News' object=$news application='cms'}{/link}">{$news->getTitle()}</a></h3>
                    <small>
                        <span class="username">
                                                <a class="userLink" data-user-id="{$news->userID}" href="{link controller='User' object=$news->getUserProfile()}{/link}">
                                                    {$news->username}
                                                </a>
                          </span>
                                            - {@$news->time|time}
                    </small>
                </div>
            </div>
        </div>
    </li>
    {/foreach}
</ul>
{else}
{assign var=objects value=$newsList}
{include file='newsListing' application='cms'}
{/if}