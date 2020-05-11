<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display settings list -->
<{if $settings_list}>
    <form name="setting">
        <table class='outer width100'>
            <tr>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_ID}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_NAME}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_VERSION}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_IMAGE}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_RELEASE}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_STATUS}></th>
                <th class='center'><{$smarty.const._AM_MODULEBUILDER_SETTING_CHOISE}></th>
                <th class='center width5'><{$smarty.const._AM_MODULEBUILDER_FORM_ACTION}></th>
            </tr>
            <{foreach item=set from=$settings_list key=set_id}>
                <tr id="setting_<{$set.id}>" class="settings">
                    <td class='center bold'><{$set.id}></td>
                    <td class='center bold green'><{$set.name}></td>
                    <td class='center'><{$set.version}></td>
                    <td class='center'><img src="<{$tdmc_upload_imgmod_url}>/<{$set.image}>" height="35"/></td>
                    <td class='center'><{$set.release}></td>
                    <td class='center'><{$set.status}></td>
                    <td class='center'><input class="rSetting" type='radio' id='set_id<{$set.id}>' name='rNumber' value='<{$set.id}>'/>
                        <img id="loading_img_type<{$set.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip" id="img_type<{$set.id}>"
                                                                                                                                                                                                                       onclick="modulebuilder_setStatus( { op: 'display', set_id: <{$set.id}>, set_type: <{if $set.type}>0<{else}>1<{/if}> }, 'img_type<{$set.id}>', 'settings.php' )"
                                                                                                                                                                                                                       src="<{xoModuleIcons16}><{$set.type}>.png"
                                                                                                                                                                                                                       alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$set.name}>"
                                                                                                                                                                                                                       title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$set.name}>"/></td>
                    <td class='xo-actions txtcenter width5'>
                        <a href="settings.php?op=edit&amp;set_id=<{$set.id}>" title="<{$smarty.const._EDIT}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>"/>
                        </a>
                        <a href="settings.php?op=delete&amp;set_id=<{$set.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>"/>
                        </a>
                    </td>
                </tr>
            <{/foreach}>
        </table>
    </form>
    <br/>
    <br/>
    <!-- Display settings navigation <input type='radio' name='settings' value='<{$set.type}>' />-->
    <div class="clear">&nbsp;</div>
    <{if $pagenav}>
        <div class="xo-pagenav floatright"><{$pagenav}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{else}>
    <!-- Display setting form (add,edit) -->
    <{if $form}>
        <div class="spacer"><{$form}></div>
    <{/if}>
<{/if}>
<{if $error}>
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>
<!-- Footer -->
<{includeq file="db:modulebuilder_footer.tpl"}>
