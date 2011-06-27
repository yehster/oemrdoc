<!DOCTYPE html>
<style type="text/css" media="all">
   @import "/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.css";
</style>
<?php
include_once("../../../sha1.js");
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once('DocumentEditorUtilities.php');
if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        echo "Document:".$docUUID;
        return;
    }
}
    $DOM = new DOMDocument("1.0","utf-8");
    $MainSpan=$DOM->createElement("SPAN");

    $status=$DOM->createElement("SECTION"," ");
    $status->setAttribute("ID","status");
    $status->setAttribute("hidden",true);
    $MainSpan->appendChild($status);    


    $lockDialog=$DOM->createElement("SECTION");
    $lockDialog->setAttribute("ID","lockDialog");
    $lockDialog->setAttribute("HIDDEN",true);    
    
    $divLockPwd=$DOM->createElement("DIV","Confirm that you want to lock this document by entering your password.");
    $lockDialog->appendChild($divLockPwd);
    
    $lblPwd=$DOM->createElement("SPAN","Password:");
    $lockDialog->appendChild($lblPwd);
    
    $lockPassword=$DOM->createElement("INPUT");
    $lockPassword->setAttribute("TYPE","PASSWORD");
    $lockPassword->setAttribute("ID","lockPass");
   
    
    $lockDialog->appendChild($lockPassword);

    $buttonDiagLock=$DOM->createElement("BUTTON","Lock");
    $buttonDiagLock->setAttribute("ID","lockDocument");
    $buttonDiagLock->setAttribute("docUUID",$docUUID);
    $lockDialog->appendChild($buttonDiagLock);    
    
    
    $buttonDiagCancel=$DOM->createElement("BUTTON","Cancel");
    $buttonDiagCancel->setAttribute("ID","cancelLock");    
    $lockDialog->appendChild($buttonDiagCancel);
    

    $MainSpan->appendChild($lockDialog);
    
    foreach($doc->getItems() as $docItem)
    {
        populateEditorDOM($DOM,$MainSpan,$docItem,1);
    }    

    $footerSpan=$DOM->createElement("SPAN");
    $MainSpan->appendChild($footerSpan);
    
    $lockButton=$DOM->createElement("BUTTON","Lock Document");
    $lockButton->setAttribute("FUNC","lock");
    $footerSpan->appendChild($lockButton);
    

    
?>
<script src="../../../js/jquery-1.6.1.min.js"></script>
<script>
    function refreshEntry(uuid,data)
    {
        selector="[uuid='"+uuid+"']";
        $(selector).html(data);
    }
</script>
<script src="UpdateNarrative.js"></script>
<script src="deleteEntry.js"></script>
<script src="lockDocument.js"></script>
<script src="addProblem.js"></script>


<script src="ajaxErrorHandler.js"></script>
<script>
    function registerControlEvents()
    {
        registerNarrativeEvents();
        registerDeleteEntryEvents();
        registerLockEvents();
        registerAddProblemEvents();
        $("body").ajaxError(handleAjaxError);
        $("#status").click(function(){$(this).attr("hidden",true);});
    }
    window.onload= registerControlEvents;
</script>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></title>
    </head>
    <body>
        <section ID="problemDialog" class="dialog" hidden="true">
            <input type="text" id="txtProblem"/>
            <button id="useTxtProblem">Add Problem</button>
            <section ID="problemFavorites"/> </section>
            <section ID="problemSearch"/> </section>
        
        </section>
    <?php
        echo $DOM->saveXML($MainSpan);
     ?>
    </body>
</html>