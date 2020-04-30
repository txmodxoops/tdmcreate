<!-- Header -->
<{include file='db:mymodule2_admin_header.tpl'}>

<{if $testfields_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_ID}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_TEXT}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_TEXTAREA}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_DHTML}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_CHECKBOX}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_YESNO}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_SELECTBOX}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_USER}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_COLOR}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_IMAGELIST}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_URLFILE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_UPLIMAGE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_UPLFILE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_TEXTDATESELECT}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_SELECTFILE}></th>
				<th class="center"><{$smarty.const._AM_MYMODULE2_TESTFIELD_STATUS}></th>
				<th class="center width5"><{$smarty.const._AM_MYMODULE2_FORM_ACTION}></th>
			</tr>
		</thead>
		<{if $testfields_count}>
		<tbody>
			<{foreach item=testfield from=$testfields_list}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class="center"><{$testfield.id}></td>
				<td class="center"><{$testfield.text}></td>
				<td class="center"><{$testfield.textarea}></td>
				<td class="center"><{$testfield.dhtml}></td>
				<td class="center"><img src="<{xoModuleIcons16}><{$testfield.checkbox}>.png" alt="testfields" /></td>
				<td class="center"><{$testfield.yesno_text}></td>
				<td class="center"><{$testfield.selectbox}></td>
				<td class="center"><{$testfield.user}></td>
				<td class="center"><span style='background-color:<{$testfield.color}>;'>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
				<td class="center"><img src="<{xoModuleIcons32}><{$testfield.imagelist}>" alt="testfields" /></td>
				<td class="center"><{$testfield.urlfile}></td>
				<td class="center"><img src="<{$mymodule2_upload_url}>/images/testfields/<{$testfield.uplimage}>" alt="testfields" style="max-width:100px" /></td>
				<td class="center"><{$testfield.uplfile}></td>
				<td class="center"><{$testfield.textdateselect}></td>
				<td class="center"><{$testfield.selectfile}></td>
				<td class="center"><img src="<{$modPathIcon16}>/status<{$testfield.status}>.png" alt="testfields" /></td>
				<td class="center  width5">
					<a href="testfields.php?op=edit&amp;tf_id=<{$testfield.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="testfields" /></a>
					<a href="testfields.php?op=delete&amp;tf_id=<{$testfield.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="testfields" /></a>
				</td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:mymodule2_admin_footer.tpl'}>
