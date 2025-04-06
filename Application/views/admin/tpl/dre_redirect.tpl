[{assign var="edit" value=$oView->getArticle()}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
window.onload = function ()
{
    top.reloadEditFrame();
    [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
    [{/if}]
}
function editThis(sID)
{
    var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = top.basefrm.list.sDefClass;

    //forcing edit frame to reload after submit
    top.forceReloadingEditFrame();

    var oSearch = top.basefrm.list.document.getElementById( "search" );
    oSearch.oxid.value = sID;
    oSearch.actedit.value = 0;
    oSearch.submit();
}
//-->
</script>

<p style="color:green;">
Hinweis: der Ziel Link muss bereits in der OXSEO Tabelle vorhanden sein (dh. Artikel muss einmal aufgerufen worden sein), sonst kommt ein 404 nach Hinzufügen des Redirect!<br><br>
</p>

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="dre_redirect">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" enctype="multipart/form-data" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="dre_redirect">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <input type="hidden" name="editval[article__oxid]" value="[{$oxid}]">
    <input type="text"   name="editval[oldLink]" size="130" value ="beispiel/url.html">
    Alten Eintrag ueberschreiben?
    <input type="checkbox" name="editval[overwrite]" value="1">
    </br>
    </br>
    <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'"" ><br>
</form>
</br>
<p style="color:green;">
    [{$info}]
</p>
</br>

<p style="color:red;">
    [{$errorCount}]
</p>

[{if $oldLink}]
    Hint: Browsers cache 301 redirects, so be sure to empty cache for the page your are checking your new redirects. </br>
    checking old url with curl (ommit cache) (
    <a href="[{$oViewConf->getBaseDir()}][{$oldLink}]"  target='_blank'>[{$oViewConf->getBaseDir()}][{$oldLink}]</a>)
     redirect destination:
[{/if}]

</br>
</br>
[{if $result}]
    HTTP Header: </br>
    [{$result}]
[{/if}]
</br>

<p style="color:red;">
    [{$error}]
</p>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
