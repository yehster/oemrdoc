<?php

require_once("checkBillingAuth.php");

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


if($doc->getOEMREncounter()==null)
{
    // No Encounter set, need to create one.

    $_POST['form_date']=date("YYYY-MM-DD",$doc->getDateofservice());
    $_POST['mode']="new";
    $date             = formData('form_date');
    $onset_date       = formData('form_onset_date');
    $sensitivity      = formData('form_sensitivity');
    $_POST['form_sensitivity']="normal";
    $pc_catid         = formData('pc_catid');
    $facility_id      = formData('facility_id');
    $billing_facility = formData('billing_facility');
    $reason           = formData('reason');
    $mode             = formData('mode');
    $referral_source  = formData('form_referral_source');    
    require_once("/var/www/openemr/library/forms/newpatient/saveNoHTML.php");

    $OEMREnc=$em->getRepository('library\doctrine\Entities\OEMREncounter')->findOneBy(array('encounter'=>$encID,'patient'=>$patID));               

//    $doc->setOEMREncounter($newEncounter);
}

?>
