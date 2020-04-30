<{include file='db:mymodule2_header.tpl'}>

<div class='mymodule2-tips'>
	<ul>
		<li><{$smarty.const._MA_MYMODULE2_SUBMIT_SUBMITONCE}></li>
		<li><{$smarty.const._MA_MYMODULE2_SUBMIT_ALLPENDING}></li>
		<li><{$smarty.const._MA_MYMODULE2_SUBMIT_DONTABUSE}></li>
		<li><{$smarty.const._MA_MYMODULE2_SUBMIT_TAKEDAYS}></li>
	</ul>
</div>
<{if $message_error != ''}>
	<div class='errorMsg'><{$message_error}></div>
<{/if}>
<div class='mymodule2-submitform'>
	<{$form}>
</div>

<{include file='db:mymodule2_footer.tpl'}>
