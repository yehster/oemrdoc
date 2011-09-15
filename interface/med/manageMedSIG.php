<?php
require_once("../manageParentEntry.php");

if(isset($_REQUEST['medSIGUUID']))
{
    $medSIG=$em->getRepository('library\doctrine\Entities\MedicationSIG')->find($_REQUEST['medSIGUUID']);
}


switch($task)
{
    case "update":
        if(!isset($medSIG))
        {
            header("HTTP/1.0 403 Forbidden");
            echo "No MedSIG Entry";
            return;
        }           
        if(isset($_REQUEST['qty']))
        {
            $qty=floatval($_REQUEST['qty']);
            $medSIG->setQuantity($qty);
        }

        if(isset($_REQUEST['units']))
        {
            $units=$_REQUEST['units'];
            $medSIG->setUnits($units);
        }

        if(isset($_REQUEST['route']))
        {
            $route=$_REQUEST['route'];
            $medSIG->setRoute($route);
        }

        if(isset($_REQUEST['schedule']))
        {
            $schedule = $_REQUEST['schedule'];
            $medSIG->setSchedule($schedule);
        }

        $em->flush();
        break;
}

require_once("/var/www/openemr/library/doctrine/interface/refreshCheck.php");
?>
