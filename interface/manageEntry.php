<?php
include_once("/var/www/openemr/interface/globals.php");
include_once('/var/www/openemr/library/doctrine/init-em.php');
$user = $_SESSION['authUser'];
if(isset($_SESSION['pid']))
{
    $patID=$_SESSION['pid'];
    $pat=$em->getRepository('library\doctrine\Entities\Patient')->find($patID);
}
if(isset($_REQUEST['parentEntryUUID']))
{
    $parentEntryUUID = $_REQUEST['parentEntryUUID'];
    $parentEntry = $em->getRepository('library\doctrine\Entities\DocumentEntry')->find($parentEntryUUID);
}
if(isset($_REQUEST['EntryType']))
{
    $EntryType = $_REQUEST['EntryType'];
}
if(isset($_REQUEST['task']))
{
    $task = $_REQUEST['task'];
}
if(isset($_REQUEST['content']))
{
        $content = $_REQUEST['content'];
}


/****************************************************
 * MedicationEntry
 */
if($EntryType=="med")
{

    if(isset($_REQUEST['rxcui']))
    {
        $rxcui = $_REQUEST['rxcui'];
    }
    if(isset($_REQUEST['rxaui']))
    {
        $rxauiID = $_REQUEST['rxaui'];
        $rxaui = $em->getRepository('library\doctrine\Entities\RXNConcept')->find($rxauiID);

    }
    if($task=="create")
    {

        $med = new library\doctrine\Entities\MedicationEntry(null,$pat,$user);
        $med->setText($content,$user);
        $med->setRXCUI($rxcui);
        $med->setRXAUI($rxaui);

        $parentEntry->getItem()->addEntry($med);
        $em->persist($med);
        $em->flush();
        echo $med->getUUID();
        return;
    }
    echo "error:".$task.":".$EntryType;
}


?>
