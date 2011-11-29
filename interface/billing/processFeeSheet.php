<?php
require_once("checkBillingAuth.php");

if(isset($_REQUEST['BillingID']))
{
    $OEMRBE=$em->getRepository('library\doctrine\Entities\OEMRBillingEntry')->find($_REQUEST['BillingID']);
    if(is_null($OEMRBE))
    {
        header("HTTP/1.0 403 Forbidden");    
        echo "No Billing Entry";

    }
}
if(isset($_REQUEST['DiagCode']))
{
    $diagCode=$_REQUEST['DiagCode'];
}
if(isset($_REQUEST['Justify']))
{
    $justify=$_REQUEST['Justify'];
echo $diagCode.":".$justify;
    
}





?>
