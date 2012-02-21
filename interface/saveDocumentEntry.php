<?php
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-session.php');
$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_REQUEST['docEntryUUID']))
{
    $docEntryUUID = $_REQUEST['docEntryUUID'];
    $docEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($docEntryUUID);
}
if(isset($_REQUEST['docEntryData']))
{
    $docEntryData = $_REQUEST['docEntryData'];
}
if($docEntry!=null)
{
    //
    if($docEntry->getPatient()->getId()==$pat->getId())
    {
        // only save if the document Entry matches the current patient

        // the "text" field is hard coded for now, but will
        if(!$docEntry->updateProperty('text',$docEntryData))
        {
            echo "failed updating";
        }
        $em->flush();
    }
    else
    {
        echo "patient/document mismatch:".$docEntryUUID;
    }
}
else
{
    // The document doesn't exist, so failure
    echo "failed saving:".$docEntryUUID;
}


?>
