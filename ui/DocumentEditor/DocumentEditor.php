<!DOCTYPE html>
<style type="text/css" media="all">
   @import "DocumentEditor.css";
   @import "medDialog.css";
   @import "sectionForms.css";
   @import "../Review/reviewHistory.css";
   @import "../InfoReview/InfoReview.css";
   @import "MedicationForms/MedSIGdialog.css";
   @import "AddProblem/TextAddProblem.css";
   @import "StatusInfo/StatusInfo.css";
</style>
<script src="/openemr/library/doctrine/ui/InfoReview/InfoReview.js"></script>
<?php
include_once('/var/www/openemr/library/doctrine/init-em.php');
include_once("../../../sha1.js");
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
    $MainSpan->setAttribute("id","main");
    $status=$DOM->createElement("SECTION"," ");
    $status->setAttribute("ID","status");
    $status->setAttribute("hidden",true);
    $MainSpan->appendChild($status);    


    $lockDialog=$DOM->createElement("SECTION");
    $lockDialog->setAttribute("ID","lockDialog");
    
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

    $footerSpan=$DOM->createElement("footer");
    $MainSpan->appendChild($footerSpan);
    if(!$doc->isLocked())
    {
        $lockButton=$DOM->createElement("BUTTON","Lock Document");
        $lockButton->setAttribute("FUNC","lock");
        $footerSpan->appendChild($lockButton);
    }
    else
    {
        $lockedBy=$doc->getLockedBy();
        $lockedUser=$em->getRepository("library\doctrine\Entities\OEMRUser")->findOneBy(array("username"=>$lockedBy));
        $signature=$DOM->createElement("DIV","Electronically Signed by ".$lockedUser->getLname().",".$lockedUser->getFname()." on ".$doc->getModified()->format(DATE_COOKIE));
        $footerSpan->appendChild($signature);
    }

    
    require_once("DocumentInfoUtilities.php");
    require("StatusInfo/StatusInfo.php"); 
?>
<script src="../../../js/jquery-1.7.1.min.js"></script>
<script>
    function refreshEntry(uuid,data)
    {

        selector="[uuid='"+uuid+"']";
        $(selector).replaceWith(data);
        reregisterEvents($(selector));
    }
    var patID=<?php echo $_SESSION['pid'];?>;
</script>
<script src="UpdateNarrative.js"></script>
<script src="deleteEntry.js"></script>
<script src="lockDocument.js"></script>
<script src="addNarrative.js"></script>
<script src="manageMeds.js"></script>
<script src="sectionForms.js"></script>
<script src="DocumentInfoUtilities.js"></script>
<script src="../Review/dialogReview.js"></script>
<script src="MedicationForms/manageMedSIG.js"></script>
<script src="MedicationForms/updateMedSIG.js"></script>
<script src="AddProblem/TextAddProblem.js"></script>
<script src="Billing/BillingFunctions.js"></script>
<script src="Billing/FeeSheet.js"></script>
<script src="StatusInfo/StatusInfo.js"></script>
<script src="ManageExistingMeds/ManageExistingMeds.js"></script>
<script src="EntryStatus/StatusUtils.js"></script>
<script src="NarrativeShortcuts/NarrativeShortcuts.js"></script>

<script src="ajaxErrorHandler.js"></script>
<script>
    function reregisterEvents(parent)
    {
        registerNarrativeShortcuts(parent);
    }
    function registerControlEvents()
    {
        registerNarrativeEvents();
        registerDeleteEntryEvents();
        registerLockEvents();
        registerAddNarrativeEvents();
        registerManageMedsEvents();
        registerSectionFormsEvents();
        registerDialogReviewEvents();
        registerManageSIGMedsEvents();        
        registerDocumentInfoEvents();
        registerTextAddProblemEvents();
        registerBillingEvents();
        registerFeeSheetEvents();
        registerUpdateSIGMedsEvents();
        registerNarrativeShortcuts(null);
        
        $(document).ajaxError(handleAjaxError);
        $("#status").click(function(){$(this).hide();});
        $("#status").hide();

        $(".dialog").hide();
        $("#lockDialog").hide();
        $(document).keypress(function(e) {
            var element = e.target.nodeName.toLowerCase();
            if (element != 'input' && element != 'textarea') {
                if (e.keyCode === 8) {
                    dialog=$(".dialog:visible");
                    if(dialog.length>0)
                        {
                            dialog.find("button.back").click();
                            return false;
                        }
                }
            }
        });
        if(top!==null)
            {
                if(top.document.title!==document.title)
                    {
                        top.document.title="OpenEMR:"+document.title;                      
                    }
            }
        // If there is only one details section, then display it.
        formButtons=$("button[func='SHOWFORM']");
        if(formButtons.length==1)
            {
                $(document).ready(function() {formButtons.click()});
            }
        if($("section[name='Billing']").length>0)
            {
                displayBilling();
            }
    }


    window.onload= registerControlEvents;
    
    function checkAjaxActive()
    {
    }

    window.onbeforeunload= function()
    {
        if($.active>0)
            {
                $(document).unbind('ajaxError'); 
            }
    }
</script>
<html>
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $doc->getMetadata()->getLongDesc().":";
                $patient=$doc->getPatient();
                echo $patient->displayName();
                ?></title>
    </head>
    <body docUUID='<?php echo $doc->getUUID()?>'>

        

     
    <?php
        echo $DOM->saveXML($infoSpan);
        echo $DOM->saveXML($MainSpan);

    ?>

        <?php require("medLookupDialog.php"); ?>
        <?php require("dialogSectionForms.php"); ?>
        <?php require("../Review/dialogReview.php"); ?>
        <?php require("MedicationForms/medSIGDialog.php"); ?>
        
    </body>
</html>