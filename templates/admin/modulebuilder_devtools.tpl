<!-- Header -->
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display tables list -->
<{if $devtools_list}>
    <table class='outer width100'>
        <tr class='even'>
            <th class='center' colspan=2><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS}></th>
        </tr>
        <tr>
            <td class='left'>
                <h5><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_FQ}></h5>
                <p><{$fq_desc}></p>
            </td>
            <td><{$fq_form}></td>
        </tr>
        <tr class='odd'>
            <td class='left'>
                <h5><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL}></h5>
                <p><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_DESC}></p>
            </td>
            <td><{$cl_form}></td>
        </tr>
    </table>
<{/if}>
<{if $clresults}>
    <div class="row">
        <div class="col-xs-12">
            <h3 class='center'><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULTS}></h3>
            <{foreach item=clresultfile from=$clresults}>
                <p><{$clresultfile.file}></p>
                <ul>
                    <{foreach item=clresult from=$clresultfile.result}>
                        <li><img src="<{$modPathIcon16}>/<{$clresult.found}>.png" alt=""/> <{$clresult.define}> <{if $clresult.first}>(<{$clresult.first}>)<{/if}></li>
                    <{/foreach}>
                </ul>
            <{/foreach}>
        </div>
        <div class="col-xs-12 center">
            <a href="devtools.php?op=list" title="<{$smarty.const._BACK}>"><{$smarty.const._BACK}></a>
        </div>
    </div>
<{/if}>
<{if $error}>
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>

<!-- Footer -->
<{includeq file="db:modulebuilder_footer.tpl"}>
