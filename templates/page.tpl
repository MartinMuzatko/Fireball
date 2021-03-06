{include file='documentHeader'}

<head>
    <title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != $page->title}{$page->getTitle()|language} - {/if}{PAGE_TITLE|language}</title>
    
    {include file='headInclude' application='wcf' sandbox=false}
	{@$page->getLayout()}
    <script data-relocate="true" src="{@$__wcf->getPath()}js/WCF.Moderation{if !ENABLE_DEBUG_MODE}.min{/if}.js?v={@$__wcfVersion}"></script>
	
    <link rel="canonical" href="{$page->getLink()}" />
</head>

<body id="tpl{$templateName|ucfirst}" data-page-id="{$page->pageID}">
{if $sidebarList|count || $page->showSidebar}
{capture assign='sidebar'}
	{foreach from=$sidebarList item=content}
        
            {foreach from=$content->getSections() item=section}
				{if $section->getObjectType() == 'de.codequake.cms.section.type.dashboard'}
					{@$section->getOutput()|language}
				{else}
                <fieldset {if $section->cssID != ''}id="{$section->cssID}"{/if} class="dashboardBox{if $section->cssClasses != ''} {$section->cssClasses}{/if}">
                    {@$section->getOutput()|language}
                </fieldset>
				{/if}
            {/foreach}
    {/foreach}
	
	{if $page->showSidebar == 1}
		{@$__boxSidebar}
	{/if}
{/capture}
{/if}

{include file='header' sidebarOrientation=$page->sidebarOrientation}


<header class="boxHeadline">
{if $__wcf->getPageMenu()->getLandingPage()->menuItem == $page->title}

		<h1>{PAGE_TITLE|language}</h1>
		{hascontent}<p>{content}{PAGE_DESCRIPTION|language}{/content}</p>{/hascontent}

{else}
	<h1>{$page->getTitle()|language}</h1>
	<p>{$page->description|language}</p>
{/if}
</header>
{include file='userNotice'}

    {foreach from=$bodyList item=content}
		{assign var=contentTag value=$content->type}
        <{$contentTag} {if $content->cssID != ''}id="{$content->cssID}"{/if} {if $content->cssClasses != ''}class="{$content->cssClasses}"{/if}>
            {foreach from=$content->getSections() item=section}
                <{if $contentTag != 'div'}li{else}div{/if} {if $section->cssID != ''}id="{$section->cssID}"{/if} {if $section->cssClasses != ''} class="{$section->cssClasses}"{/if}>
                    {@$section->getOutput()|language}
                </{if $contentTag != 'div'}li{else}div{/if}>
            {/foreach}
        </{$contentTag}>
    {/foreach}

	{if $page->isCommentable}
    {include file='pageCommentList' application='cms'}
	{/if}
{include file='footer' sandbox=false}
</body>
</html>
