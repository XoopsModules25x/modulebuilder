<!-- Header -->
<{include file='db:modulebuilder_admin_header.tpl' }>

<{if $form|default:''}>
    <{$form|default:false}>
<{/if}>
<{if $result|default:''}>
    <{$result|default:false}>
<{/if}>
<{if $error|default:''}>
    <div class="errorMsg"><strong><{$error|default:false}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:modulebuilder_admin_footer.tpl' }>
