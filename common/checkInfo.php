<?php
require_once("$doctrineroot/common/checkUser.php");
if(isset($_REQUEST['patientID']))
{
    $patID=$_REQUEST['patientID'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(!isset($pat))
{
    header("HTTP/1.0 403 Forbidden");
    die("No patient specified!");
    return;
    
}
?>
