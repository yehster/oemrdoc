<!DOCTYPE html>
<style type="text/css" media="all">
   @import "/openemr/library/doctrine/ui/DocumentEditor/DocumentEditor.css";
</style>
<?php
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
    $Body=$DOM->createElement("BODY");

    $status=$DOM->createElement("SECTION"," ");
    $status->setAttribute("ID","status");
    $status->setAttribute("hidden",true);
    $Body->appendChild($status);    
    
    foreach($doc->getItems() as $docItem)
    {
        populateEditorDOM($DOM,$Body,$docItem,1);
    }    

    $footerSpan=$DOM->createElement("SPAN");
    $Body->appendChild($footerSpan);
    
    $lockButton=$DOM->createElement("BUTTON","Lock Document");
    $lockButton->setAttribute("FUNC","lock");
    $lockButton->setAttribute("docUUID",$docUUID);
    $footerSpan->appendChild($lockButton);
?>
<script src="../../../js/jquery-1.6.1.min.js"></script>
<script src="UpdateNarrative.js"></script>
<script src="deleteEntry.js"></script>
<script src="lockDocument.js"></script>

<script src="ajaxErrorHandler.js"></script>
<script>
    function registerControlEvents()
    {
        registerNarrativeEvents();
        registerDeleteEntryEvents();
        registerLockEvents();
        $("body").ajaxError(handleAjaxError);
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
    <?php
        echo $DOM->saveXML($Body);
     ?>
</html>