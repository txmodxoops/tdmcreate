<{include file="db:mymodule3_header.tpl"}>
<table class="mymodule3">
    <thead class="outer">
        <tr class="head">
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_ID}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_TEXT}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_TEXTAREA}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_DHTML}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_CHECKBOX}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_YESNO}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_SELECTBOX}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_USER}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_COLOR}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_IMAGELIST}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_URLFILE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_UPLIMAGE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_UPLFILE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_TEXTDATESELECT}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_SELECTFILE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_PASSWORD}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_COUNTRY_LIST}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_LANGUAGE}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_RADIO}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_STATUS}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_DATETIME}></th>
            <th class="center"><{$smarty.const._MA_MYMODULE3_TF_COMBOBOX}></th>
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
                <td class="center"><img src="<{$mymodule3_upload_url}>/images/testfields/<{$list.uplimage}>" alt="testfields"></td>
                <td class="center"><{$list.uplfile}></td>
                <td class="center"><{$list.textdateselect}></td>
                <td class="center"><{$list.selectfile}></td>
                <td class="center"><{$list.password}></td>
                <td class="center"><{$list.country_list}></td>
                <td class="center"><{$list.language}></td>
                <td class="center"><{$list.radio}></td>
                <td class="center"><{$list.status}></td>
                <td class="center"><{$list.datetime}></td>
                <td class="center"><{$list.combobox}></td>
            </tr>
        <{/foreach}>
    </tbody>
</table>
<{include file="db:mymodule3_footer.tpl"}>