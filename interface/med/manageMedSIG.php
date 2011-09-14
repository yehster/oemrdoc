<?php
require_once("manageParentEntry.php");
if(isset($_REQUEST['qty']))
{
    $qty=floatval($_REQUEST['qty']);
}

if(isset($_REQUEST['units']))
{
    $units=$_REQUEST['units'];
}

if(isset($_REQUEST['route']))
{
    $route=$_REQUEST['route'];
}

if(isset($_REQUEST['schedule']))
{
    $schedule = $_REQUEST['schedule'];
}

if(isset($_REQUEST['medSIGUUID']))
{
    $medSIG=$em->getRepository('library\doctrine\Entities\MedicationSIG')->find($_REQUEST['medSIGUUID']);
}
else
{
    $medSIGs=$parentEntry->getSIGs();
    $medSIG=$medSIGs[0];
}


require_once("refreshCheck.php");
?>
