<?php

require_once("/var/www/openemr/library/doctrine/interface/manageParentEntry.php");
require_once("$doctrineroot/Entities/EntryStatusCodes.php");
$user = $_SESSION['authUser'];
if($user==null)
{
    header("HTTP/1.0 403 Forbidden");
    echo "Error:no user set!";
    return;
}

if(isset($_REQUEST['infoUUID']))
{
    $infoUUID = $_REQUEST["infoUUID"];
    $info =$em->getRepository('library\doctrine\Entities\DocumentEntry')->find($infoUUID);
}
$infoCopy=$info->copy($user);
  
$parentEntry->getItem()->addEntry($infoCopy);
$em->persist($infoCopy);

if($task=="ACTIVE")
{
    $infoCopy->setStatus(STATUS_ACTIVE);
}
elseif($task=="INACTIVE")
{
    $infoCopy->setStatus(STATUS_INACTIVE);
}
elseif($task=="DISCONTINUE")
{
    $medCopy->setStatus(STATUS_DISCONTINUED);
}
elseif($task=="CONFIRM")
{
    $medCopy->setStatus(STATUS_CONFIRMED);
}
$em->flush();
require_once("../refreshCheck.php");
?>

