[{assign var="edit" value=$oView->getArticle()}]
[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
[{if $readonly }]
    [{assign var="readonly" value="readonly disabled"}]
[{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<script type="text/javascript">
<!--
function editThis( sID )
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
[{if !$oxparentid}]
    window.onload = function ()
    {
        [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
        var oField = top.oxid.admin.getLockTarget();
        oField.onchange = oField.onkeyup = oField.onmouseout = top.oxid.admin.unlockSave;
    }
[{/if}]
//-->
</script>

<p style="color:green;">
Hinweis: der Ziel Link muss bereits in der OXSEO Tabelle vorhanden sein (dh. Artikel muss einmal aufgerufen worden sein DETAILANSICHT), sonst kommt ein 404 nach Hinzufügen des Redirect!<br><br>
</p>

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="dre_redirect_controller">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" enctype="multipart/form-data" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="dre_redirect_controller">
    <input type="hidden" name="fnc" value="">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="voxid" value="[{$oxid}]">
    <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
    <input type="hidden" name="editval[oxarticles__oxid]" value="[{$oxid}]">
    <input type="text"   name="editval[oldLink]" size="130" value ="beispiel/url.html">
    <br/>
    <br/>
    <br/>
    Alten Eintrag ueberschreiben? Sicher? Der Artikel ist dann nicht mehr über den alten Link erreichbar!<br>
    <input type="checkbox" name="editval[overwrite]" value="1">
    </br>
    </br>
    <input type="submit" class="edittext" name="save" value="[{oxmultilang ident="GENERAL_SAVE"}]" onClick="Javascript:document.myedit.fnc.value='save'"><br>
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
    <p>
        Hinweis: Der Browser cached nochmal separat 301 Weiterleitungen!!! Bitte im privaten Fenster mit ?nocache=1 testen <br/><br/><br/>
        <a href="[{$oViewConf->getBaseDir()}][{$oldLink}]?nocache=1" target='_blank'>[{$oViewConf->getBaseDir()}][{$oldLink}]</a>
    </p>
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
