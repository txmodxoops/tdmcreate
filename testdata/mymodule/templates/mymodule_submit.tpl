<{include file='db:mymodule_header.tpl'}>

<div class='mymodule-tips'>
	<ul>
		<li><{$smarty.const._MA_MYMODULE_SUBMIT_SUBMITONCE}></li>
		<li><{$smarty.const._MA_MYMODULE_SUBMIT_ALLPENDING}></li>
		<li><{$smarty.const._MA_MYMODULE_SUBMIT_DONTABUSE}></li>
		<li><{$smarty.const._MA_MYMODULE_SUBMIT_TAKEDAYS}></li>
	</ul>
</div>
<{if $message_error != ''}>
	<div class='errorMsg'><{$message_error}></div>
<{/if}>
<div class='mymodule-submitform'>
	<{$form}>
</div>

<{include file='db:mymodule_footer.tpl'}>
