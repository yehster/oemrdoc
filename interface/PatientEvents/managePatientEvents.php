<?php
require_once("../../init-em.php");
require_once("managePatientEventsUtilities.php");

if(empty($_SESSION['authUser']))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user";
    return;
}
$user = $_SESSION['authUser'];

$doctrineUser=$em->getRepository('library\doctrine\Entities\User')->findOneBy(array("username"=>$user));

if($doctrineUser==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "Invalid User!";
    return;    
}

if(empty($_REQUEST['patientID']))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No patient set!";
    return;
}

$pat=$em->getRepository('library\doctrine\Entities\Patient')->find($_REQUEST['patientID']);    

if($pat==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "No patient specified!";
    return; 
}

if(!isset($_REQUEST['task']))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No task specified!";
    return; 
    
}
$task=$_REQUEST['task'];
$for_user='admin';

if(isset($_REQUEST['eventType']))
{
    $event_type=$em->getRepository('library\doctrine\Entities\PatientEventStatus')->find($_REQUEST['eventType']);        
}
if($event_type==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "No event_type specified";
    return; 
    
}


switch ($task)
{
    case 'create':
        create_patient_event($em,$user,$pat,$for_user,$event_type);
        break;
}   

?>
