<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display files list -->
<{if $files_list|default:''}>
    <table class='outer width100'>
        <tr>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_ID}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_MID_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_TYPE_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_INFOLDER_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_NAME_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_EXTENSION_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_FILE_UPLOAD_LIST}></th>
            <th class='center width5'><{$smarty.const._AM_MODULEBUILDER_FORM_ACTION}></th>
        </tr>
        <{foreach item=file from=$files_list key=file_id}>
            <tr id="file<{$file.id}>" class="files">
                <td class='center bold width5'><{$file.id}></td>
                <td class='center bold'><{$file.mid}></td>
                <td class='center bold'><{$file.type}></td>
                <td class='center bold blue'><{$file.infolder}></td>
                <td class='center bold green'><{$file.name}></td>
                <td class='center bold red'><{$file.extension}></td>
                <td class='center bold'><{$file.upload}></td>
                <td class='xo-actions txtcenter width5'>
                    <a href="morefiles.php?op=edit&amp;file_id=<{$file.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>">
                    </a>
                    <a href="morefiles.php?op=delete&amp;file_id=<{$file.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>">
                    </a>
                </td>
            </tr>
        <{/foreach}>
    </table>
    <br>
    <br>
    <!-- Display files navigation -->
    <div class="clear">&nbsp;</div>
    <{if $pagenav|default:''}>
        <div class="xo-pagenav floatright"><{$pagenav}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{else}>  <!-- Display file images on edit page -->
    <!-- Display file form (add,edit) -->
    <{if $form|default:''}>
        <div class="spacer"><{$form}></div>
    <{/if}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>
<!-- Footer -->
<{includeq file="db:modulebuilder_footer.tpl"}>
