<?php

require_once("checkBillingAuth.php");
require_once("billingUtilities.php");
require_once("$doctrineroot/common/checkDocument.php");
require_once("$doctrineroot/ui/DocumentEditor/Billing/FeeSheetRendering.php");
require_once("$doctrineroot/interface/billing/EncounterUtilities.php");

if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    
}

if($doc->getOEMREncounter()==null)
{
    // TODO: Confirm validity of user to bill.
    $prov=$doctrineUser->getID();
    $DOS=$doc->getDateofservice();
    $username=$doctrineUser->getUsername();
    $OEMREnc=createEncounter($em,$pat,$prov,"normal",$DOS,$doc,$username);
}

// create a billing entries for the problems in this document.
$problems=syncProblems($em,$doc,true);

if(isset($_REQUEST['codeType']))
{
    if(isset($_REQUEST['codeVal']))
    {
        $codeType=$_REQUEST['codeType'];
        $codeVal=$_REQUEST['codeVal'];
        // we need to create or change the procedure code for this request
        require_once("CPTUtilities.php");
        billForCPT($em,$doc,$codeVal,$codeType,$problems);
        
    }
    $_SESSION['encounter']=$doc->getOEMREncounter()->getEncounter();
}


$DOM = new DOMDocument("1.0","utf-8");
$table=FeeSheetRender($DOM,$DOM,$doc->getOEMREncounter());

echo $DOM->saveXML($table);
?>
