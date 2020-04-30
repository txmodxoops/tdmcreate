<{include file='db:mymodule2_header.tpl'}>

<div class='panel panel-<{$panel_type}>'>
<div class='panel-heading'>
<{$smarty.const._MA_MYMODULE2_TESTFIELDS_TITLE}></div>

<{foreach item=testfield from=$testfields}>
<div class='panel panel-body'>
<{include file='db:mymodule2_testfields_list.tpl' testfield=$testfield}>
<{if $testfield.count is div by $numb_col}>
<br>
<{/if}>

</div>

<{/foreach}>

</div>

<{include file='db:mymodule2_footer.tpl'}>
