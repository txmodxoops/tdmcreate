<!-- Header -->
<{includeq file="db:tdmcreate_header.tpl"}>
<!-- Display tables list -->
<{if $tables_list}>
    <table class='outer width100'>
        <thead>
        <tr>
            <th class='center cell cell-width1-2'><{$smarty.const._AM_TDMCREATE_ID_LIST}></th>
            <th class='center cell cell-width2-2'><{$smarty.const._AM_TDMCREATE_NAME_LIST}></th>
            <th class='center cell cell-width3-2'><{$smarty.const._AM_TDMCREATE_IMAGE_LIST}></th>
            <th class='center cell cell-width4-2'><{$smarty.const._AM_TDMCREATE_NBFIELDS_LIST}></th>
            <th class='center cell cell-width5-2'><{$smarty.const._AM_TDMCREATE_PARENT_LIST}></th>
            <th class='center cell cell-width6-2'><{$smarty.const._AM_TDMCREATE_INLIST_LIST}></th>
            <th class='center cell cell-width7-2'><{$smarty.const._AM_TDMCREATE_INFORM_LIST}></th>
            <th class='center cell cell-width8-2'><{$smarty.const._AM_TDMCREATE_ADMIN_LIST}></th>
            <th class='center cell cell-width9-2'><{$smarty.const._AM_TDMCREATE_USER_LIST}></th>
            <th class='center cell cell-width10-2'><{$smarty.const._AM_TDMCREATE_BLOCK_LIST}></th>
            <th class='center cell cell-width11-2'><{$smarty.const._AM_TDMCREATE_MAIN_LIST}></th>
            <th class='center cell cell-width12-2'><{$smarty.const._AM_TDMCREATE_SEARCH_LIST}></th>
            <th class='center cell cell-width13-2'><{$smarty.const._AM_TDMCREATE_REQUIRED_LIST}></th>
            <th class='center cell cell-width14-2'><{$smarty.const._AM_TDMCREATE_FORMACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=table from=$tables_list}>
            <{if $table.id > 0}>
                <tr id="table_<{$table.id}>" order="<{$table.order}>" class="tables-fields toggleMain">
                    <td class='center cell cell-width1-2 bold'>&#40;<{$table.lid}>&#41;
                        <a href="#" title="Toggle"><img class="imageToggle" src="<{$modPathIcon16}>/toggle.png" alt="Toggle"/></a>
                    </td>
                    <td class='center cell cell-width2-2' style="text-decoration: underline;">
                        <class
                        ='bold'><{$table.name}></td>
                    <td class='center cell cell-width3-2'><img src="<{xoModuleIcons32}><{$table.image}>" title="<{$table.name}>" alt="<{$table.name}>"/></td>
                    <td class='center cell cell-width4-2 bold'><{$table.nbfields}></td>
                    <td class='center cell cell-width5-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width6-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width7-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width8-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width9-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width10-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width11-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width12-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='center cell cell-width13-2'><img src="<{$modPathIcon16}>/fields.png"/></td>
                    <td class='xo-actions center cell cell-width14-2'>
                        <a href="tables.php?op=edit&amp;table_mid=<{$table.mid}>&amp;table_id=<{$table.id}>" title="<{$smarty.const._AM_TDMCREATE_EDIT_TABLE}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._AM_TDMCREATE_EDIT_TABLE}>"/>
                        </a>
                        <a href="fields.php?op=edit&amp;field_mid=<{$table.mid}>&amp;field_tid=<{$table.id}>" title="<{$smarty.const._AM_TDMCREATE_EDIT_FIELDS}>">
                            <img src="<{xoModuleIcons16 inserttable.png}>" alt="<{$smarty.const._AM_TDMCREATE_EDIT_FIELDS}>"/>
                        </a>
                        <a href="tables.php?op=delete&amp;field_tid=<{$table.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>"/>
                        </a>
                    </td>
                </tr>
                <tr class="toggleChild">
                    <td class="sortable" colspan="14"><{includeq file="db:tdmcreate_fields_item.tpl" table=$table}></td>
                </tr>
            <{/if}>
        <{/foreach}>
        </tbody>
    </table>
    <br/>
    <br/>
    <!-- Display modules navigation -->
    <div class="clear">&nbsp;</div>
    <{if $pagenav}>
        <div class="xo-pagenav floatright"><{$pagenav}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{/if}>
<{if $error}>
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>
<!-- Display module form (add,edit) -->
<{if $form}>
    <div class="spacer"><{$form}></div>
<{/if}>
<!-- Footer -->
<{*<{includeq file="db:tdmcreate_footer.tpl"}>*}>
