{capture assign='pageTitle'}{lang}cms.acp.stylesheet.{@$action}{/lang}{/capture}
{include file='header'}

<header class="boxHeadline">
	<h1>{lang}cms.acp.stylesheet.{$action}{/lang}</h1>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<div class="contentNavigation">
    <nav>
        <ul>
            <li><a href="{link application='cms' controller='StylesheetList'}{/link}" title="{lang}cms.acp.menu.link.cms.stylesheet.list{/lang}" class="button"><span class="icon icon24 icon-list"></span> <span>{lang}cms.acp.menu.link.cms.stylesheet.list{/lang}</span></a></li>
            {event name='contentNavigationButtons'}
        </ul>
    </nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='StylesheetAdd' application='cms'}{/link}{else}{link controller='StylesheetEdit' id=$sheetID application='cms'}{/link}{/if}">
    <div class="container containerPadding marginTop">  
        <fieldset>
			<legend>{lang}cms.acp.stylesheet.general{/lang}</legend>
				<dl>
					<dt><label for="title">{lang}cms.acp.stylesheet.title{/lang}</label></dt>
					<dd><input type="text" name="title" id="title" required="required" value="{$title}"/></dd>
				</dl>
		</fieldset>
		<fieldset class="marginTop">
				<legend>{lang}cms.acp.stylesheet.less{/lang}</legend>
				
				<dl class="wide">
					<dd>
						<textarea id="less" rows="20" cols="40" name="less">{$less}</textarea>
						<small>{lang}cms.acp.stylesheet.less.description{/lang}</small>
					</dd>
				</dl>
		</fieldset>
    </div>  
    {include file='codemirror' codemirrorMode='less' codemirrorSelector='#less'}
    <div class="formSubmit">
		    <input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
			 {@SECURITY_TOKEN_INPUT_TAG}
	    </div>
</form>

{include file='footer'}