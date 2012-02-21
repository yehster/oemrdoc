<?php
require_once("../../init-session.php");
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
        $patientEvent=create_patient_event($em,$user,$pat,$for_user,$event_type);
        break;
}   
if(isset($_REQUEST['refresh']))
{
    if($_REQUEST['refresh']=='yes')
    {
        require_once("$doctrineroot/ui/Widgets/PatientEvents/PatientEventsUtil.php");
        $DOM=new DOMDocument("1.0","utf-8");
        $events_elem=generate_patient_events($em,$DOM,null,$pat,$patientEvent);
        echo $DOM->saveXML($events_elem);
    }
}
?>
