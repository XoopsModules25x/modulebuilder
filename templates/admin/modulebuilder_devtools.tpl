<!-- Header -->
<style>
    .even, .odd {
        border-top: 1px solid #cccccc;
        border-bottom: 1px solid #cccccc;
    }
    .td-padding {
        padding:20px 0 !important;
    }
</style>
<{includeq file="db:modulebuilder_header.tpl"}>
<!-- Display tables list -->
<{if $devtools_list|default:''}>
    <table class='outer width100'>
        <tr>
            <th class='center' colspan=2><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS}></th>
        </tr>
        <tr class='even'>
            <td class='left td-padding'>
                <h5><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_FQ}></h5>
                <p><{$fq_desc}></p>
            </td>
            <td class='td-padding'><{$fq_form}></td>
        </tr>
        <tr class='odd'>
            <td class='left td-padding'>
                <h5><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL}></h5>
                <p><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_DESC}></p>
            </td>
            <td class='td-padding'><{$cl_form}></td>
        </tr>
        <tr class='even'>
            <td class='left td-padding'>
                <h5><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_TAB}></h5>
                <p><{$tab_desc}></p>
            </td>
            <td class='td-padding'><{$tab_form}></td>
        </tr>
    </table>
<{/if}>
<{if $clresults|default:''}>
    <div class="row">
        <div class="col-xs-12">
            <h3 class='center'><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULTS}></h3>
            <div class="col-xs-12" style="margin-bottom:50px;font-size:80%">
                <p><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_LEGEND}></p>
                <p>
                    <img src="<{$modPathIcon16}>/1.png" alt="<{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS}>"/>                
                    <{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS}> <{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS_DESCR}>
                </p>
                <p>
                    <img src="<{$modPathIcon16}>/0.png" alt="<{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_FAILED}>"/>                
                    <{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_FAILED}>
                </p>
            </div>
            <{foreach item=clresultfile from=$clresults key=key}>
                <p><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_FILE}> <{$key}></p>
                <ul>
                    <{foreach item=clresult from=$clresultfile}>
                        <li><img src="<{$modPathIcon16}>/<{$clresult.found}>.png" alt="<{if $clresult.first}><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_SUCCESS}><{else}><{$smarty.const._AM_MODULEBUILDER_DEVTOOLS_CL_RESULT_FAILED}><{/if}>"/> <{$clresult.define}> <{if $clresult.first}>(<{$clresult.first}>)<{/if}></li>
                    <{/foreach}>
                </ul>
            <{/foreach}>
        </div>
        <div class="col-xs-12 center">
            <a href="devtools.php?op=list" title="<{$smarty.const._BACK}>"><{$smarty.const._BACK}></a>
        </div>
    </div>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg">
        <strong><{$error}></strong>
    </div>
<{/if}>

<!-- Footer -->
<{includeq file="db:modulebuilder_footer.tpl"}>
