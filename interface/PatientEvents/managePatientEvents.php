<?php
require_once("../../init-em.php");

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

if(empty($_REQUEST['patient_id']))
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No patient set!";
    return;
}

$pat=$em->getRepository('library\doctrine\Entities\Patient')->find($_REQUEST['patient_id']);    

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


?>
