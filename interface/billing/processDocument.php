<?php

require_once("checkBillingAuth.php");
require_once("billingUtilities.php");

if(isset($_REQUEST['docUUID']))
{
    $docUUID = $_REQUEST['docUUID'];
    $doc = $em->getRepository('library\doctrine\Entities\Document')->find($docUUID);
    if($doc==null)
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "Document:".$docUUID;
        return;
    }
}

if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    
}

if($doc->getOEMREncounter()==null)
{
    // No Encounter set, need to create one.

    $_POST['form_date']=date("Y-m-d",$doc->getDateofservice()->getTimestamp());
    $_POST['mode']="new";
    $_POST['form_sensitivity']="normal";
    
    // TODO: fix hard coded values for billing
    $_POST['pc_catid']=5;
    $_POST['facility_id']=3;
    $_POST['billing_facility']=3;
    $_POST['reason']="Autogenerated for document:".$doc->getUUID();
    
    require_once("/var/www/openemr/interface/globals.php");
    require_once("/var/www/openemr/interface/forms/newpatient/saveDoctrine.php");
    $OEMREnc=$em->getRepository('library\doctrine\Entities\OEMREncounter')->findOneBy(array('encounter'=>$_SESSION['encounter'],'patient'=>$patID));               

    $doc->setOEMREncounter($OEMREnc);
    $em->flush();

}

// create a billing entries for the problems in this document.
syncProblems($em,$doc);

if(isset($_REQUEST['codeType']))
{
    if(isset($_REQUEST['codeVal']))
    {
        $codeType=$_REQUEST['codeType'];
        $codeVal=$_REQUEST['codeVal'];
        echo $codeType.":".$codeVal;
    }
}

?>
