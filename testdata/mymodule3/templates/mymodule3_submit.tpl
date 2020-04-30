<{include file='db:mymodule3_header.tpl'}>

<div class='mymodule3-tips'>
	<ul>
		<li><{$smarty.const._MA_MYMODULE3_SUBMIT_SUBMITONCE}></li>
		<li><{$smarty.const._MA_MYMODULE3_SUBMIT_ALLPENDING}></li>
		<li><{$smarty.const._MA_MYMODULE3_SUBMIT_DONTABUSE}></li>
		<li><{$smarty.const._MA_MYMODULE3_SUBMIT_TAKEDAYS}></li>
	</ul>
</div>
<{if $message_error != ''}>
	<div class='errorMsg'><{$message_error}></div>
<{/if}>
<div class='mymodule3-submitform'>
	<{$form}>
</div>

<{include file='db:mymodule3_footer.tpl'}>
