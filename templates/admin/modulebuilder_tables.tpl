<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display modules list -->
<{if $modules_list}>
    <table class='outer width100'>
        <thead>
        <tr>
            <th class='center cell cell-width1'><{$smarty.const._AM_MODULEBUILDER_MODULE_ID}></th>
            <th class='center cell cell-width2'><{$smarty.const._AM_MODULEBUILDER_MODULE_NAME_LIST}></th>
            <th class='center cell cell-width3'><{$smarty.const._AM_MODULEBUILDER_MODULE_IMAGE_LIST}></th>
            <th class='center cell cell-width4'><{$smarty.const._AM_MODULEBUILDER_MODULE_NBFIELDS_LIST}></th>
            <th class='center cell cell-width5'><{$smarty.const._AM_MODULEBUILDER_MODULE_ADMIN_LIST}></th>
            <th class='center cell cell-width6'><{$smarty.const._AM_MODULEBUILDER_MODULE_USER_LIST}></th>
            <th class='center cell cell-width7'><{$smarty.const._AM_MODULEBUILDER_MODULE_BLOCKS_LIST}></th>
            <th class='center cell cell-width8'><{$smarty.const._AM_MODULEBUILDER_MODULE_SUBMENU_LIST}></th>
            <th class='center cell cell-width9'><{$smarty.const._AM_MODULEBUILDER_MODULE_SEARCH_LIST}></th>
            <th class='center cell cell-width10'><{$smarty.const._AM_MODULEBUILDER_MODULE_COMMENTS_LIST}></th>
            <th class='center cell cell-width11'><{$smarty.const._AM_MODULEBUILDER_MODULE_NOTIFICATIONS_LIST}></th>
            <th class='center cell cell-width12'><{$smarty.const._AM_MODULEBUILDER_MODULE_PERMISSIONS_LIST}></th>
            <th class='center xo-actions cell cell-width13'><{$smarty.const._AM_MODULEBUILDER_FORMACTION}></th>
        </tr>
        </thead>
        <tbody>
        <{foreach item=module from=$modules_list}>
            <{if $module.id > 0}>
                <tr id="module<{$module.id}>" class="modules toggleMain">
                    <td class='center bold cell cell-width1'>&#40;<{$module.id}>&#41;
                        <a href="#" title="Toggle"><img class="imageToggle" src="<{$modPathIcon16}>/toggle.png" alt="Toggle"/></a>
                    </td>
                    <td class='center bold green name cell cell-width2'><{$module.name}></td>
                    <td class='center'><img src="<{$tdmc_upload_imgmod_url}>/<{$module.image}>" alt="" height="35"/></td>
                    <td class='center'><img src="<{$modPathIcon16}>/fields.png" alt="16"/></td>
                    <td class='center'><img id="loading_img_admin<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                          id="img_admin<{$module.id}>"
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
                    <td class='center'><img id="loading_img_blocks<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                           id="img_blocks<{$module.id}>"
                                                                                                                                                                                                                                           onclick="modulebuilder_setStatus( { op: 'display', mod_id: <{$module.id}>, mod_blocks: <{if $module.blocks}>0<{else}>1<{/if}> }, 'img_blocks<{$module.id}>', 'modules.php' )"
                                                                                                                                                                                                                                           src="<{xoModuleIcons16}><{$module.blocks}>.png"
                                                                                                                                                                                                                                           alt="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"
                                                                                                                                                                                                                                           title="<{$smarty.const._AM_MODULEBUILDER_CHANGE_DISPLAY}>&nbsp;<{$module.name}>"/>
                    </td>
                    <td class='center'><img src="<{$modPathIcon16}>/submenu.png" alt="Submenu" title="Submenu"/></td>
                    <td class='center'><img id="loading_img_search<{$module.id}>" src="<{$modPathIcon16}>/spinner.gif" style="display:none;" title="<{$smarty.const._AM_SYSTEM_LOADING}>" alt="<{$smarty.const._AM_SYSTEM_LOADING}>"/><img style="cursor:pointer;" class="tooltip"
                                                                                                                                                                                                                                           id="img_search<{$module.id}>"
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
                    <td class='xo-actions txtcenter width6'>
                        <a href="modules.php?op=edit&amp;mod_id=<{$module.id}>" title="<{$smarty.const._EDIT}>">
                            <img src="<{xoModuleIcons16 edit.png}>" alt="<{$smarty.const._EDIT}>" alt="<{$smarty.const._EDIT}>"/>
                        </a>
                        <a href="modules.php?op=delete&amp;mod_id=<{$module.id}>" title="<{$smarty.const._DELETE}>">
                            <img src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>" alt="<{$smarty.const._DELETE}>"/>
                        </a>
                        <a href="tables.php?op=new&amp;table_mid=<{$module.id}>" title="<{$smarty.const._ADD}>">
                            <img src="<{xoModuleIcons16 add.png}>" alt="<{$smarty.const._ADD}>" alt="<{$smarty.const._ADD}>"/>
                        </a>
                        <a href="building.php?op=build&amp;mod_id=<{$module.id}>"><img src="<{xoModuleIcons16 forward.png}>"
                                                                                       alt="<{$smarty.const._AM_MODULEBUILDER_ADMIN_CONST}>"
                                                                                       title="<{$smarty.const._AM_MODULEBUILDER_ADMIN_CONST}>"></a>
                    </td>
                </tr>
                <tr class="toggleChild">
                    <td class="sortable" colspan="13"><{includeq file="db:modulebuilder_tables_item.tpl" module=$module}></td>
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
<{includeq file="db:modulebuilder_footer.tpl"}>
