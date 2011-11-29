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
    $needle=$diagCode.":";
    $strJust=$OEMRBE->getJustify();
    $diagLoc=strpos($strJust,$needle);
    if($justify=="true")
    {
        if($diagLoc===false)
        {
            $strJust=$OEMRBE->getJustify().$needle;
        }
        
    }
    else
    {
        if($diagLoc!==false)
        {
            $strJust=substr($OEMRBE->getJustify(),0,$diagLoc);
            $strJust.=substr($OEMRBE->getJustify(),$diagLoc+strlen($needle));
        }
    }
    $OEMRBE->setJustify($strJust);
    $em->flush();
    echo $strJust;
}
if(isset($_REQUEST['Fee']))
{
    $OEMRBE->setFee(floatval($_REQUEST['Fee']));
    $em->flush();
    echo $OEMRBE->getFee();
}

if(isset($_REQUEST['Modifier']))
{
    $OEMRBE->setModifier($_REQUEST['Modifier']);
    $em->flush();
    echo $OEMRBE->getModifier();
}



?>
