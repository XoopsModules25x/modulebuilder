<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display building form  -->
<br/>
<{if $building_directory}>
    <table class="outer">
        <thead>
        <tr class="head">
            <th width="80%"><{$smarty.const._AM_MODULEBUILDER_BUILDING_FILES}></th>
            <th width="10%"><{$smarty.const._AM_MODULEBUILDER_BUILDING_SUCCESS}></th>
            <th width="10%"><{$smarty.const._AM_MODULEBUILDER_BUILDING_FAILED}></th>
        </tr>
        </thead>
        <tbody>
        <tr class="even">
            <{if $base_architecture}>
                <td style="padding-left: 30px;"><{$smarty.const._AM_MODULEBUILDER_OK_ARCHITECTURE}></td>
                <td class="center"><img src="<{xoModuleIcons16 on.png}>" alt=""/></td>
                <td>&nbsp;</td>
            <{else}>
                <td style="padding-left: 30px;"><{$smarty.const._AM_MODULEBUILDER_NOTOK_ARCHITECTURE}></td>
                <td>&nbsp;</td>
                <td class="center"><img src="<{xoModuleIcons16 off.png}>" alt=""/></td>
            <{/if}>
        </tr>
        <{foreach item=build from=$builds}>
            <tr class="<{cycle values='odd, even'}>">
                <{if $created}>
                    <td style="padding-left: 30px;"><{$build.list}></td>
                    <td class="center"><img src="<{xoModuleIcons16 on.png}>" alt=""/></td>
                    <td>&nbsp;</td>
                <{else}>
                    <td style="padding-left: 30px;"><{$build.list}></td>
                    <td>&nbsp;</td>
                    <td class="center"><img src="<{xoModuleIcons16 off.png}>" alt=""/></td>
                <{/if}>
            </tr>
        <{/foreach}>
        <tr class="<{cycle values='even, odd'}>">
            <td class="center" colspan="3"><{$building_directory}></td>
        </tr>
        </tbody>
    </table>
    <br/>
<{else}>
    <{if $checkResults}>
        <h3 class="red"><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD}></h3>
        <p><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD_FOUND}></p>
        <{foreach item=check from=$checkResults}>
            <p><img src="<{$modPathIcon16}>/<{$check.icon}>.png" alt=""/><{$check.info}></p>
        <{/foreach}>
        <p><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD_SOLVE}></p>
        <p class='small'><br><br>------------------------------------------------------------------------------------------------------------------
            <br><img src="<{$modPathIcon16}>/error.png" alt=""/><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD_ERROR_DESC}><br><img src="<{$modPathIcon16}>/warning.png" alt=""/><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD_WARNING_DESC}><br>
        </p>
    <{/if}>
    <{if $checkResultsNice}>
        <h3 class="red"><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD}></h3>
        <p><img src="<{xoModuleIcons16 on.png}>" alt=""/><{$smarty.const._AM_MODULEBUILDER_CHECKPREBUILD_NOERRORS}></p>
    <{/if}>
    <{if $form}>
        <{$form}>
    <{/if}>
<{/if}>
<!-- Footer -->
<{includeq file="db:modulebuilder_footer.tpl"}>
