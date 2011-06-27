<?php
require_once('/var/www/openemr/library/doctrine/init-em.php');
require_once('../ui/Editor/EditorUtilities.php');



$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");    
    echo "No user set!";
    return;
}
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
    
}
if(is_null($pat))
{
    header("HTTP/1.0 403 Forbidden");
    echo "No patient specified!";
    return;
    
}

if(isset($_REQUEST["task"]))
{
    $task=$_REQUEST["task"];
}
else
{
    header("HTTP/1.0 403 Forbidden");
    echo "No task specified!";
    return;
}

if(isset($_REQUEST["parentUUID"]))
{
    $parentUUID = $_REQUEST["parentUUID"];
    $parentEntry =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($parentUUID);
}

?>
