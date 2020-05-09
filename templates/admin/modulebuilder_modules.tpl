<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display modules list -->
<{if $modules_list}>
    <table class='outer width100'>
        <tr>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_ID}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_NAME}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_VERSION}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_IMAGE}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_RELEASE}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_STATUS}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_ADMIN_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_USER_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_BLOCKS_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_SEARCH_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_COMMENTS_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_NOTIFICATIONS_LIST}></th>
            <th class='center'><{$smarty.const._AM_MODULEBUILDER_MODULE_PERMISSIONS_LIST}></th>
            <th class='center width5'><{$smarty.const._AM_MODULEBUILDER_FORMACTION}></th>
        </tr>
        <{foreach item=module from=$modules_list key=mod_id}>
            <tr id="module<{$module.id}>" class="modules">
                <td class='center bold'><{$module.id}></td>
                <td class='center bold green'><{$module.name}></td>
                <td class='center'><{$module.version}></td>
                <td class='center'><img src="<{$tdmc_upload_imgmod_url}>/<{$module.image}>" height="35"/></td>
                <td class='center'><{$module.release}></td>
                <td class='center'><{$module.status}></td>
                <td class='center'><img id="loading_img_admin<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip" id="img_admin<{$module.id}>"
                                                                                                                                                                                                                                      onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_admin: <{if $module.admin == 1}>0<{else}>1<{/if}> }, 'img_admin<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                      src="<{xoModuleIcons16}><{$module.admin}>.png"
                                                                                                                                                                                                                                      alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                      title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_user<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip" id="img_user<{$module.id}>"
                                                                                                                                                                                                                                     onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_user: <{if $module.user}>0<{else}>1<{/if}> }, 'img_user<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                     src="<{xoModuleIcons16}><{$module.user}>.png"
                                                                                                                                                                                                                                     alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                     title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_blocks<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip" id="img_blocks<{$module.id}>"
                                                                                                                                                                                                                                       onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_blocks: <{if $module.blocks}>0<{else}>1<{/if}> }, 'img_blocks<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                       src="<{xoModuleIcons16}><{$module.blocks}>.png"
                                                                                                                                                                                                                                       alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                       title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_search<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip" id="img_search<{$module.id}>"
                                                                                                                                                                                                                                       onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_search: <{if $module.search}>0<{else}>1<{/if}> }, 'img_search<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                       src="<{xoModuleIcons16}><{$module.search}>.png"
                                                                                                                                                                                                                                       alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                       title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_comments<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                         id="img_comments<{$module.id}>"
                                                                                                                                                                                                                                         onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_comments: <{if $module.comments}>0<{else}>1<{/if}> }, 'img_comments<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                         src="<{xoModuleIcons16}><{$module.comments}>.png"
                                                                                                                                                                                                                                         alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                         title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_notifications<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                              id="img_notifications<{$module.id}>"
                                                                                                                                                                                                                                              onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_notifications: <{if $module.notifications}>0<{else}>1<{/if}> }, 'img_notifications<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                              src="<{xoModuleIcons16}><{$module.notifications}>.png"
                                                                                                                                                                                                                                              alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                              title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='center'><img id="loading_img_permissions<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                            id="img_permissions<{$module.id}>"
                                                                                                                                                                                                                                            onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_permissions: <{if $module.permissions}>0<{else}>1<{/if}> }, 'img_permissions<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                            src="<{xoModuleIcons16}><{$module.permissions}>.png"
                                                                                                                                                                                                                                            alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                            title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                </td>
                <td class='xo-actions txtcenter width5'>
                    <a href="modules.php?op=edit&amp;mod_id=<{$module.id}>" title="<{$smarty.const._EDIT}>">
                        <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>"/>
                    </a>
                    <a href="modules.php?op=delete&amp;mod_id=<{$module.id}>" title="<{$smarty.const._DELETE}>">
                        <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>"/>
                    </a>
                    <a href="building.php?op=build&amp;mod_id=<{$module.id}>"><img src="<{xoModuleIcons16 forward.png}>"
                                                                                     alt="<{$smarty.const._AM_MODULEBUILDER_ADMIN_CONST}>"
                                                                                     title="<{$smarty.const._AM_MODULEBUILDER_ADMIN_CONST}>"></a>
                </td>
            </tr>
        <{/foreach}>
    </table>
    <br/>
    <br/>
    <!-- Display modules navigation -->
    <div class="clear">&nbsp;</div>
    <{if $pagenav}>
        <div class="xo-pagenav floatright"><{$pagenav}></div>
        <div class="clear spacer"></div>
    <{/if}>
<{else}>  <!-- Display module images on edit page -->
    <!-- Display module form (add,edit) -->
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
