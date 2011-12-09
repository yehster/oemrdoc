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

if(isset($_REQUEST['medUUID']))
{
    $medUUID = $_REQUEST["medUUID"];
    $med =$em->getRepository('library\doctrine\Entities\MedicationEntry')->find($medUUID);
}
$medCopy=$med->copy($user);
  
$parentEntry->getItem()->addEntry($medCopy);
$em->persist($medCopy);
foreach($med->getSIGs() as $SIG)
{
    $medCopy->getItem()->addEntry($SIG->copy($user));
}

if($task=="DISCONTINUE")
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
