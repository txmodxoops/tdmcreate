<{include file="db:mymodule2_header.tpl"}>
<table class="mymodule2">
    <thead class="outer">
        <tr class="head">
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_ID}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_TEXT}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_TEXTAREA}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_DHTML}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_CHECKBOX}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_YESNO}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_SELECTBOX}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_USER}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_COLOR}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_IMAGELIST}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_URLFILE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_UPLIMAGE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_UPLFILE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_TEXTDATESELECT}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE2_TF_SELECTFILE}></th>
        </tr>
    </thead>
    <tbody>
        <{foreach item=list from=$testfields}>
            <tr class="<{cycle values='odd, even'}>">
                <td class="center"><{$list.id}></td>
                <td class="center"><{$list.text}></td>
                <td class="center"><{$list.textarea}></td>
                <td class="center"><{$list.dhtml}></td>
                <td class="center"><{$list.checkbox}></td>
                <td class="center"><{$list.yesno}></td>
                <td class="center"><{$list.selectbox}></td>
                <td class="center"><{$list.user}></td>
                <td class="center"><span style="background-color: #<{$list.color}>;">		</span></td>
                <td class="center"><img src="<{xoModuleIcons32}><{$list.imagelist}>" alt="testfields"></td>
                <td class="center"><{$list.urlfile}></td>
                <td class="center"><img src="<{$mymodule2_upload_url}>/images/testfields/<{$list.uplimage}>" alt="testfields"></td>
                <td class="center"><{$list.uplfile}></td>
                <td class="center"><{$list.textdateselect}></td>
                <td class="center"><{$list.selectfile}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
<{include file="db:mymodule2_footer.tpl"}>